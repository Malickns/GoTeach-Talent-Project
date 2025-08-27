@php
    $editing = isset($jeune) && $jeune;
@endphp

<form method="POST" action="{{ $action }}" class="row g-3">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="col-12 col-md-6">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $editing ? $jeune->user?->prenom : '') }}" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom', $editing ? $jeune->user?->nom : '') }}" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $editing ? $jeune->user?->email : '') }}" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $editing ? ($jeune->telephone ?? $jeune->user?->telephone) : '') }}">
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Date de naissance</label>
        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $editing ? optional($jeune->date_naissance)->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Genre</label>
        <select name="genre" class="form-select" required>
            <option value="">Choisir</option>
            <option value="homme" @selected(old('genre', $editing ? $jeune->genre : '')==='homme')>Homme</option>
            <option value="femme" @selected(old('genre', $editing ? $jeune->genre : '')==='femme')>Femme</option>
        </select>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">CNI (optionnel)</label>
        <input type="text" name="numero_cni" class="form-control" value="{{ old('numero_cni', $editing ? $jeune->numero_cni : '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $editing ? $jeune->adresse : '') }}" required>
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Ville</label>
        <input type="text" name="ville" class="form-control" value="{{ old('ville', $editing ? $jeune->ville : '') }}" required>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Région</label>
        <input type="text" name="region" class="form-control" value="{{ old('region', $editing ? $jeune->region : '') }}" required>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Pays</label>
        <input type="text" name="pays" class="form-control" value="{{ old('pays', $editing ? $jeune->pays : 'Sénégal') }}" required>
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Niveau d'étude</label>
        <input type="text" name="niveau_etude" class="form-control" value="{{ old('niveau_etude', $editing ? $jeune->niveau_etude : '') }}" required>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Dernier diplôme</label>
        <input type="text" name="dernier_diplome" class="form-control" value="{{ old('dernier_diplome', $editing ? $jeune->dernier_diplome : '') }}">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Etablissement</label>
        <input type="text" name="etablissement" class="form-control" value="{{ old('etablissement', $editing ? $jeune->etablissement : '') }}">
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Année d'obtention</label>
        <input type="number" name="annee_obtention" class="form-control" value="{{ old('annee_obtention', $editing ? $jeune->annee_obtention : '') }}" min="1950" max="{{ date('Y')+1 }}">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Lieu de naissance</label>
        <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $editing ? $jeune->lieu_naissance : '') }}" required>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label">Nationalité</label>
        <input type="text" name="nationalite" class="form-control" value="{{ old('nationalite', $editing ? $jeune->nationalite : 'Sénégalaise') }}" required>
    </div>

    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <a href="{{ route('admin.national.jeunes.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>


