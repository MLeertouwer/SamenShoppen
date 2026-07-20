<?php

namespace App\Http\Controllers;

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

        User::create($validated);

        return redirect()->route('requestform')->with('success', 'Accountgegevens aangevraagd, een admin zal deze goedkeuren');
    }
}
