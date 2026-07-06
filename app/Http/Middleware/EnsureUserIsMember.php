<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMember
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user('member') || $request->user('member')->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}
