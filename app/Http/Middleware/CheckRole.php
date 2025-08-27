<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if ($user->role !== $role) {
            // Redirection vers le bon dashboard selon le rôle
            switch ($user->role) {
                case 'jeune':
                    return redirect()->route('jeunes.dashboard');
                case 'employeur':
                    return redirect()->route('pages.employeurs.dashboard');
                case 'admin_local':
                    return redirect()->route('pages.admins.local.dashboard');
                case 'admin_national':
                    return redirect()->route('pages.admins.national.dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
} 