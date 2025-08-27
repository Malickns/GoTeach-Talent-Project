<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Types de contrats disponibles
    |--------------------------------------------------------------------------
    */
    'types_contrat' => [
        'cdi' => 'CDI',
        'cdd' => 'CDD',
        'stage' => 'Stage',
        'formation' => 'Formation',
        'freelance' => 'Freelance',
        'autre' => 'Autre',
    ],

    /*
    |--------------------------------------------------------------------------
    | Niveaux d'étude
    |--------------------------------------------------------------------------
    */
    'niveaux_etude' => [
        'Bac' => 'Bac',
        'Bac+2' => 'Bac+2',
        'Bac+3' => 'Bac+3',
        'Bac+4' => 'Bac+4',
        'Bac+5' => 'Bac+5',
        'Doctorat' => 'Doctorat',
    ],

    /*
    |--------------------------------------------------------------------------
    | Statuts de postulation
    |--------------------------------------------------------------------------
    */
    'statuts_postulation' => [
        'en_attente' => 'En attente',
        'en_revue' => 'En revue',
        'retenu' => 'Retenu',
        'rejete' => 'Rejeté',
        'entretien_programme' => 'Entretien programmé',
        'entretien_effectue' => 'Entretien effectué',
        'embauche' => 'Embauché',
        'refuse_apres_entretien' => 'Refusé après entretien',
        'retiree' => 'Retirée',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'offres_par_page' => 10,
        'candidatures_par_page' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation des offres
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'auto_validation_employeur' => false, // Les offres des employeurs nécessitent-elles une validation admin ?
        'delai_validation' => 48, // Délai en heures pour la validation d'une offre
    ],
]; 