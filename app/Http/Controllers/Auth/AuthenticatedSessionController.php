<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

  

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirection basée sur le rôle et le statut de l'utilisateur
        $user = Auth::user();
        
        if ($user->statut === 'actif') {
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
                    return redirect()->intended('dashboard');
            }
        } else {
            // Utilisateur inactif, suspendu ou supprimé
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('error', 'Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
