<?php

namespace Partymeister\Competitions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ExportTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $configuredToken = config('partymeister-competitions.export_token');

        if (empty($configuredToken)) {
            abort(503, 'Export token not configured');
        }

        $providedToken = $request->header('X-Export-Token') ?? $request->query('token');

        if (empty($providedToken) || ! hash_equals($configuredToken, $providedToken)) {
            abort(401, 'Invalid export token');
        }

        return $next($request);
    }
}
