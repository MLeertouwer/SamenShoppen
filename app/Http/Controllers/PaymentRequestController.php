<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $amountInCents = (int) round($request->amount * 100);

        $response = Http::withHeaders([
            'API-Key' => config('services.tikkie.api_key'),
            'X-App-Token' => config('services.tikkie.app_token'),
            'Content-Type' => 'application/json',
        ])->post('https://api-sandbox.abnamro.com/v2/tikkie/paymentrequests', [
            'amountInCents' => $amountInCents,
            'description' => $request->description,
            'referenceId' => 'REQ-'.time(),
        ]);

        $paymentRequestUrl = $response->json('url');

        // Vang hier het unieke token op uit de Tikkie API response:
        $paymentRequestToken = $response->json('paymentRequestToken');

        // Sla het op in de database:
        PaymentRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
            'payment_request_url' => $paymentRequestUrl,
            'tikkie_payment_request_id' => $paymentRequestToken, // <--- Deze slaat het token op!
        ]);

        return redirect()->back()->with('success', 'Tikkie is succesvol verstuurd!');
    }

    public function create(Request $request)
    {
        $receiverId = $request->query('receiver_id');

        $members = User::where('id', '!=', auth()->id())
            ->whereHas('membership', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        return view('admin.member.paymentrequest', compact('members', 'receiverId'));
    }

    public function handleWebhook(Request $request)
    {
        // Tikkie stuurt gegevens mee, zoals de paymentRequestToken
        $token = $request->input('paymentRequestToken');

        // Zoek het betaalverzoek op basis van het opgeslagen ID/token in je database
        $paymentRequest = PaymentRequest::where('tikkie_payment_request_id', $token)->first();

        if ($paymentRequest) {
            // Update de status naar betaald
            $paymentRequest->update([
                'status' => 'paid',
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'not_found'], 404);
    }
}
