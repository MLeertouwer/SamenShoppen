<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $accountrequest = Membership::with('user')
            ->where('status', 'pending')
            ->get();

        return view('admin.dashboard', compact('accountrequest'));
    }

    public function showRequests()
    {
        $accountrequest = Membership::with('user')
            ->where('status', 'pending')
            ->get();

        return view('admin.aanvragen', compact('accountrequest'));
    }

    public function approveUser(User $user)
    {
        // 1. Het bijbehorende lidmaatschap ophalen en op 'active' zetten
        $membership = Membership::where('user_id', $user->id)->first();

        if ($membership) {
            $membership->status = 'active';
            $membership->approved_by = auth()->id(); // 💡 auth()->id() is de ingelogde admin!
            $membership->save();
        }

        // 2. Wachtwoord-reset mail sturen naar de goedgekeurde gebruiker
        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);

        return redirect()->back()->with('success', 'Gebruiker goedgekeurd en welkomstmail verstuurd.');
    }

    public function rejectUser(User $user)
    {
        $membership = Membership::where('user_id', $user->id)->first();

        if ($membership) {
            $membership->status = 'rejected';
            $membership->approved_by = auth()->id();
            $membership->save();
        }

        return redirect()->back()->with('success', 'Gebruiker afgekeurd.');
    }

    public function showApprovedUsers()
    {

        $accountrequest = Membership::with('user')->where('status', 'pending')->get();
        $processedRequests = Membership::with('user')->where('status', '!=', 'pending')->latest()->take(2)->get();

        return view('admin.dashboard', compact('accountrequest', 'processedRequests'));
    }

    public function showMembers()
    {

        $members = Membership::with('user')->where('status', '!=', 'pending')->get();

        return view('admin.ledenbeheren', compact('members'));
    }

    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();

        return redirect()->back()->with('success', 'Lid succesvol verwijderd.');
    }

    public function showMember($id)
    {
        $member = User::findOrFail($id);

        return view('admin.member.show', compact('member'));
    }

    public function showDashboardWithSuggestions(Request $request)
    {
        $accountrequest = Membership::with('user')->where('status', 'pending')->get();
        $processedRequests = Membership::with('user')->where('status', '!=', 'pending')->latest()->take(2)->get();

        // Start de query voor suggesties/klachten met de gekoppelde gebruiker
        $suggestionsQuery = Suggestion::with('user');

        // Check of er een filter is meegegeven via de URL (?type=klacht of ?type=suggestie)
        if ($request->filled('type') && in_array($request->type, ['klacht', 'suggestie'])) {
            $suggestionsQuery->where('type', $request->type);
        }

        $suggestions = $suggestionsQuery->latest()->get();

        return view('admin.dashboard', compact('accountrequest', 'processedRequests', 'suggestions'));
    }

    public function destroySuggestion($id)
    {
        $suggestion = Suggestion::findOrFail($id);
        $suggestion->delete();

        return redirect()->back()->with('success', 'Suggestie/klacht succesvol afgehandeld.');
    }
}
