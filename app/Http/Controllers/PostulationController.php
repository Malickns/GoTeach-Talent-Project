<?php

namespace App\Http\Controllers;

use App\Models\Postulation;
use App\Models\Document;
use App\Models\OffreEmplois;
use App\Models\Jeune;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostulationController extends Controller
{
    /**
     * Afficher le formulaire de candidature
     */
    public function showCandidatureForm($offreId)
    {
        $offre = OffreEmplois::with('employeur.user')->findOrFail($offreId);
        $jeune = Auth::user()->jeune;
        
        // Vérifier si l'utilisateur a déjà postulé
        $existingPostulation = Postulation::where('offre_id', $offreId)
            ->where('jeune_id', $jeune->jeune_id)
            ->first();
            
        if ($existingPostulation) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà postulé pour cette offre.',
                'postulation' => $existingPostulation
            ]);
        }
        
        // Récupérer les documents existants de l'utilisateur
        $documents = Document::where('jeune_id', $jeune->jeune_id)
            ->whereIn('type', ['cv', 'lettre_motivation'])
            ->get()
            ->keyBy('type');
        
        return response()->json([
            'success' => true,
            'offre' => $offre,
            'jeune' => $jeune,
            'documents' => $documents
        ]);
    }
    
    /**
     * Soumettre une candidature
     */
    public function submitCandidature(Request $request, $offreId)
    {
        $validator = Validator::make($request->all(), [
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'lettre_motivation_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'use_existing_cv' => 'nullable|boolean',
            'use_existing_lm' => 'nullable|boolean',
            'message_motivation' => 'nullable|string|max:1000',
            'disponibilite' => 'required|string|in:immediate,1_semaine,2_semaines,1_mois,plus',
            'niveau_etude' => 'required|string|max:100',
            'langues' => 'nullable|string|max:500',
            'competences' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            $errorMessages = [];
            foreach ($validator->errors()->all() as $error) {
                $errorMessages[] = $error;
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation : ' . implode(', ', $errorMessages),
                'errors' => $validator->errors()
            ]);
        }
        
        try {
            $jeune = Auth::user()->jeune;
            $offre = OffreEmplois::findOrFail($offreId);
            
            // Vérifier si l'utilisateur a déjà postulé
            $existingPostulation = Postulation::where('offre_id', $offreId)
                ->where('jeune_id', $jeune->jeune_id)
                ->first();
                
            if ($existingPostulation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà postulé pour cette offre.'
                ]);
            }
            
            // Validation conditionnelle pour les documents
            $useExistingCv = $request->boolean('use_existing_cv');
            $useExistingLm = $request->boolean('use_existing_lm');
            
            // Vérifier que l'utilisateur a soit un nouveau fichier, soit un document existant
            if (!$useExistingCv && !$request->hasFile('cv_file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez soit uploader un nouveau CV, soit utiliser votre CV existant.'
                ]);
            }
            
            if (!$useExistingLm && !$request->hasFile('lettre_motivation_file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez soit uploader une nouvelle lettre de motivation, soit utiliser votre lettre existante.'
                ]);
            }
            
            // Traiter les fichiers uploadés et récupérer les documents existants
            $cvDocumentId = null;
            $lmDocumentId = null;
            $cvPath = null;
            $lmPath = null;
            
            // Traiter le CV
            if ($useExistingCv) {
                // Récupérer le CV existant
                $cvDocument = Document::where('jeune_id', $jeune->jeune_id)
                    ->where('type', 'cv')
                    ->first();
                
                if (!$cvDocument) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun CV existant trouvé. Veuillez uploader un nouveau CV.'
                    ]);
                }
                
                $cvDocumentId = $cvDocument->document_id;
                $cvPath = $cvDocument->chemin_fichier;
            } elseif ($request->hasFile('cv_file')) {
                $cvFile = $request->file('cv_file');
                $cvFileName = 'cv_' . $jeune->jeune_id . '_' . time() . '.' . $cvFile->getClientOriginalExtension();
                $cvPath = $cvFile->storeAs('documents/cv', $cvFileName, 'public');
                
                // Vérifier si l'utilisateur a déjà un CV
                $cvDocument = Document::where('jeune_id', $jeune->jeune_id)
                    ->where('type', 'cv')
                    ->first();
                
                if ($cvDocument) {
                    // Mettre à jour le CV existant
                    $cvDocument->update([
                        'chemin_fichier' => $cvPath,
                        'nom_original' => $cvFile->getClientOriginalName(),
                    ]);
                    $cvDocumentId = $cvDocument->document_id;
                } else {
                    // Créer un nouveau CV
                    $cvDocument = Document::create([
                        'jeune_id' => $jeune->jeune_id,
                        'type' => 'cv',
                        'chemin_fichier' => $cvPath,
                        'nom_original' => $cvFile->getClientOriginalName(),
                    ]);
                    $cvDocumentId = $cvDocument->document_id;
                }
            }
            
            // Traiter la lettre de motivation
            if ($useExistingLm) {
                // Récupérer la lettre de motivation existante
                $lmDocument = Document::where('jeune_id', $jeune->jeune_id)
                    ->where('type', 'lettre_motivation')
                    ->first();
                
                if (!$lmDocument) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucune lettre de motivation existante trouvée. Veuillez uploader une nouvelle lettre.'
                    ]);
                }
                
                $lmDocumentId = $lmDocument->document_id;
                $lmPath = $lmDocument->chemin_fichier;
            } elseif ($request->hasFile('lettre_motivation_file')) {
                $lmFile = $request->file('lettre_motivation_file');
                $lmFileName = 'lm_' . $jeune->jeune_id . '_' . time() . '.' . $lmFile->getClientOriginalExtension();
                $lmPath = $lmFile->storeAs('documents/lettres_motivation', $lmFileName, 'public');
                
                // Vérifier si l'utilisateur a déjà une lettre de motivation
                $lmDocument = Document::where('jeune_id', $jeune->jeune_id)
                    ->where('type', 'lettre_motivation')
                    ->first();
                
                if ($lmDocument) {
                    // Mettre à jour la lettre existante
                    $lmDocument->update([
                        'chemin_fichier' => $lmPath,
                        'nom_original' => $lmFile->getClientOriginalName(),
                    ]);
                    $lmDocumentId = $lmDocument->document_id;
                } else {
                    // Créer une nouvelle lettre
                    $lmDocument = Document::create([
                        'jeune_id' => $jeune->jeune_id,
                        'type' => 'lettre_motivation',
                        'chemin_fichier' => $lmPath,
                        'nom_original' => $lmFile->getClientOriginalName(),
                    ]);
                    $lmDocumentId = $lmDocument->document_id;
                }
            }
            
            // Créer la postulation
            $postulation = Postulation::create([
                'offre_id' => $offreId,
                'jeune_id' => $jeune->jeune_id,
                'cv_path' => $cvPath,
                'lettre_motivation_path' => $lmPath,
                'cv_document_id' => $cvDocumentId,
                'lm_document_id' => $lmDocumentId,
                'documents_supplementaires' => [
                    'message_motivation' => $request->message_motivation,
                    'disponibilite' => $request->disponibilite,
                    'niveau_etude' => $request->niveau_etude,
                    'langues' => $request->langues,
                    'competences' => $request->competences,
                ],
                'statut' => 'en_attente',
                'date_postulation' => now(),
            ]);
            
            // Log de l'activité
            \Log::info('Nouvelle candidature soumise', [
                'postulation_id' => $postulation->postulation_id,
                'offre_id' => $offreId,
                'jeune_id' => $jeune->jeune_id,
                'offre_titre' => $offre->titre,
                'jeune_nom' => $jeune->nom ?? 'N/A'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Votre candidature a été soumise avec succès !',
                'postulation_id' => $postulation->postulation_id,
                'redirect_url' => route('jeunes.dashboard')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la soumission de candidature', [
                'offre_id' => $offreId,
                'jeune_id' => $jeune->jeune_id ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.'
            ], 500);
        }
    }
    
    /**
     * Obtenir les détails d'une postulation
     */
    public function getPostulationDetails($postulationId)
    {
        $postulation = Postulation::with(['offre', 'jeune', 'cvDocument', 'lettreMotivationDocument'])
            ->findOrFail($postulationId);
            
        return response()->json([
            'success' => true,
            'postulation' => $postulation
        ]);
    }
    
    /**
     * Récupérer les documents existants de l'utilisateur
     */
    public function getExistingDocuments()
    {
        try {
            $jeune = Auth::user()->jeune;
            
            $documents = Document::where('jeune_id', $jeune->jeune_id)
                ->whereIn('type', ['cv', 'lettre_motivation'])
                ->get()
                ->keyBy('type');

        return response()->json([
                'success' => true,
                'documents' => $documents
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des documents.'
            ], 500);
        }
    }
    
    /**
     * Retirer une candidature
     */
    public function retirerCandidature($postulationId)
    {
        try {
            $jeune = Auth::user()->jeune;
            $postulation = Postulation::where('postulation_id', $postulationId)
                ->where('jeune_id', $jeune->jeune_id)
                ->firstOrFail();
                
            if ($postulation->statut !== 'en_attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez retirer que les candidatures en attente.'
                ]);
            }
            
            $postulation->update([
                'statut' => 'retiree',
                'date_retrait' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Votre candidature a été retirée avec succès.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du retrait de votre candidature.'
            ], 500);
        }
    }
} 