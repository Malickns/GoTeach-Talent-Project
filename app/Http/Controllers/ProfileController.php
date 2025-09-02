<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $typesContrat = config('offres.types_contrat');
        $secteurs = \App\Models\Employeur::query()
            ->whereNotNull('secteur_activite')
            ->distinct()
            ->orderBy('secteur_activite')
            ->pluck('secteur_activite')
            ->toArray();

        return view('profile.edit', [
            'user' => $request->user(),
            'typesContrat' => $typesContrat,
            'secteurs' => $secteurs,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Mettre à jour User (name/email) côté Breeze par défaut
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Mettre à jour les préférences du profil Jeune si présent
        $jeune = $user->jeune;
        if ($jeune) {
            if (array_key_exists('preferences_emploi', $validated)) {
                $jeune->preferences_emploi = $validated['preferences_emploi'];
            }
            if (array_key_exists('types_contrat_preferes', $validated)) {
                $jeune->types_contrat_preferes = $validated['types_contrat_preferes'];
            }
            if (array_key_exists('secteurs_preferes', $validated)) {
                $jeune->secteurs_preferes = $validated['secteurs_preferes'];
            }
            $jeune->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
