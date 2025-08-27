@extends('layouts.app')

@section('title', $offre->titre . ' - Détails de l\'offre | GoTeach')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('jeunes.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Tableau de bord
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('jeunes.offres') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200 md:ml-2">Offres d'emploi</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ Str::limit($offre->titre, 50) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header de l'offre avec design moderne -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
            <!-- Bannière supérieure avec gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1 text-white">
                        <div class="flex items-center space-x-3 mb-4">
                            @if($offre->offre_urgente)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800 animate-pulse">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Offre urgente
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white bg-opacity-20 text-white backdrop-blur-sm">
                                {{ ucfirst($offre->type_contrat) }}
                            </span>
                        </div>
                        
                        <h1 class="text-4xl font-bold mb-3 leading-tight">{{ $offre->titre }}</h1>
                        
                        <div class="flex items-center text-blue-100 mb-4">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-lg font-semibold">{{ $offre->ville_travail }}</span>
                        </div>

                        @if($employeur)
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-white">{{ $employeur->nom_entreprise }}</p>
                                    <p class="text-blue-100">{{ $employeur->secteur_activite ?? 'Secteur non spécifié' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-3">
                        @if($aPostule)
                            <div class="inline-flex items-center px-6 py-3 rounded-xl text-base font-semibold bg-green-100 text-green-800 border-2 border-green-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Candidature envoyée
                            </div>
                        @else
                            <a href="{{ route('jeunes.offres.candidature', $offre) }}" 
                               class="inline-flex items-center px-8 py-4 border-2 border-transparent text-lg font-semibold rounded-xl text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Postuler maintenant
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="p-8">
                <!-- Statistiques de l'offre -->
                @if(isset($statistiquesOffre) && is_array($statistiquesOffre))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $statistiquesOffre['total_vues'] ?? 0 }}</div>
                        <div class="text-sm text-blue-600">Vues totales</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $statistiquesOffre['vues_uniques'] ?? 0 }}</div>
                        <div class="text-sm text-green-600">Vues uniques</div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $statistiquesOffre['total_candidatures'] ?? 0 }}</div>
                        <div class="text-sm text-purple-600">Candidatures</div>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $statistiquesOffre['candidatures_en_attente'] ?? 0 }}</div>
                        <div class="text-sm text-orange-600">En attente</div>
                    </div>
                </div>
                @endif

                <!-- Grille des informations -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne principale -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Description -->
                        @if($offre->description)
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Description du poste
                            </h3>
                            <div class="prose prose-gray max-w-none">
                                {!! nl2br(e($offre->description)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Missions principales -->
                        @if($offre->missions_principales)
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Missions principales
                            </h3>
                            <div class="prose prose-gray max-w-none">
                                {!! nl2br(e($offre->missions_principales)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Compétences requises -->
                        @if($offre->competences_requises && is_array($offre->competences_requises))
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                </svg>
                                Compétences requises
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($offre->competences_requises as $competence)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ $competence }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Colonne latérale -->
                    <div class="space-y-6">
                        <!-- Détails du contrat -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails du contrat</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Type de contrat</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst($offre->type_contrat) }}</span>
                                </div>
                                @if($offre->duree_contrat_mois)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Durée</span>
                                    <span class="font-medium text-gray-900">{{ $offre->duree_contrat_mois }} mois</span>
                                </div>
                                @endif
                                @if($offre->date_debut_contrat)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Début</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $offre->date_debut_contrat ? $offre->date_debut_contrat->format('d/m/Y') : 'Non spécifiée' }}
                                    </span>
                                </div>
                                @endif
                                @if($offre->date_fin_contrat)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Fin</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $offre->date_fin_contrat ? $offre->date_fin_contrat->format('d/m/Y') : 'Non spécifiée' }}
                                    </span>
                                </div>
                                @endif
                                @if($offre->grade)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Grade</span>
                                    <span class="font-medium text-gray-900">{{ $offre->grade }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informations de l'entreprise -->
                        @if($employeur)
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">À propos de l'entreprise</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-600 text-sm">Nom</span>
                                    <p class="font-medium text-gray-900">{{ $employeur->nom_entreprise }}</p>
                                </div>
                                @if($employeur->secteur_activite)
                                <div>
                                    <span class="text-gray-600 text-sm">Secteur</span>
                                    <p class="font-medium text-gray-900">{{ $employeur->secteur_activite }}</p>
                                </div>
                                @endif
                                @if($employeur->description_activite)
                                <div>
                                    <span class="text-gray-600 text-sm">Description</span>
                                    <p class="text-sm text-gray-700">{{ Str::limit($employeur->description_activite, 100) }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-600 text-sm">Localisation</span>
                                    <p class="font-medium text-gray-900">{{ $employeur->ville_entreprise }}, {{ $employeur->region_entreprise }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Statistiques de publication -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de publication</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Publiée le</span>
                                    <span class="font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($offre->created_at) }}</span>
                                </div>
                                @if($offre->date_expiration)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Expire le</span>
                                    <span class="font-medium text-gray-900">{{ \App\Helpers\DateHelper::formatDate($offre->date_expiration) }}</span>
                                </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Référence</span>
                                    <span class="font-medium text-gray-900">#{{ $offre->offre_id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offres similaires -->
        @if(isset($offresSimilaires) && $offresSimilaires->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Offres similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($offresSimilaires as $offreSimilaire)
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <a href="{{ route('jeunes.offres.show', $offreSimilaire) }}" class="hover:text-blue-600 transition-colors duration-200">
                            {{ $offreSimilaire->titre }}
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-3">{{ $offreSimilaire->employeur->nom_entreprise ?? 'Entreprise non spécifiée' }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ $offreSimilaire->ville_travail }}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $offreSimilaire->type_contrat }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions finales -->
        <div class="mt-8 text-center">
            @if(!$aPostule)
            <div class="space-y-4">
                <a href="{{ route('jeunes.offres.candidature', $offre) }}" 
                   class="inline-flex items-center px-8 py-4 border-2 border-transparent text-xl font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-xl">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Postuler à cette offre
                </a>
                <p class="text-gray-600">Assurez-vous d'avoir votre CV et lettre de motivation à jour</p>
            </div>
            @else
            <div class="space-y-4">
                <div class="inline-flex items-center px-6 py-3 rounded-xl text-lg font-semibold bg-green-100 text-green-800 border-2 border-green-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Vous avez déjà postulé à cette offre
                </div>
                <p class="text-gray-600">Consultez vos candidatures pour suivre l'évolution</p>
                <a href="{{ route('jeunes.candidatures') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Voir mes candidatures
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
