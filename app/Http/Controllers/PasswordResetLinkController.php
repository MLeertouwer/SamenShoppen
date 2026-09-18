<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{

public function create()
{
    return view('admin.member.forgot-password');
}

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Laravel controleert of het e-mailadres bestaat,
        // maakt een token aan en verstuurt de notification mail.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
