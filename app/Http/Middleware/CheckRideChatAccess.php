<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRideChatAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Haal het ride object op
        $ride = $request->route('ride');

        // Als de ride bestaat,check of de ingelogde user de driver of een passagier is, zo niet, return 403.
        if ($ride) {
            $isDriver = optional($ride->driver)->user_id === $request->user()->id;
            $isApprovedPassenger = $ride->approvedPassengers()->where('user_id', $request->user()->id)->exists();

            if (! $isDriver && ! $isApprovedPassenger) {
                abort(403, 'Je hebt geen toegang tot deze chat.');
            }
        }

        return $next($request);
    }
}
