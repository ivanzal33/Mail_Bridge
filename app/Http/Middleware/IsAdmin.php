<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user instanceof User && $user->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Access denied');
    }
}

