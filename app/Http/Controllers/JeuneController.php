<?php

namespace App\Http\Controllers;

use App\Models\Jeune;
use App\Models\OffreEmplois;
use App\Models\Postulation;
use App\Models\Document;
use App\Models\CategorieJeune;
use App\Models\OffreVue; // Added this import
use App\Services\StatistiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Services\RecommendationService;

class JeuneController extends Controller
{
    /**
     * Liste des jeunes pour l'admin local (seulement ceux de son programme)
     */
    public function indexAdminLocal(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403);
        }

        $admin = $user->admin;
        if (!$admin || $admin->niveau_admin !== 'local') {
            abort(403);
        }

        $query = Jeune::with(['user', 'programme'])
            ->where('programme_id', $admin->programme_id);

        // Recherche globale
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('prenom', 'like', "%{$search}%")
                       ->orWhere('nom', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('ville', 'like', "%{$search}%")
                ->orWhere('region', 'like', "%{$search}%")
                ->orWhere('niveau_etude', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('genre')) {
            $query->where('genre', $request->string('genre'));
        }
        if ($request->filled('niveau_etude')) {
            $query->where('niveau_etude', $request->string('niveau_etude'));
        }
        if ($request->filled('ville')) {
            $query->where('ville', $request->string('ville'));
        }

        // Ordre: toujours les comptes non actifs en haut, puis récents d'abord
        $query->orderByRaw("(CASE WHEN (SELECT statut FROM users WHERE users.user_id = jeunes.user_id) <> 'actif' THEN 0 ELSE 1 END) ASC")
              ->orderByRaw("(SELECT created_at FROM users WHERE users.user_id = jeunes.user_id) DESC");

        $jeunes = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $villes = Jeune::where('programme_id', $admin->programme_id)->distinct()->pluck('ville')->filter()->values();
        $niveaux = Jeune::where('programme_id', $admin->programme_id)->distinct()->pluck('niveau_etude')->filter()->values();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('pages.admins.local.jeunes.partials.table', compact('jeunes', 'admin'))->render(),
            ]);
        }

        return view('pages.admins.local.jeunes.index', compact('jeunes', 'villes', 'niveaux', 'admin'));
    }

    /**
     * Rend uniquement la table (AJAX) pour tri/pagination en temps réel
     */
    public function tableAdminLocal(Request $request)
    {
        return $this->indexAdminLocal($request);
    }

    /**
     * Liste des jeunes pour l'admin national (tous les programmes)
     */
    public function indexAdminNational(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || !$user->isAdminNational()) {
            abort(403);
        }

        $query = Jeune::with(['user', 'programme', 'documents']);

        // Recherche globale
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('prenom', 'like', "%{$search}%")
                       ->orWhere('nom', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('ville', 'like', "%{$search}%")
                ->orWhere('region', 'like', "%{$search}%")
                ->orWhere('niveau_etude', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->integer('programme_id'));
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->integer('categorie_id'));
        }
        if ($request->filled('genre')) {
            $query->where('genre', $request->string('genre'));
        }
        if ($request->filled('ville')) {
            $query->where('ville', $request->string('ville'));
        }
        if ($request->filled('niveau_etude')) {
            $query->where('niveau_etude', $request->string('niveau_etude'));
        }
        if ($request->filled('disponibilite')) {
            $query->where('disponibilite', 'like', "%".$request->string('disponibilite')."%" );
        }

        // Ordre: non actifs en haut puis récents
        $query->orderByRaw("(CASE WHEN (SELECT statut FROM users WHERE users.user_id = jeunes.user_id) <> 'actif' THEN 0 ELSE 1 END) ASC")
              ->orderByRaw("(SELECT created_at FROM users WHERE users.user_id = jeunes.user_id) DESC");

        $jeunes = $query->paginate(12)->withQueryString();

        // Données pour filtres
        $programmes = \App\Models\Programme::orderBy('nom')->get();
        $categories = CategorieJeune::when($request->filled('programme_id'), function ($q) use ($request) {
                $q->where('programme_id', $request->integer('programme_id'));
            })
            ->orderBy('nom')
            ->get();
        $villes = Jeune::distinct()->pluck('ville')->filter()->values();
        $niveaux = Jeune::distinct()->pluck('niveau_etude')->filter()->values();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('pages.admins.national.jeunes.partials.table', compact('jeunes'))->render(),
            ]);
        }

        return view('pages.admins.national.jeunes.index', compact('jeunes', 'programmes', 'categories', 'villes', 'niveaux'));
    }

    /**
     * Rend uniquement la table (AJAX) pour tri/pagination en temps réel - admin national
     */
    public function tableAdminNational(Request $request)
    {
        return $this->indexAdminNational($request);
    }

    /**
     * Détails d'un jeune (admin national)
     */
    public function showAdminNational($jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || !$user->isAdminNational()) {
            abort(403);
        }

        $jeune = Jeune::with(['user','programme','documents','postulations.offreEmplois.employeur.user'])
            ->findOrFail($jeune);

        // Documents principaux
        $cv = $jeune->getCv();
        $lettreMotivation = $jeune->getLettreMotivation();

        return view('pages.admins.national.jeunes.show', compact('jeune','cv','lettreMotivation'));
    }

    /**
     * Télécharger un document (admin national)
     */
    public function telechargerDocumentAdminNational(Document $document)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || !$user->isAdminNational()) {
            abort(403);
        }

        return $document->telecharger() ?? back()->with('error', 'Fichier introuvable.');
    }

    /**
     * Afficher le formulaire de création d'un jeune (admin local)
     */
    public function createAdminLocal(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        return view('pages.admins.local.jeunes.create', compact('admin'));
    }

    /**
     * Enregistrer un jeune (admin local)
     */
    public function storeAdminLocal(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;

        // Validation stricte
        $validated = $request->validate([
            'prenom' => ['required','string','max:50'],
            'nom' => ['required','string','max:50'],
            'email' => ['required','email','max:100','unique:users,email'],
            'telephone' => ['nullable','string','max:20','regex:/^[0-9+\-\s\(\)]+$/'],
            'date_naissance' => ['required','date','before:-16 years'],
            'genre' => ['required', Rule::in(['homme','femme'])],
            'lieu_naissance' => ['required','string','max:100'],
            'nationalite' => ['required','string','max:50'],
            'numero_cni' => ['nullable','string','max:50','unique:jeunes,numero_cni'],
            'adresse' => ['required','string','max:255'],
            'ville' => ['required','string','max:100'],
            'region' => ['required','string','max:100'],
            'pays' => ['required','string','max:50'],
            'niveau_etude' => ['required','string','max:50'],
            'dernier_diplome' => ['nullable','string','max:100'],
            'etablissement' => ['nullable','string','max:100'],
            'annee_obtention' => ['nullable','integer','min:1950','max:'.(date('Y')+1)],
        ]);

        // Création atomique
        \DB::transaction(function () use ($validated, $admin, $request) {
            $nouvelUser = \App\Models\User::create([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'password' => bcrypt(Str::random(12)),
                'telephone' => $request->input('telephone'),
                'role' => 'jeune',
                'statut' => 'actif',
            ]);

            Jeune::create([
                'user_id' => $nouvelUser->user_id,
                'date_naissance' => $validated['date_naissance'],
                'genre' => $validated['genre'],
                'lieu_naissance' => $validated['lieu_naissance'],
                'nationalite' => $validated['nationalite'],
                'numero_cni' => $request->input('numero_cni'),
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'region' => $validated['region'],
                'pays' => $validated['pays'],
                'niveau_etude' => $validated['niveau_etude'],
                'dernier_diplome' => $request->input('dernier_diplome'),
                'etablissement' => $request->input('etablissement'),
                'annee_obtention' => $request->input('annee_obtention'),
                'programme_id' => $admin->programme_id,
            ]);
        });

        return redirect()->route('admin.local.jeunes.index')
            ->with('success', 'Jeune créé avec succès.');
    }

    /**
     * Détails d'un jeune (admin local)
     */
    public function showAdminLocal($jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        $jeune = Jeune::with(['user','programme'])->findOrFail($jeune);
        if ($jeune->programme_id !== $admin->programme_id) {
            abort(403);
        }
        return view('pages.admins.local.jeunes.show', compact('jeune','admin'));
    }

    /**
     * Formulaire d'édition d'un jeune (admin local)
     */
    public function editAdminLocal($jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        $jeune = Jeune::with(['user'])->findOrFail($jeune);
        if ($jeune->programme_id !== $admin->programme_id) {
            abort(403);
        }
        return view('pages.admins.local.jeunes.edit', compact('jeune','admin'));
    }

    /**
     * Mise à jour d'un jeune (admin local)
     */
    public function updateAdminLocal(Request $request, $jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        $jeune = Jeune::with(['user'])->findOrFail($jeune);
        if ($jeune->programme_id !== $admin->programme_id) {
            abort(403);
        }

        $validated = $request->validate([
            'prenom' => ['required','string','max:50'],
            'nom' => ['required','string','max:50'],
            'email' => ['required','email','max:100', Rule::unique('users','email')->ignore($jeune->user_id, 'user_id')],
            'telephone' => ['nullable','string','max:20','regex:/^[0-9+\-\s\(\)]+$/'],
            'date_naissance' => ['required','date','before:-16 years'],
            'genre' => ['required', Rule::in(['homme','femme'])],
            'lieu_naissance' => ['required','string','max:100'],
            'nationalite' => ['required','string','max:50'],
            'numero_cni' => ['nullable','string','max:50', Rule::unique('jeunes','numero_cni')->ignore($jeune->jeune_id, 'jeune_id')],
            'adresse' => ['required','string','max:255'],
            'ville' => ['required','string','max:100'],
            'region' => ['required','string','max:100'],
            'pays' => ['required','string','max:50'],
            'niveau_etude' => ['required','string','max:50'],
            'dernier_diplome' => ['nullable','string','max:100'],
            'etablissement' => ['nullable','string','max:100'],
            'annee_obtention' => ['nullable','integer','min:1950','max:'.(date('Y')+1)],
        ]);

        \DB::transaction(function () use ($validated, $jeune, $request) {
            // Update user
            $jeune->user->update([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'telephone' => $request->input('telephone'),
            ]);

            // Update jeune
            $jeune->update([
                'date_naissance' => $validated['date_naissance'],
                'genre' => $validated['genre'],
                'lieu_naissance' => $validated['lieu_naissance'],
                'nationalite' => $validated['nationalite'],
                'numero_cni' => $request->input('numero_cni'),
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'region' => $validated['region'],
                'pays' => $validated['pays'],
                'niveau_etude' => $validated['niveau_etude'],
                'dernier_diplome' => $request->input('dernier_diplome'),
                'etablissement' => $request->input('etablissement'),
                'annee_obtention' => $request->input('annee_obtention'),
            ]);
        });

        return redirect()->route('admin.local.jeunes.show', $jeune->jeune_id)
            ->with('success', 'Jeune mis à jour avec succès.');
    }

    /**
     * Suppression d'un jeune (admin local)
     */
    public function destroyAdminLocal($jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        $jeune = Jeune::with(['user','postulations'])->findOrFail($jeune);
        if ($jeune->programme_id !== $admin->programme_id) {
            abort(403);
        }

        // Interdire si des candidatures actives existent ? On supprime logique selon besoins
        if ($jeune->postulations()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce jeune car des candidatures existent.');
        }

        \DB::transaction(function () use ($jeune) {
            $user = $jeune->user;
            $jeune->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.local.jeunes.index')
            ->with('success', 'Jeune supprimé avec succès.');
    }

    /**
     * Mettre à jour le statut du compte utilisateur lié au jeune (valider/bloquer)
     */
    public function updateStatusAdminLocal(Request $request, $jeune)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || $user->admin?->niveau_admin !== 'local') {
            abort(403);
        }
        $admin = $user->admin;
        $jeune = Jeune::with('user')->findOrFail($jeune);
        if ($jeune->programme_id !== $admin->programme_id) {
            abort(403);
        }

        $validated = $request->validate([
            'statut' => ['required', Rule::in(['actif','inactif','suspendu','supprime'])],
        ]);

        $jeune->user->update(['statut' => $validated['statut']]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
        ]);
    }
    /**
     * Afficher le dashboard du jeune
     */
    public function dashboard()
    {
        $jeune = Jeune::where('user_id', Auth::id())->with('user')->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        // Recommandations paginées basées sur profil/compétences/préférences
        $offresRecommandees = RecommendationService::recommandationsPourJeune($jeune, 12);
        
        // Récupérer les offres récentes (non expirées)
        $offresRecentes = OffreEmplois::visible()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Récupérer les candidatures du jeune
        $candidatures = $jeune->postulations()
            ->with(['offreEmplois.employeur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques dynamiques via le service
        $statistiques = StatistiqueService::getStatistiquesJeune($jeune->jeune_id);

        return view('pages.jeunes.dashboard', compact(
            'jeune',
            'offresRecommandees',
            'offresRecentes',
            'candidatures',
            'statistiques'
        ));
    }

    /**
     * Afficher toutes les offres d'emploi
     */
    public function offres(Request $request)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        $query = OffreEmplois::visible()->with(['employeur']);

        // Filtres
        if ($request->filled('ville')) {
            $query->where('ville', $request->ville);
        }

        if ($request->filled('type_contrat')) {
            $query->where('type_contrat', $request->type_contrat);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ;
            });
        }

        $offres = $query->orderBy('created_at', 'desc')->paginate(12);

        // Récupérer les filtres disponibles
        $villes = OffreEmplois::distinct()->pluck('ville_travail')->filter();
        $typesContrat = OffreEmplois::distinct()->pluck('type_contrat')->filter();

        return view('pages.jeunes.offres', compact(
            'offres',
            'villes',
            'typesContrat',
            'jeune'
        ));
    }

    /**
     * Afficher une offre d'emploi spécifique
     */
    public function showOffre(OffreEmplois $offre)
    {
        try {
            $jeune = Jeune::where('user_id', Auth::id())->first();
            
            if (!$jeune) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Veuillez compléter votre profil jeune pour accéder aux offres d\'emploi.');
            }

            // Vérifier si l'offre est visible
            if (!$offre->isVisible()) {
                return redirect()->route('jeunes.offres')
                    ->with('error', 'Cette offre d\'emploi n\'est plus disponible ou a expiré.');
            }

            // Enregistrer la vue de l'offre avec le service
            try {
                \App\Services\OffreVueService::enregistrerVue($offre->offre_id, $jeune->jeune_id);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'enregistrement de la vue d\'offre', [
                    'offre_id' => $offre->offre_id,
                    'jeune_id' => $jeune->jeune_id,
                    'error' => $e->getMessage()
                ]);
                // On continue même si l'enregistrement de la vue échoue
            }

            // Vérifier si le jeune a déjà postulé
            $aPostule = $jeune->hasPostule($offre->offre_id);

            // Récupérer les documents du jeune
            $cv = $jeune->getCv();
            $lettreMotivation = $jeune->getLettreMotivation();

            // Récupérer l'employeur avec eager loading pour éviter les requêtes N+1
            $employeur = $offre->employeur()->with('user')->first();

            // Récupérer les statistiques de l'offre avec cache et gestion d'erreur
            try {
                $statistiquesOffre = \App\Services\OffreVueService::getStatistiquesOffre($offre->offre_id);
                
                // Valider et nettoyer les statistiques
                $statistiquesOffre = \App\Helpers\StatistiquesHelper::validerStatistiques($statistiquesOffre);
                
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la récupération des statistiques de l\'offre', [
                    'offre_id' => $offre->offre_id,
                    'error' => $e->getMessage()
                ]);
                
                // Utiliser le helper pour obtenir des statistiques par défaut
                $statistiquesOffre = \App\Helpers\StatistiquesHelper::getStatistiquesErreur();
            }

            // Récupérer les offres similaires pour les recommandations
            $offresSimilaires = OffreEmplois::visible()
                ->where('offre_id', '!=', $offre->offre_id)
                ->where('ville_travail', $offre->ville_travail)
                ->where('type_contrat', $offre->type_contrat)
                ->limit(3)
                ->get();

            return view('pages.jeunes.show-offre', compact(
                'offre',
                'jeune',
                'employeur',
                'aPostule',
                'cv',
                'lettreMotivation',
                'statistiquesOffre',
                'offresSimilaires'
            ));

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'affichage de l\'offre', [
                'offre_id' => $offre->offre_id ?? 'N/A',
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('jeunes.offres')
                ->with('error', 'Une erreur est survenue lors du chargement de l\'offre. Veuillez réessayer.');
        }
    }

    /**
     * Afficher le formulaire de candidature
     */
    public function candidature(OffreEmplois $offre)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        // Vérifier si le jeune a déjà postulé
        if ($jeune->hasPostule($offre->offre_id)) {
            return redirect()->route('jeunes.offres.show', $offre)
                ->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Récupérer les documents du jeune
        $cv = $jeune->getCv();
        $lettreMotivation = $jeune->getLettreMotivation();

        return view('pages.jeunes.candidature', compact(
            'offre',
            'jeune',
            'cv',
            'lettreMotivation'
        ));
    }

    /**
     * Soumettre une candidature
     */
    public function postuler(Request $request, OffreEmplois $offre)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        // Vérifier si le jeune a déjà postulé
        if ($jeune->hasPostule($offre->offre_id)) {
            return redirect()->route('jeunes.offres.show', $offre)
                ->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Vérifier si l'offre est toujours active
        if (!$offre->visible()) {
            return redirect()->route('jeunes.offres.show', $offre)
                ->with('error', 'Cette offre n\'est plus disponible.');
        }

        // Récupérer les documents existants
        $cv = $jeune->getCv();
        $lettreMotivation = $jeune->getLettreMotivation();

        // Déterminer quels documents sont nécessaires
        $cvNecessaire = !$cv || !$cv->fichierExiste();
        $lmNecessaire = !$lettreMotivation || !$lettreMotivation->fichierExiste();

        // Validation conditionnelle
        $rules = [
            'message_employeur' => 'nullable|string|max:1000',
        ];

        if ($cvNecessaire) {
            $rules['cv'] = 'required|file|mimes:pdf,doc,docx|max:2048';
        }

        if ($lmNecessaire) {
            $rules['lettre_motivation'] = 'required|file|mimes:pdf,doc,docx|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            \DB::transaction(function () use ($request, $jeune, $offre, $cv, $lettreMotivation, $cvNecessaire, $lmNecessaire) {
                $cvDocument = $cv;
                $lmDocument = $lettreMotivation;

                // Upload du CV si nécessaire
                if ($cvNecessaire) {
                    $cvFile = $request->file('cv');
                    $cvPath = $cvFile->store('documents/cv', 'public');

                    $cvDocument = Document::updateOrCreate(
                        ['jeune_id' => $jeune->jeune_id, 'type' => 'cv'],
                        [
                            'nom_original' => $cvFile->getClientOriginalName(),
                            'chemin_fichier' => $cvPath,
                            'date_upload' => now(),
                        ]
                    );
                }

                // Upload de la lettre de motivation si nécessaire
                if ($lmNecessaire) {
                    $lmFile = $request->file('lettre_motivation');
                    $lmPath = $lmFile->store('documents/lettres_motivation', 'public');

                    $lmDocument = Document::updateOrCreate(
                        ['jeune_id' => $jeune->jeune_id, 'type' => 'lettre_motivation'],
                        [
                            'nom_original' => $lmFile->getClientOriginalName(),
                            'chemin_fichier' => $lmPath,
                            'date_upload' => now(),
                        ]
                    );
                }

                // Créer la postulation
                Postulation::create([
                    'offre_id' => $offre->offre_id,
                    'jeune_id' => $jeune->jeune_id,
                    'cv_document_id' => $cvDocument->document_id,
                    'lm_document_id' => $lmDocument->document_id,
                    'message_employeur' => $request->message_employeur,
                    'statut' => 'en_attente',
                    'date_postulation' => now(),
                ]);
            });

            return redirect()->route('jeunes.offres.show', $offre)
                ->with('success', 'Votre candidature a été soumise avec succès !');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la postulation: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.');
        }
    }

    /**
     * Afficher les candidatures du jeune
     */
    public function candidatures()
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        $candidatures = $jeune->postulations()
            ->with(['offreEmplois.employeur', 'cvDocument', 'lettreMotivationDocument'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.jeunes.candidatures', compact('candidatures', 'jeune'));
    }

    /**
     * Afficher une candidature spécifique
     */
    public function showCandidature(Postulation $postulation)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune || $postulation->jeune_id !== $jeune->jeune_id) {
            abort(403);
        }

        return view('pages.jeunes.show-candidature', compact('postulation', 'jeune'));
    }

    /**
     * Annuler une candidature
     */
    public function annulerCandidature(Postulation $postulation)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune || $postulation->jeune_id !== $jeune->jeune_id) {
            abort(403);
        }

        if ($postulation->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez annuler que les candidatures en attente.');
        }

        $postulation->update(['statut' => 'annulee']);
        $jeune->updateStatistiques();

        return back()->with('success', 'Candidature annulée avec succès.');
    }

    /**
     * Gérer les documents du jeune
     */
    public function documents()
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        $documents = $jeune->documents()->orderBy('created_at', 'desc')->get();

        return view('pages.jeunes.documents', compact('documents', 'jeune'));
    }

    /**
     * Uploader un document
     */
    public function uploadDocument(Request $request)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune) {
            return redirect()->route('profile.edit')->with('error', 'Veuillez compléter votre profil jeune.');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:cv,lettre_motivation',
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('document');
            $path = $file->store('documents/' . $request->type, 'public');
            
            Document::updateOrCreate(
                ['jeune_id' => $jeune->jeune_id, 'type' => $request->type],
                [
                    'nom_original' => $file->getClientOriginalName(),
                    'chemin_fichier' => $path,
                    'date_upload' => now(),
                ]
            );

            return back()->with('success', 'Document uploadé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'upload du document.');
        }
    }

    /**
     * Supprimer un document
     */
    public function supprimerDocument(Document $document)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune || $document->jeune_id !== $jeune->jeune_id) {
            abort(403);
        }

        // Vérifier si le document est utilisé dans une candidature
        if ($document->postulations()->exists() || $document->postulationsLettreMotivation()->exists()) {
            return back()->with('error', 'Ce document est utilisé dans une candidature et ne peut pas être supprimé.');
        }

        $document->supprimerFichier();
        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Télécharger un document
     */
    public function telechargerDocument(Document $document)
    {
        $jeune = Jeune::where('user_id', Auth::id())->first();
        
        if (!$jeune || $document->jeune_id !== $jeune->jeune_id) {
            abort(403);
        }

        return $document->telecharger();
    }
} 