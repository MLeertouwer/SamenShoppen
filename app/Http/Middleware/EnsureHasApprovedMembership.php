<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasApprovedMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        // Is de gebruiker ingelogd én heeft hij een goedgekeurd lidmaatschap?
        if (! $request->user() || ! $request->user()->hasApprovedMembership()) {
            abort(403, 'Je moet een actief lidmaatschap hebben om deze pagina te bekijken.');
        }

        return $next($request);
    }
}
