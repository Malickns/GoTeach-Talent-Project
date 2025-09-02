<?php

namespace App\Services;

use App\Models\Jeune;
use App\Models\OffreEmplois;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Retourne une pagination d'offres recommandées pour un jeune donné.
     */
    public static function recommandationsPourJeune(Jeune $jeune, int $perPage = 12, ?int $page = null): LengthAwarePaginator
    {
        $page = $page ?: request()->integer('page', 1);

        // Offres visibles, récentes d'abord, avec relations utiles
        $query = OffreEmplois::visible()
            ->with(['employeur'])
            ->orderBy('date_publication', 'desc');

        // Exclure les offres déjà postulées par ce jeune
        $offreIdsDejaPostulees = $jeune->postulations()->pluck('offre_id');
        if ($offreIdsDejaPostulees->isNotEmpty()) {
            $query->whereNotIn('offre_id', $offreIdsDejaPostulees);
        }

        // Récupérer un pool raisonnable (ex: 200 dernières visibles) pour scorer côté application
        $offresPool = $query->limit(200)->get();

        // Préparer les données du jeune
        $ville = trim((string) $jeune->ville);
        $region = trim((string) $jeune->region);
        $competencesJeune = self::normaliserListeTexte((string) ($jeune->competences ?? ''));
        $preferences = self::normaliserListeTexte((string) ($jeune->preferences_emploi ?? ''));

        // Scoring
        $typesPref = array_map('mb_strtolower', (array) ($jeune->types_contrat_preferes ?? []));
        $secteursPref = array_map('mb_strtolower', (array) ($jeune->secteurs_preferes ?? []));

        $scored = $offresPool->map(function (OffreEmplois $offre) use ($ville, $region, $competencesJeune, $preferences, $typesPref, $secteursPref) {
            $score = 0;

            // Ville et région
            if ($ville && mb_strtolower($offre->ville_travail) === mb_strtolower($ville)) {
                $score += 30;
            }
            if ($region && str_contains(mb_strtolower((string) $offre->ville_travail), mb_strtolower($region))) {
                $score += 15;
            }

            // Urgence
            if ((bool) $offre->offre_urgente) {
                $score += 5;
            }

            // Compétences
            $competencesOffre = self::normaliserArray((array) ($offre->competences_requises ?? []));
            $matchCompetences = count(array_intersect($competencesJeune, $competencesOffre));
            if ($matchCompetences > 0) {
                $score += $matchCompetences * 10;
            }

            // Préférences (mots-clés) dans titre/description + type contrat + secteur
            $texte = mb_strtolower(trim(($offre->titre ?? '') . ' ' . ($offre->description ?? '')));
            $typeContrat = mb_strtolower((string) ($offre->type_contrat ?? ''));
            $secteur = mb_strtolower((string) ($offre->employeur->secteur_activite ?? ''));
            foreach ($preferences as $pref) {
                if ($pref !== '' && str_contains($texte, $pref)) {
                    $score += 6; // petit bonus cumulé par mot-clé
                }
                if ($pref !== '' && $typeContrat !== '' && str_contains($typeContrat, $pref)) {
                    $score += 8; // bonus type de contrat correspondant
                }
                if ($pref !== '' && $secteur !== '' && str_contains($secteur, $pref)) {
                    $score += 8; // bonus secteur d'activité correspondant
                }
            }

            // Bonus explicite si le type/secteur fait partie des préférences sélectionnées
            if ($typeContrat && in_array($typeContrat, $typesPref, true)) {
                $score += 12;
            }
            if ($secteur && in_array($secteur, $secteursPref, true)) {
                $score += 12;
            }

            // Fraîcheur (max 20, décroît avec le temps)
            $jours = now()->diffInDays($offre->date_publication ?? $offre->created_at);
            $fraicheur = max(0, 20 - min(20, (int) floor($jours * 1.5)));
            $score += $fraicheur;

            // Garde une petite base pour trier à défaut
            $score += 1;

            $offre->reco_score = $score;
            return $offre;
        });

        // Trier par score desc puis par date_publication desc
        $sorted = $scored->sortByDesc(function ($o) {
            return [$o->reco_score, $o->date_publication ?? $o->created_at];
        })->values();

        return self::paginateCollection($sorted, $perPage, $page);
    }

    /**
     * Convertit une chaîne en liste normalisée (séparateurs: virgule, point-virgule, barre, espace multiple).
     */
    private static function normaliserListeTexte(string $texte): array
    {
        $texte = str_replace([';', '|', '\\n'], ',', $texte);
        $items = array_map('trim', explode(',', $texte));
        $items = array_filter($items, fn($i) => $i !== '');
        return array_values(array_map('mb_strtolower', $items));
    }

    /**
     * Normalise un tableau de texte en minuscules/trim.
     */
    private static function normaliserArray(array $items): array
    {
        $items = array_map(function ($i) {
            return mb_strtolower(trim((string) $i));
        }, $items);
        return array_values(array_filter($items, fn($i) => $i !== ''));
    }

    /**
     * Paginer une collection en LengthAwarePaginator.
     */
    private static function paginateCollection(Collection $items, int $perPage, int $page): LengthAwarePaginator
    {
        $total = $items->count();
        $results = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}


