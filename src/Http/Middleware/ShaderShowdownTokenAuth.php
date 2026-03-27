<?php

namespace Partymeister\Competitions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShaderShowdownTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $configuredToken = config('partymeister-competitions.shader_showdown_token');

        if (empty($configuredToken)) {
            abort(503, 'Shader Showdown token not configured');
        }

        $providedToken = $request->header('X-Shader-Token');

        if (empty($providedToken) || ! hash_equals($configuredToken, $providedToken)) {
            abort(401, 'Invalid Shader Showdown token');
        }

        return $next($request);
    }
}
