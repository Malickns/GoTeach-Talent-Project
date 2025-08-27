<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jeune;
use App\Models\Employeur;
use App\Models\Programme;
use App\Models\CategorieJeune;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        // Récupérer les programmes et catégories pour le formulaire
        $programmes = Programme::actifs()->get();
        $categories = CategorieJeune::actives()->get();
        
        return view('auth.register', compact('programmes', 'categories'));
    }

    /**
     * Traiter l'inscription d'un jeune
     */
    public function registerJeune(Request $request)
    {
        // Rate limiting pour l'inscription
        $this->ensureIsNotRateLimited($request);

        // Validation des données
        $validator = Validator::make($request->all(), [
            // Informations utilisateur
            'prenom' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'telephone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            
            // Informations jeune
            'date_naissance' => ['required', 'date', 'before:-16 years'], // Minimum 16 ans
            'genre' => ['required', 'in:homme,femme'],
            'lieu_naissance' => ['required', 'string', 'max:100'],
            // 'nationalite' supprimé du schéma
            'numero_cni' => ['nullable', 'string', 'max:50', 'unique:jeunes'],
            'adresse' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:100'],
            'region' => ['required', 'string', 'max:100'],
            // 'pays' supprimé du schéma
            'niveau_etude' => ['required', 'string', 'max:50'],
            'dernier_diplome' => ['nullable', 'string', 'max:100'],
            'etablissement' => ['nullable', 'string', 'max:100'],
            'annee_obtention' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'programme_id' => ['nullable', 'exists:programmes,programme_id'],
            
            // Contacts d'urgence supprimés du schéma
            
            // Captcha supprimé
            // 'g-recaptcha-response' => ['required', 'recaptcha'],
            
            // Conditions d'utilisation
            'terms' => ['required', 'accepted'],
        ], [
            'date_naissance.before' => 'Vous devez avoir au moins 16 ans pour vous inscrire.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            // Messages captcha supprimés
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        try {
            DB::beginTransaction();

            // Créer l'utilisateur
            $user = User::create([
                'prenom' => $request->prenom,
                'nom' => $request->nom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'role' => 'jeune',
                'statut' => 'inactif', // Statut inactif par défaut
                'email_verified_at' => null, // Email non vérifié
            ]);

            // Créer le profil jeune
            $jeune = Jeune::create([
                'user_id' => $user->user_id,
                'date_naissance' => $request->date_naissance,
                'genre' => $request->genre,
                'lieu_naissance' => $request->lieu_naissance,
                'numero_cni' => $request->numero_cni,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'region' => $request->region,
                'niveau_etude' => $request->niveau_etude,
                'dernier_diplome' => $request->dernier_diplome,
                'etablissement' => $request->etablissement,
                'annee_obtention' => $request->annee_obtention,
                'programme_id' => $request->programme_id,
            ]);

            DB::commit();

            // Log de l'inscription
            Log::info('Nouvelle inscription jeune', [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Event d'inscription
            event(new Registered($user));

            // Redirection avec message
            return redirect()->route('login')
                ->with('status', 'Inscription réussie ! Votre compte sera activé après validation par un administrateur.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de l\'inscription jeune', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    /**
     * Traiter l'inscription d'un employeur
     */
    public function registerEmployeur(Request $request)
    {
        // Rate limiting pour l'inscription
        $this->ensureIsNotRateLimited($request);

        // Validation des données
        $validator = Validator::make($request->all(), [
            // Informations utilisateur
            'prenom' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'telephone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            
            // Informations entreprise
            'nom_entreprise' => ['required', 'string', 'max:100'],
            'raison_sociale' => ['nullable', 'string', 'max:100'],
            'numero_rccm' => ['nullable', 'string', 'max:50', 'unique:employeurs'],
            'numero_ninea' => ['nullable', 'string', 'max:50', 'unique:employeurs'],
            'numero_contribuable' => ['nullable', 'string', 'max:50'],
            'secteur_activite' => ['required', 'string', 'max:100'],
            'description_activite' => ['nullable', 'string'],
            'taille_entreprise' => ['required', 'in:micro,petite,moyenne,grande'],
            'nombre_employes' => ['nullable', 'integer', 'min:1'],
            'nombre_employes_actuel' => ['nullable', 'integer', 'min:0'],
            'adresse_entreprise' => ['required', 'string', 'max:255'],
            'ville_entreprise' => ['required', 'string', 'max:100'],
            'region_entreprise' => ['required', 'string', 'max:100'],
            'pays_entreprise' => ['required', 'string', 'max:50'],
            'code_postal_entreprise' => ['nullable', 'string', 'max:10'],
            'telephone_entreprise' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            'fax_entreprise' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            'site_web' => ['nullable', 'url', 'max:255'],
            'email_entreprise' => ['nullable', 'email', 'max:100'],
            'contact_rh_nom' => ['nullable', 'string', 'max:100'],
            'contact_rh_telephone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            'contact_rh_email' => ['nullable', 'email', 'max:100'],
            'type_entreprise' => ['required', 'in:privee,publique,ong,cooperative,autre'],
            
            // Captcha supprimé
            // 'g-recaptcha-response' => ['required', 'recaptcha'],
            
            // Conditions d'utilisation
            'terms' => ['required', 'accepted'],
        ], [
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            // Messages captcha supprimés
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        try {
            DB::beginTransaction();

            // Créer l'utilisateur
            $user = User::create([
                'prenom' => $request->prenom,
                'nom' => $request->nom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'role' => 'employeur',
                'statut' => 'inactif', // Statut inactif par défaut
                'email_verified_at' => null, // Email non vérifié
            ]);

            // Créer le profil employeur
            $employeur = Employeur::create([
                'user_id' => $user->user_id,
                'nom_entreprise' => $request->nom_entreprise,
                'raison_sociale' => $request->raison_sociale,
                'numero_rccm' => $request->numero_rccm,
                'numero_ninea' => $request->numero_ninea,
                'numero_contribuable' => $request->numero_contribuable,
                'secteur_activite' => $request->secteur_activite,
                'description_activite' => $request->description_activite,
                'taille_entreprise' => $request->taille_entreprise,
                'nombre_employes' => $request->nombre_employes,
                'nombre_employes_actuel' => $request->nombre_employes_actuel,
                'adresse_entreprise' => $request->adresse_entreprise,
                'ville_entreprise' => $request->ville_entreprise,
                'region_entreprise' => $request->region_entreprise,
                'pays_entreprise' => $request->pays_entreprise,
                'code_postal_entreprise' => $request->code_postal_entreprise,
                'telephone_entreprise' => $request->telephone_entreprise,
                'fax_entreprise' => $request->fax_entreprise,
                'site_web' => $request->site_web,
                'email_entreprise' => $request->email_entreprise,
                'contact_rh_nom' => $request->contact_rh_nom,
                'contact_rh_telephone' => $request->contact_rh_telephone,
                'contact_rh_email' => $request->contact_rh_email,
                'type_entreprise' => $request->type_entreprise,
                'autorise_publier_offres' => false,
                'autorise_voir_candidatures' => true,
                'autorise_contacter_jeunes' => true,
                'profil_public' => true,
                'statut_verification' => 'en_attente',
            ]);

            DB::commit();

            // Log de l'inscription
            Log::info('Nouvelle inscription employeur', [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'entreprise' => $employeur->nom_entreprise,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Event d'inscription
            event(new Registered($user));

            // Redirection avec message
            return redirect()->route('login')
                ->with('status', 'Inscription réussie ! Votre compte sera activé après validation par un administrateur.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de l\'inscription employeur', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    /**
     * Vérifier que l'inscription n'est pas limitée par le rate limiting
     */
    protected function ensureIsNotRateLimited(Request $request)
    {
        $key = 'registration:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) { // 3 tentatives par heure
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
        
        RateLimiter::hit($key, 3600); // 1 heure
    }

    /**
     * Vérifier la disponibilité d'un email
     */
    public function checkEmail(Request $request)
    {
        $email = $request->get('email');
        
        if (empty($email)) {
            return response()->json(['available' => false, 'message' => 'Email requis']);
        }
        
        $exists = User::where('email', $email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Cet email est déjà utilisé' : 'Email disponible'
        ]);
    }

    /**
     * Vérifier la disponibilité d'un numéro RCCM
     */
    public function checkRccm(Request $request)
    {
        $rccm = $request->get('numero_rccm');
        
        if (empty($rccm)) {
            return response()->json(['available' => true]);
        }
        
        $exists = Employeur::where('numero_rccm', $rccm)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Ce numéro RCCM est déjà utilisé' : 'Numéro RCCM disponible'
        ]);
    }

    /**
     * Vérifier la disponibilité d'un numéro NINEA
     */
    public function checkNinea(Request $request)
    {
        $ninea = $request->get('numero_ninea');
        
        if (empty($ninea)) {
            return response()->json(['available' => true]);
        }
        
        $exists = Employeur::where('numero_ninea', $ninea)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Ce numéro NINEA est déjà utilisé' : 'Numéro NINEA disponible'
        ]);
    }
} 