<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
        ]);

        $user = User::create($validated);

        // membership aanmaken met membership model
        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'approved_by' => null,
            'paid_contribution' => false,
        ]);

        return redirect()->route('requestform')->with('success', 'Accountgegevens aangevraagd, een admin zal deze goedkeuren');
    }
}
