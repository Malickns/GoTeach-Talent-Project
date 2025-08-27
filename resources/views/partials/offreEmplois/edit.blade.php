<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier l'offre d'emploi
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Modifier l'offre d'emploi</h2>
                <p class="text-gray-600">Modifiez les informations de votre offre d'emploi.</p>
            </div>

            <form method="POST" action="{{ route('offres-emplois.update', $offreEmplois) }}" class="p-6">
                @csrf
                @method('PUT')

                <!-- Messages d'erreur -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre du poste *
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               value="{{ old('titre', $offreEmplois->titre) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Développeur Full Stack">
                    </div>

                    <div>
                        <label for="type_contrat" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de contrat *
                        </label>
                        <select name="type_contrat" 
                                id="type_contrat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionnez un type</option>
                            <option value="cdi" {{ old('type_contrat', $offreEmplois->type_contrat) == 'cdi' ? 'selected' : '' }}>CDI</option>
                            <option value="cdd" {{ old('type_contrat', $offreEmplois->type_contrat) == 'cdd' ? 'selected' : '' }}>CDD</option>
                            <option value="stage" {{ old('type_contrat', $offreEmplois->type_contrat) == 'stage' ? 'selected' : '' }}>Stage</option>
                            <option value="formation" {{ old('type_contrat', $offreEmplois->type_contrat) == 'formation' ? 'selected' : '' }}>Formation</option>
                            <option value="freelance" {{ old('type_contrat', $offreEmplois->type_contrat) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="autre" {{ old('type_contrat', $offreEmplois->type_contrat) == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <!-- Description et profil -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description du poste *
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Décrivez le poste, les responsabilités principales...">{{ old('description', $offreEmplois->description) }}</textarea>
                </div>

                <!-- Profil recherché supprimé du schéma -->

                <div class="mb-8">
                    <label for="missions_principales" class="block text-sm font-medium text-gray-700 mb-2">
                        Missions principales *
                    </label>
                    <textarea name="missions_principales" 
                              id="missions_principales" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Listez les principales missions et responsabilités...">{{ old('missions_principales', $offreEmplois->missions_principales) }}</textarea>
                </div>

                <!-- Localisation et conditions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="ville_travail" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville de travail *
                        </label>
                        <input type="text" 
                               name="ville_travail" 
                               id="ville_travail" 
                               value="{{ old('ville_travail', $offreEmplois->ville_travail) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Dakar, Thiès, Saint-Louis">
                    </div>
                </div>

                <!-- Exigences -->
                <!-- Exigences (niveau/expérience) supprimées du schéma -->

                <!-- Compétences -->
                <div class="mb-8">
                    <label for="competences_requises" class="block text-sm font-medium text-gray-700 mb-2">
                        Compétences requises (séparées par des virgules)
                    </label>
                    <input type="text" 
                           name="competences_requises" 
                           id="competences_requises" 
                           value="{{ old('competences_requises', is_array($offreEmplois->competences_requises) ? implode(', ', $offreEmplois->competences_requises) : $offreEmplois->competences_requises) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: PHP, Laravel, MySQL, JavaScript">
                    <p class="text-sm text-gray-500 mt-1">Séparez les compétences par des virgules</p>
                </div>

                <!-- Avantages -->
                <div class="mb-8">
                    <label for="avantages_offerts" class="block text-sm font-medium text-gray-700 mb-2">
                        Avantages offerts
                    </label>
                    <textarea name="avantages_offerts" 
                              id="avantages_offerts" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Décrivez les avantages offerts (télétravail, avantages sociaux, etc.)">{{ old('avantages_offerts', $offreEmplois->avantages_offerts) }}</textarea>
                </div>

                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="offre_urgente" 
                               id="offre_urgente" 
                               value="1"
                               {{ old('offre_urgente', $offreEmplois->offre_urgente) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="offre_urgente" class="ml-2 block text-sm text-gray-900">
                            Offre urgente
                        </label>
                    </div>

                    <!-- visible_public supprimé du schéma -->
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('offres-emplois.show', $offreEmplois) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Conversion des compétences en tableau pour l'affichage
document.addEventListener('DOMContentLoaded', function() {
    const competencesInput = document.getElementById('competences_requises');
    if (competencesInput) {
        // Si on a des anciennes valeurs, les afficher correctement
        const oldValue = competencesInput.value;
        if (oldValue && typeof oldValue === 'string') {
            competencesInput.value = oldValue;
        }
    }
});
</script>
</x-app-layout>