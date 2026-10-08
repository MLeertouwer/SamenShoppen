<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function checkout()
    {
        // 1. Geef de Stripe Secret Key op uit je .env
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = 10.00; // Bedrag in euro's

        // 2. Maak een Stripe Checkout Session aan
        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Onderhoudsbijdrage (Halfjaarlijks)',
                    ],
                    'unit_amount' => $amount * 100, // Stripe rekent in centen (€ 10,00 = 1000)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        // 3. Sla de betaling alvast op als 'pending' in je database
        Payment::create([
            'user_id' => auth()->id(),
            'stripe_session_id' => $session->id,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        // 4. Stuur de gebruiker door naar de Stripe betaalpagina
        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            // Zoek de betaling op en zet de status op 'paid'
            $payment = Payment::where('stripe_session_id', $sessionId)->first();

            if ($payment && $payment->status !== 'paid') {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }

        return redirect()->route('profiel')->with('success', 'Bedankt! Je onderhoudsbijdrage is succesvol voldaan.');
    }

    public function cancel()
    {
        return redirect()->route('profiel')->with('error', 'De betaling is geannuleerd.');
    }
}
