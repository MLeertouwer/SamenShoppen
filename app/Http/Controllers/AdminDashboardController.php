<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $accountrequest = User::where('status', 'wacht op goedkeuring')->latest()->take(5)->get();

        return view('admin.dashboard', compact('accountrequest'));
    }

    public function showRequests()
    {
        $accountrequest = User::where('status', 'wacht op goedkeuring')->get();

        return view('admin.aanvragen', compact('accountrequest'));
    }

    public function approveUser(User $user)
    {
        $user->status = 'goedgekeurd';
        $user->save();

        $token = Password::createToken($user);

        $user->sendPasswordResetNotification($token);

        return redirect()->back()->with('success', 'Gebruiker goedgekeurd en welkomstmail verstuurd.');
    }

    public function rejectUser(User $user)
    {
        $user->status = 'afgekeurd';
        $user->save();

        return redirect()->back()->with('success', 'Gebruiker afgekeurd.');
    }

    public function showApprovedUsers()
    {

        $accountrequest = User::where('status', 'wacht op goedkeuring')->get();

        $processedRequests = User::where('status', '!=', 'wacht op goedkeuring')->get();

        return view('admin.dashboard', compact('accountrequest', 'processedRequests'));
    }
}
