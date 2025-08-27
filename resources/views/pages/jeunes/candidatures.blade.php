@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes candidatures</h1>
            <p class="mt-2 text-gray-600">Suivez l'état de vos candidatures et gérez vos postulations</p>
        </div>

        @if($candidatures->count() > 0)
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $candidatures->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En attente</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $candidatures->where('statut', 'en_attente')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Retenues</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $candidatures->whereIn('statut', ['retenu', 'entretien_programme', 'entretien_effectue'])->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Refusées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $candidatures->whereIn('statut', ['rejete', 'annulee'])->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des candidatures -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Historique des candidatures</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($candidatures as $candidature)
                        <div class="p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('jeunes.offres.show', $candidature->offreEmplois) }}" class="hover:text-blue-600">
                                                {{ $candidature->offreEmplois->titre }}
                                            </a>
                                        </h3>
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$candidature->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$candidature->statut] ?? ucfirst($candidature->statut) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Entreprise :</span>
                                            {{ $candidature->offreEmplois->employeur->nom_entreprise ?? 'Non spécifié' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Localisation :</span>
                                            {{ $candidature->offreEmplois->ville_travail }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Date de candidature :</span>
                                            {{ $candidature->date_postulation ? $candidature->date_postulation->format('d/m/Y') : 'Date inconnue' }}
                                        </div>
                                    </div>

                                    @if($candidature->message_employeur)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-md">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Message :</span>
                                                {{ $candidature->message_employeur }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('jeunes.candidatures.show', $candidature) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Voir
                                    </a>
                                    
                                    @if($candidature->statut === 'en_attente')
                                        <form action="{{ route('jeunes.candidatures.annuler', $candidature) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ?')"
                                                    class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($candidatures->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $candidatures->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- État vide -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune candidature</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas encore postulé à des offres d'emploi.</p>
                <a href="{{ route('jeunes.offres') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Découvrir les offres
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
