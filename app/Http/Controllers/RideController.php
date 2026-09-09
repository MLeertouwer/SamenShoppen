<?php

namespace App\Http\Controllers;

use App\Enums\PassengerStatus;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Ride;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RideController extends Controller
{
    public function index(Request $request)
    {
        // 1. Pas de status aan van verlopen ritten
        Ride::where('departure_time', '<', now())
            ->whereIn('status', ['open', 'vol'])
            ->update(['status' => 'verlopen']);

        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $selectedDate = $request->get('selected_date');

        // 2. Haal de ritten op van deze maand
        $rides = Ride::with('passengers')
            ->whereMonth('departure_time', $month)
            ->whereYear('departure_time', $year)
            ->get();

        // 3. Controleer en update de status VEILIG zonder andere kolommen te raken
        foreach ($rides as $ride) {
            $statusValue = is_object($ride->status) ? $ride->status->value : $ride->status;

            if ($statusValue !== 'verlopen') {
                $approvedPassengersCount = $ride->passengers
                    ->where('pivot.status', \App\Enums\PassengerStatus::APPROVED->value)
                    ->count();

                // Als de rit vol zit
                if ($approvedPassengersCount >= $ride->max_passengers && $statusValue !== 'vol') {
                    // Gebruik direct de DB query om ALLEEN de status kolom aan te passen
                    Ride::where('id', $ride->id)->update(['status' => 'vol']);
                    $ride->status = 'vol'; // Update ook het object in het geheugen voor de view
                }
                // Als er weer plek is
                elseif ($approvedPassengersCount < $ride->max_passengers && $statusValue === 'vol') {
                    Ride::where('id', $ride->id)->update(['status' => 'open']);
                    $ride->status = 'open'; // Update ook het object in het geheugen voor de view
                }
            }
        }

        // Groepeer de ritten op datum
        $ridesPerDay = $rides->groupBy(function ($ride) {
            return Carbon::parse($ride->departure_time)->format('Y-m-d');
        });

        return view('rides.index', compact('ridesPerDay', 'month', 'year', 'selectedDate'));
    }


    public function show($id)
    {
        // 1. Haal het Ride object op
        $ride = Ride::with(['driver.user', 'passengers.user'])->findOrFail($id);

        // 2. Check of het een boodschappenrit is
        $isGrocery = $ride->is_grocery_only;

        // 3. Haal het actieve lidmaatschap op van de ingelogde user
        $membership = Membership::where('user_id', auth()->id())->first();

        // 4. Check of de ingelogde user de driver van de rit is 
        $isDriver = $membership && ($ride->driver_id === $membership->id);

        // 5. Check of de ingelogde user al een passagier is
        $passengerRecord = ($membership && $ride->passengers)
            ? $ride->passengers->firstWhere('id', $membership->id)
            : null;

        $currentUserStatus = $passengerRecord ? $passengerRecord->pivot->status : null;

        // 6. Verdeel de passagiers over twee lijsten op basis van hun status
        $approvedPassengers = $ride->passengers->where('pivot.status', PassengerStatus::APPROVED->value);
        $pendingRequests = $ride->passengers->where('pivot.status', PassengerStatus::PENDING->value);

        // 7. Geef alle variabelen door naar de view
        return view('rides.show', compact(
            'ride',
            'isDriver',
            'currentUserStatus',
            'approvedPassengers',
            'pendingRequests',
            'isGrocery'
        ));
    }


    /**
     * Toon het formulier om een nieuwe rit aan te maken.
     */
    public function create(Request $request)
    {
        // Check de url voor een query parameter
        if ($request->get('type') === 'boodschappen') {
            return view('rides.create-groceries');
        }

        // Anders tonen we het normale formulier
        return view('rides.create');
    }


    /**
     * Valideer de data en sla de rit op in de database.
     */
    public function store(Request $request)
    {
        // 1. Valideer de invoer van het formulier
        $validated = $request->validate([
            'destination_store'     => 'nullable|string|max:255',
            'destination_address'   => 'required|string|max:255',
            'destination_longitude' => 'required|numeric',
            'destination_latitude'  => 'required|numeric',
            'departure_address'     => 'nullable|string|max:255',
            'departure_longitude'   => 'nullable|numeric',
            'departure_latitude'    => 'nullable|numeric',
            'departure_time'        => 'required|date|after:now',
            'is_grocery_only'       => 'required|boolean',
            'max_passengers'        => 'required|integer|min:1',
        ]);

        // 2. Haal het actieve lidmaatschap van de ingelogde gebruiker op
        $membership = Membership::where('user_id', Auth::id())->first();

        if (!$membership) {
            return redirect()->back()->with('error', 'Je moet een actief lidmaatschap hebben om een rit aan te maken.');
        }

        // 3. Koppel de driver_id aan het membership ID en zet de status op open
        $validated['driver_id'] = $membership->id;
        $validated['status'] = 'open';

        // 4. Sla de rit op
        Ride::create($validated);

        // 5. Stuur de gebruiker terug met een succesmelding
        return redirect()->route('ritten.index')->with('success', 'Je rit is succesvol aangemeld!');
    }

    /**
     * Koppel de passagiers aan de ritten.
     */
    public function joinRide(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);

        // Haal het lidmaatschap op van de ingelogde gebruiker
        $membership = Membership::where('user_id', auth()->id())->first();

        if (!$membership) {
            return redirect()->back()->with('error', 'Je hebt een actief lidmaatschap nodig om mee te rijden.');
        }

        // 1. Check of de ingelogde gebruiker de driver zelf is
        if ($ride->driver_id === $membership->id) {
            return redirect()->back()->with('error', 'Je bent de bestuurder van deze rit!');
        }

        // 2. Check of deze passagier al een verzoek heeft gedaan
        $existingPassenger = $ride->passengers()->where('membership_id', $membership->id)->first();

        if ($existingPassenger) {
            $status = $existingPassenger->pivot->status;

            if ($status === PassengerStatus::APPROVED->value) {
                return redirect()->back()->with('error', 'Je reist al mee met deze rit!');
            }

            if ($status === PassengerStatus::PENDING->value) {
                return redirect()->back()->with('error', 'Je hebt al een verzoek ingediend.');
            }

            // Als het verzoek eerder was afgewezen, maken we er weer PENDING van
            if ($status === PassengerStatus::REJECTED->value) {
                $ride->passengers()->updateExistingPivot($membership->id, [
                    'status' => PassengerStatus::PENDING->value,
                    'delivery_address' => $request->input('delivery_address'),
                ]);

                return redirect()->back()->with('success', 'Je verzoek om mee te rijden is opnieuw verzonden!');
            }
        }

        // 3. Check of de rit vol is
        if (($ride->max_passengers - $ride->approvedPassengersCount()) <= 0) {
            return redirect()->back()->with('error', 'Helaas, deze rit is al volgeboekt.');
        }

        // 4. Eerste verzoek
        $ride->passengers()->attach($membership->id, [
            'status' => PassengerStatus::PENDING->value,
            'delivery_address' => $request->input('delivery_address'),
        ]);

        return redirect()->back()->with('success', 'Je verzoek om mee te rijden is verzonden!');
    }

    public function updatePassengerStatus(Request $request, Ride $ride, Membership $membership)
    {
        // Stap 1: Valideer de data
        $request->validate([
            'status' => ['required', Rule::enum(PassengerStatus::class)],
        ]);

        // Stap 2: Pas de status van de passagier aan naar goedgekeurd of afgewezen.
        $ride->passengers()->updateExistingPivot($membership->id, [
            'status' => $request->status,
        ]);

        // 3. Stuur de gebruiker terug met een succesmelding
        return back()->with('success', 'Status van passagier succesvol bijgewerkt!');
    }
}
