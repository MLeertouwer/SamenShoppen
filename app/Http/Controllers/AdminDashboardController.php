<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\User;
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
        // 1. User status bijwerken
        $user->status = 'goedgekeurd';
        $user->save();

        // 2. Het bijbehorende lidmaatschap ophalen en op 'active' zetten
        $membership = Membership::where('user_id', $user->id)->first();
        if ($membership) {
            $membership->status = 'active';
            $membership->approved_by = auth()->id();
            $membership->save();
        }
        $token = Password::createToken($user);

        $user->sendPasswordResetNotification($token);

        return redirect()->back()->with('success', 'Gebruiker goedgekeurd en welkomstmail verstuurd.');
    }

    public function rejectUser(User $user)
    {
        $user->status = 'afgekeurd';
        $user->save();

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
}
