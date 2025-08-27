<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $offreEmplois->titre }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête de l'offre -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $offreEmplois->titre }}</h1>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-building mr-2"></i>
                            <span class="font-medium">{{ $offreEmplois->employeur->nom_entreprise ?? 'Entreprise non spécifiée' }}</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $offreEmplois->ville_travail }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-briefcase mr-2"></i>
                                <span class="capitalize">{{ $offreEmplois->type_contrat }}</span>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end space-y-2">
                        @if($offreEmplois->offre_urgente)
                            <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                            </span>
                        @endif
                        
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-eye mr-1"></i>{{ $offreEmplois->nombre_vues }} vues
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-users mr-1"></i>{{ $offreEmplois->nombre_candidatures }} candidatures
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Description du poste</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($offreEmplois->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Missions principales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Missions principales</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($offreEmplois->missions_principales)) !!}
                        </div>
                    </div>
                </div>

                <!-- Profil recherché -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Profil recherché</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($offreEmplois->profil_recherche)) !!}
                        </div>
                    </div>
                </div>

                @if($offreEmplois->avantages_offerts)
                    <!-- Avantages -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Avantages offerts</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($offreEmplois->avantages_offerts)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        
                        @if(auth()->user()->isJeune())
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 mb-3">
                                <i class="fas fa-paper-plane mr-2"></i>Postuler maintenant
                            </button>
                        @endif
                        
                        @if(auth()->user()->isEmployeur() && $offreEmplois->employeur_id === auth()->user()->employeur->employeur_id)
                            <div class="space-y-2">
                                <a href="{{ route('offres-emplois.edit', $offreEmplois) }}" 
                                   class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 inline-block text-center">
                                    <i class="fas fa-edit mr-2"></i>Modifier
                                </a>
                                
                                <form action="{{ route('offres-emplois.destroy', $offreEmplois) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                        <i class="fas fa-trash mr-2"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <a href="{{ route('offres-emplois.index') }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 inline-block text-center mt-3">
                            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Informations clés -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations clés</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Type de contrat</span>
                                <p class="text-gray-900 capitalize">{{ $offreEmplois->type_contrat }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Localisation</span>
                                <p class="text-gray-900">{{ $offreEmplois->ville_travail }}</p>
                            </div>
                            
                            
                            
                            
                        </div>
                    </div>
                </div>

                <!-- Compétences requises -->
                @if($offreEmplois->competences_requises && count($offreEmplois->competences_requises) > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Compétences requises</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($offreEmplois->competences_requises as $competence)
                                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                        {{ $competence }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                
            </div>
        </div>
    </div>
</div>
</x-app-layout>