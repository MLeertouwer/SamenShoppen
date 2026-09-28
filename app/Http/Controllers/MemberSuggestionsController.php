<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberSuggestionsController extends Controller
{
    public function create()
    {
        return view('admin.member.contactpage-members');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'bericht' => 'required|string|max:1000',
        ]);

        auth()->User()->suggestions()->create([
            'type' => $request->type,
            'bericht' => $request->bericht,
        ]);

        return redirect()->route('suggesties.create')->with('success', 'Bedankt voor je suggestie! We nemen zo snel mogelijk contact met je op.');

    }
}
