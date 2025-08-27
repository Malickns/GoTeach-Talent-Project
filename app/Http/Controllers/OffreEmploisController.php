<?php

namespace App\Http\Controllers;

use App\Models\OffreEmplois;
use App\Models\Employeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OffreEmploisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupération des offres selon le rôle
        if ($user->isEmployeur()) {
            // L'employeur ne voit que ses offres
            $employeur = $user->employeur;
            if (!$employeur) {
                return redirect()->route('pages.employeurs.dashboard')
                    ->with('error', 'Profil employeur non trouvé.');
            }
            
            $offresEmplois = OffreEmplois::where('employeur_id', $employeur->employeur_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
        } elseif ($user->isJeune()) {
            // Les jeunes voient les offres non expirées
            $offresEmplois = OffreEmplois::visible()
                ->orderBy('offre_urgente', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
        } elseif ($user->isAdmin()) {
            // Les admins voient toutes les offres
            $offresEmplois = OffreEmplois::with(['employeur.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            abort(403, 'Accès non autorisé.');
        }

        return view('partials.offreEmplois.index', compact('offresEmplois'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Seuls les employeurs et admins peuvent créer des offres
        if (!$user->isEmployeur() && !$user->isAdmin()) {
            abort(403, 'Seuls les employeurs peuvent créer des offres d\'emploi.');
        }

        return view('partials.offreEmplois.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérification des permissions
        if (!$user->isEmployeur() && !$user->isAdmin()) {
            abort(403, 'Seuls les employeurs peuvent créer des offres d\'emploi.');
        }

        // Validation des données
        $validatedData = $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'required|string|min:20',
            'missions_principales' => 'nullable|string',
            'type_contrat' => 'required|in:cdi,cdd,stage,formation,freelance,autre',
            'duree_contrat_mois' => 'nullable|integer|min:1|max:60',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date|after:date_debut_contrat',
            'grade' => 'nullable|string|max:50',
            'ville_travail' => 'required|string|max:100',
            'competences_requises' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
            'offre_urgente' => 'boolean',
        ]);

        // Traitement des compétences, langues et logiciels
        $validatedData = $this->processArrayFields($validatedData);

        // Récupération de l'employeur
        $employeur = $user->employeur;
        if (!$employeur) {
            return redirect()->back()
                ->with('error', 'Profil employeur non trouvé.')
                ->withInput();
        }

        // Ajout de l'employeur_id
        $validatedData['employeur_id'] = $employeur->employeur_id;

        try {
            $offreEmplois = OffreEmplois::create($validatedData);

            return redirect()->route('offres-emplois.index')
                ->with('success', 'Offre d\'emploi créée avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'offre d\'emploi.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OffreEmplois $offreEmplois)
    {
        $user = Auth::user();
        
        // Vérification des permissions
        if ($user->isEmployeur()) {
            $employeur = $user->employeur;
            if ($offreEmplois->employeur_id !== $employeur->employeur_id) {
                abort(403, 'Vous ne pouvez voir que vos propres offres d\'emploi.');
            }
        } elseif ($user->isJeune()) {
            // Les jeunes ne peuvent voir que les offres non expirées
            if ($offreEmplois->date_expiration && $offreEmplois->date_expiration->isPast()) {
                abort(404, 'Offre d\'emploi non trouvée.');
            }
        }

        // Incrémenter le nombre de vues
        $offreEmplois->increment('nombre_vues');

        return view('partials.offreEmplois.show', compact('offreEmplois'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OffreEmplois $offreEmplois)
    {
        $user = Auth::user();
        
        // Vérification des permissions
        if ($user->isEmployeur()) {
            $employeur = $user->employeur;
            if ($offreEmplois->employeur_id !== $employeur->employeur_id) {
                abort(403, 'Vous ne pouvez modifier que vos propres offres d\'emploi.');
            }
        } elseif (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('partials.offreEmplois.edit', compact('offreEmplois'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OffreEmplois $offreEmplois)
    {
        $user = Auth::user();
        
        // Vérification des permissions
        if ($user->isEmployeur()) {
            $employeur = $user->employeur;
            if ($offreEmplois->employeur_id !== $employeur->employeur_id) {
                abort(403, 'Vous ne pouvez modifier que vos propres offres d\'emploi.');
            }
        } elseif (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation des données
        $validatedData = $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'required|string|min:20',
            'missions_principales' => 'nullable|string',
            'type_contrat' => 'required|in:cdi,cdd,stage,formation,freelance,autre',
            'duree_contrat_mois' => 'nullable|integer|min:1|max:60',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date|after:date_debut_contrat',
            'grade' => 'nullable|string|max:50',
            'ville_travail' => 'required|string|max:100',
            'competences_requises' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
            'offre_urgente' => 'boolean',
        ]);

        // Traitement des compétences, langues et logiciels
        $validatedData = $this->processArrayFields($validatedData);

        try {
            $offreEmplois->update($validatedData);

            return redirect()->route('offres-emplois.show', $offreEmplois)
                ->with('success', 'Offre d\'emploi mise à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'offre d\'emploi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OffreEmplois $offreEmplois)
    {
        $user = Auth::user();
        
        // Vérification des permissions
        if ($user->isEmployeur()) {
            $employeur = $user->employeur;
            if ($offreEmplois->employeur_id !== $employeur->employeur_id) {
                abort(403, 'Vous ne pouvez supprimer que vos propres offres d\'emploi.');
            }
        } elseif (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        try {
            // Vérifier s'il y a des postulations
            if ($offreEmplois->postulations()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette offre car elle a des candidatures.');
            }

            $offreEmplois->delete();

            return redirect()->route('offres-emplois.index')
                ->with('success', 'Offre d\'emploi supprimée avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'offre d\'emploi.');
        }
    }

    /**
     * Traite les champs de type tableau (compétences, langues, logiciels)
     */
    private function processArrayFields($data)
    {
        $arrayFields = ['competences_requises'];
        
        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                // Convertir la chaîne en tableau
                $items = array_map('trim', explode(',', $data[$field]));
                $items = array_filter($items); // Supprimer les éléments vides
                $data[$field] = !empty($items) ? $items : null;
            }
        }
        
        return $data;
    }
}
