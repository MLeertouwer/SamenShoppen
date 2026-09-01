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
        // Pakt de huidige maand/jaar
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Haal de gekozen datum op uit de URL
        $selectedDate = $request->get('selected_date');

        // Haal de ritten op van deze maand
        $rides = Ride::whereMonth('departure_time', $month)
            ->whereYear('departure_time', $year)
            ->get();

        // Groepeer de ritten op datum
        $ridesPerDay = $rides->groupBy(function ($ride) {
            return Carbon::parse($ride->departure_time)->format('Y-m-d');
        });

        // Stuur alles naar de view
        return view('rides.index', compact('ridesPerDay', 'month', 'year', 'selectedDate'));
    }


    public function show($id)
    {
        // Zoek de rit op basis van het ID
        $ride = Ride::with(['passengers.user'])->findOrFail($id);

        // Stuur de rit door naar de view 'rides.show'
        return view('rides.show', compact('ride'));
    }


    /**
     * Toon het formulier om een nieuwe rit aan te maken.
     */
    public function create()
    {
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
            'departure_address'     => 'required|string|max:255',
            'departure_longitude'   => 'required|numeric',
            'departure_latitude'    => 'required|numeric',
            'departure_time'        => 'required|date|after:now',
            'max_passengers'        => 'required|integer|min:1',
        ]);

        // // 2. Gebruik de relaties om het membership van de ingelogde user op te halen
        // $userMembership = Auth::user()->membership;

        // // Check of de gebruiker een membership heeft
        // if (!$userMembership) {
        //     return redirect()->back()->with('error', 'Je moet een actief lidmaatschap hebben om een rit te melden.');
        // }

        // 3. Voeg de driver_id toe van de Membership en een standaard status
        // $validated['driver_id'] = $userMembership->id;
        $validated['driver_id'] = Auth::id(); // TODO: terugzetten naar $userMembership->id zodra membership-functionaliteit bestaat
        $validated['status'] = 'open';

        // 4. Sla de rit op met de ingevulde data
        Ride::create($validated);

        // 5. Stuur de gebruiker terug met een succesmelding
        return redirect()->route('ritten.index')->with('success', 'Je rit is succesvol aangemeld!');
    }

    /**
     * Koppel de passagiers aan de ritten.
     */
    public function joinRide($id)
    {
        // 1. Haal de rit op en tel direct het aantal passagiers
        $ride = Ride::withCount('passengers')->findOrFail($id);
        $membership = Membership::where('user_id', auth()->id())->first();

        // 1. Check eerst of de gebruiker al is aangemeld 👥
        if ($ride->passengers->contains($membership->id)) {
            return redirect()->back()->with('error', 'Je bent al aangemeld voor deze rit!');
        }

        // 2. Check daarna pas of de rit vol is 🚗
        if (($ride->max_passengers - $ride->passengers->count()) <= 0) {
            return redirect()->back()->with('error', 'Helaas, deze rit is al volgeboekt.');
        }

        // 3. Als beide checks goed zijn, melden we de passagier aan 🎉
        $ride->passengers()->attach($membership->id, [
            'status' => PassengerStatus::PENDING->value,
        ]);

        return redirect()->back()->with('success', 'Je verzoek om mee te rijden is verzonden!');
    }

    public function updatePassengerStatus(Request $request)
    {
        // Stap 1: Valideer de data
        $request->validate([
            'status' => ['required', Rule::enum(PassengerStatus::class)],
        ]);

        // Stap 2: Pas de status van de passagier aan naar goedgekeurd of afgewezen.
        if ()
    }
}
