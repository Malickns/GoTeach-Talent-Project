@extends('layouts.app')

@section('title', 'Détails de la candidature')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('jeunes.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
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
                        <a href="{{ route('jeunes.candidatures') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Mes candidatures</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            @php
                                $statusColors = [
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'retenu' => 'bg-blue-100 text-blue-800',
                                    'entretien_programme' => 'bg-purple-100 text-purple-800',
                                    'entretien_effectue' => 'bg-indigo-100 text-indigo-800',
                                    'embauche' => 'bg-green-100 text-green-800',
                                    'rejete' => 'bg-red-100 text-red-800',
                                    'annulee' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusLabels = [
                                    'en_attente' => 'En attente',
                                    'retenu' => 'Retenu',
                                    'entretien_programme' => 'Entretien programmé',
                                    'entretien_effectue' => 'Entretien effectué',
                                    'embauche' => 'Embauché',
                                    'rejete' => 'Refusé',
                                    'annulee' => 'Annulé'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$postulation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$postulation->statut] ?? ucfirst($postulation->statut) }}
                            </span>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $postulation->offreEmplois->titre }}</h1>
                        <p class="text-gray-600">{{ $postulation->offreEmplois->employeur->nom_entreprise ?? 'Entreprise non spécifiée' }}</p>
                    </div>

                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('jeunes.offres.show', $postulation->offreEmplois) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            Voir l'offre
                        </a>
                        
                        @if($postulation->statut === 'en_attente')
                            <form action="{{ route('jeunes.candidatures.annuler', $postulation) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ?')"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Annuler la candidature
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations sur l'offre -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations sur l'offre</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Poste</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $postulation->offreEmplois->titre }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $postulation->offreEmplois->employeur->nom_entreprise ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Localisation</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $postulation->offreEmplois->ville_travail }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de contrat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($postulation->offreEmplois->type_contrat) }}</dd>
                            </div>
                            @if($postulation->offreEmplois->salaire_minimum || $postulation->offreEmplois->salaire_maximum)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Salaire</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($postulation->offreEmplois->salaire_minimum && $postulation->offreEmplois->salaire_maximum)
                                        {{ number_format($postulation->offreEmplois->salaire_minimum) }} - {{ number_format($postulation->offreEmplois->salaire_maximum) }} FCFA
                                    @elseif($postulation->offreEmplois->salaire_minimum)
                                        À partir de {{ number_format($postulation->offreEmplois->salaire_minimum) }} FCFA
                                    @elseif($postulation->offreEmplois->salaire_maximum)
                                        Jusqu'à {{ number_format($postulation->offreEmplois->salaire_maximum) }} FCFA
                                    @endif
                                </dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents soumis -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Documents soumis</h2>
                        <div class="space-y-4">
                            <!-- CV -->
                            @if($postulation->cvDocument)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">CV</p>
                                            <p class="text-sm text-gray-500">{{ $postulation->cvDocument->nom_original }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('jeunes.documents.telecharger', $postulation->cvDocument) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                            @endif

                            <!-- Lettre de motivation -->
                            @if($postulation->lettreMotivationDocument)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Lettre de motivation</p>
                                            <p class="text-sm text-gray-500">{{ $postulation->lettreMotivationDocument->nom_original }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('jeunes.documents.telecharger', $postulation->lettreMotivationDocument) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Message à l'employeur -->
                @if($postulation->message_employeur)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Message à l'employeur</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $postulation->message_employeur }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations sur la candidature -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations sur la candidature</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de candidature</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $postulation->date_postulation ? $postulation->date_postulation->format('d/m/Y à H:i') : 'Date inconnue' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$postulation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$postulation->statut] ?? ucfirst($postulation->statut) }}
                                    </span>
                                </dd>
                            </div>
                            
                            @if($postulation->updated_at != $postulation->created_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dernière mise à jour</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $postulation->updated_at ? $postulation->updated_at->format('d/m/Y à H:i') : 'Date inconnue' }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('jeunes.offres.show', $postulation->offreEmplois) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                                Voir l'offre
                            </a>
                            
                            <a href="{{ route('jeunes.candidatures') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Retour aux candidatures
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
