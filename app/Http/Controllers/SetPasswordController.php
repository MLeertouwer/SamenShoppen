<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class SetPasswordController extends Controller
{
    public function create($token)
    {
        return view('wachtwoordaanmaken', ['token' => $token]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

         if ($status === Password::PASSWORD_RESET) {
             return redirect()->route('login')->with('status', __($status));
            }

            return back()->withErrors(['email' => __($status)]);
    }
}
