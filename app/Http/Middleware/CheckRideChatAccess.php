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

        if ($ride) {
            // Controleer of de gebruiker een beheerder is 
            $isAdmin = $isAdmin = $request->user()->hasRole('beheerder') ?? false;

            $isDriver = optional($ride->driver)->user_id === $request->user()->id;
            $isApprovedPassenger = $ride->approvedPassengers()->where('user_id', $request->user()->id)->exists();

            // Als de gebruiker géén beheerder, driver of goedgekeurde passagier is, blokkeer dan de toegang
            if (! $isAdmin && ! $isDriver && ! $isApprovedPassenger) {
                abort(403, 'Je hebt geen toegang tot deze chat.');
            }
        }

        return $next($request);
    }
}
