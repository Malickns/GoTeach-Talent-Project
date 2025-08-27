<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(auth()->user()->isEmployeur())
                Mes offres d'emplois
            @elseif(auth()->user()->isJeune())
                Offres d'emplois disponibles
            @else
                Toutes les offres d'emplois
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête avec titre et bouton de création -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    @if(auth()->user()->isEmployeur())
                        Mes offres d'emplois
                    @elseif(auth()->user()->isJeune())
                        Offres d'emplois disponibles
                    @else
                        Toutes les offres d'emplois
                    @endif
                </h1>
                
                @if(auth()->user()->isEmployeur() || auth()->user()->isAdmin())
                    <a href="{{ route('offres-emplois.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Nouvelle offre
                    </a>
                @endif
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Liste des offres -->
            @if($offresEmplois->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($offresEmplois as $offre)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 border border-gray-200">
                            <!-- En-tête de la carte -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                        {{ $offre->titre }}
                                    </h3>
                                    @if($offre->offre_urgente)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Urgent
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i class="fas fa-building mr-2"></i>
                                    <span>{{ $offre->employeur->nom_entreprise ?? 'Entreprise non spécifiée' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>{{ $offre->ville_travail }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-briefcase mr-2"></i>
                                    <span class="capitalize">{{ $offre->type_contrat }}</span>
                                </div>
                            </div>

                            <!-- Corps de la carte -->
                            <div class="p-6">
                                <p class="text-gray-700 text-sm line-clamp-3 mb-4">
                                    {{ Str::limit($offre->description, 150) }}
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($offre->competences_requises)
                                        @foreach(array_slice((array)$offre->competences_requises, 0, 3) as $competence)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                {{ $competence }}
                                            </span>
                                        @endforeach
                                        @if(count((array)$offre->competences_requises) > 3)
                                            <span class="text-gray-500 text-xs">+{{ count((array)$offre->competences_requises) - 3 }} autres</span>
                                        @endif
                                    @endif
                                </div>

                                <!-- Statistiques -->
                                <div class="flex justify-between text-xs text-gray-500 mb-4">
                                    <span><i class="fas fa-eye mr-1"></i>{{ $offre->nombre_vues }} vues</span>
                                    <span><i class="fas fa-users mr-1"></i>{{ $offre->nombre_candidatures }} candidatures</span>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('offres-emplois.show', $offre) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Voir détails
                                    </a>
                                    
                                    @if(auth()->user()->isEmployeur() && $offre->employeur_id === auth()->user()->employeur->employeur_id)
                                        <div class="flex space-x-2">
                                            <a href="{{ route('offres-emplois.edit', $offre) }}" 
                                               class="text-green-600 hover:text-green-800 text-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('offres-emplois.destroy', $offre) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $offresEmplois->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Aucune offre d'emploi trouvée
                    </h3>
                    <p class="text-gray-600">
                        @if(auth()->user()->isEmployeur())
                            Vous n'avez pas encore créé d'offres d'emploi.
                        @else
                            Aucune offre d'emploi n'est disponible pour le moment.
                        @endif
                    </p>
                    @if(auth()->user()->isEmployeur())
                        <a href="{{ route('offres-emplois.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Créer votre première offre
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
</x-app-layout>