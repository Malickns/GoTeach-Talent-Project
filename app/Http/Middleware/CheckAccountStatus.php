<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Ce middleware est désormais remplacé par CheckStatus qui gère la logique complète.
// Fichier conservé volontairement pour éviter les erreurs d'autoload si référencé, mais sans effet.
class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}