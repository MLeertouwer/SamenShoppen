<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;

class MemberHomeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Verzoeken die JIJ hebt verstuurd
        $sentRequests = PaymentRequest::with('receiver')
            ->where('sender_id', $userId)
            ->latest()
            ->get();

        // Verzoeken die AAN JOU zijn gestuurd
        $receivedRequests = PaymentRequest::with('sender')
            ->where('receiver_id', $userId)
            ->latest()
            ->get();

        return view('home', compact('sentRequests', 'receivedRequests'));
    }
}
