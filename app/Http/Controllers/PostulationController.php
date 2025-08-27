<?php

namespace App\Http\Controllers;

use App\Models\Postulation;
use App\Models\OffreEmplois;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostulationController extends Controller
{
    /**
     * Afficher la liste des candidatures pour un employeur
     */
    public function index(Request $request)
    {
        $employeurId = Auth::user()?->employeur?->employeur_id;
        
        $postulations = Postulation::with(['offre', 'jeune', 'cvDocument', 'lettreMotivationDocument'])
            ->whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })
            ->orderBy('date_postulation', 'desc')
            ->paginate(15);

        // Statistiques
        $stats = [
            'total' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->count(),
            'en_attente' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'en_attente')->count(),
            'retenues' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'retenu')->count(),
            'rejetees' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'rejete')->count(),
        ];

        return view('pages.employeurs.candidatures.index', compact('postulations', 'stats'));
    }

    /**
     * Afficher les détails d'une candidature
     */
    public function show($id)
    {
        $employeurId = Auth::user()?->employeur?->employeur_id;
        
        $postulation = Postulation::with(['offre', 'jeune', 'cvDocument', 'lettreMotivationDocument'])
            ->whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })
            ->findOrFail($id);

        // Incrémenter le nombre de vues
        $postulation->increment('nombre_vues_employeur');

        return view('pages.employeurs.candidatures.show', compact('postulation'));
    }

    /**
     * Télécharger le CV d'un candidat
     */
    public function downloadCv($id)
    {
        $employeurId = Auth::id();
        
        $postulation = Postulation::with(['offre', 'cvDocument'])
            ->whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })
            ->findOrFail($id);

        // Incrémenter le nombre de téléchargements
        $postulation->increment('nombre_telechargements_cv');

        if ($postulation->cv_document_id && $postulation->cvDocument) {
            return response()->download(storage_path('app/public/' . $postulation->cvDocument->chemin_fichier), $postulation->cvDocument->nom_original);
        } elseif ($postulation->cv_path) {
            return response()->download(storage_path('app/public/' . $postulation->cv_path));
        }

        return back()->with('error', 'CV non trouvé');
    }

    /**
     * Télécharger la lettre de motivation
     */
    public function downloadLettreMotivation($id)
    {
        $employeurId = Auth::user()?->employeur?->employeur_id;
        
        $postulation = Postulation::with(['offre', 'lettreMotivationDocument'])
            ->whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })
            ->findOrFail($id);

        // Incrémenter le nombre de téléchargements
        $postulation->increment('nombre_telechargements_lm');

        if ($postulation->lm_document_id && $postulation->lettreMotivationDocument) {
            return response()->download(storage_path('app/public/' . $postulation->lettreMotivationDocument->chemin_fichier), $postulation->lettreMotivationDocument->nom_original);
        } elseif ($postulation->lettre_motivation_path) {
            return response()->download(storage_path('app/public/' . $postulation->lettre_motivation_path));
        }

        return back()->with('error', 'Lettre de motivation non trouvée');
    }

    /**
     * Mettre à jour le statut d'une candidature
     */
    public function updateStatut(Request $request, $id)
    {
        $employeurId = Auth::user()?->employeur?->employeur_id;
        
        $postulation = Postulation::whereHas('offre', function($query) use ($employeurId) {
            $query->where('employeur_id', $employeurId);
        })->findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,en_revue,retenu,rejete,entretien_programme,entretien_effectue,embauche,refuse_apres_entretien,retiree',
        ]);

        $postulation->update([
            'statut' => $request->statut,
        ]);

        return back()->with('success', 'Statut de la candidature mis à jour avec succès');
    }

    /**
     * Filtrer les candidatures par statut
     */
    public function filter(Request $request)
    {
        $employeurId = Auth::id();
        $statut = $request->get('statut');
        $offreId = $request->get('offre_id');

        $query = Postulation::with(['offre', 'jeune'])
            ->whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            });

        if ($statut && $statut !== 'tous') {
            $query->where('statut', $statut);
        }

        if ($offreId) {
            $query->where('offre_id', $offreId);
        }

        $postulations = $query->orderBy('date_postulation', 'desc')->paginate(15);

        return response()->json([
            'html' => view('pages.employeurs.candidatures.partials.liste', compact('postulations'))->render()
        ]);
    }

    /**
     * Obtenir les statistiques des candidatures
     */
    public function stats()
    {
        $employeurId = Auth::id();
        
        $stats = [
            'total' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->count(),
            'en_attente' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'en_attente')->count(),
            'retenues' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'retenu')->count(),
            'rejetees' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->where('statut', 'rejete')->count(),
            'entretiens' => Postulation::whereHas('offre', function($query) use ($employeurId) {
                $query->where('employeur_id', $employeurId);
            })->whereIn('statut', ['entretien_programme', 'entretien_effectue'])->count(),
        ];

        return response()->json($stats);
    }
} 