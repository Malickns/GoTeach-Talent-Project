@extends('pages.admins.national.layout.app')

@section('title', 'Jeunes – Admin national')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="mb-3">
            <div class="bg-white border rounded p-3 p-md-4">
                <div class="row g-2 g-md-3 align-items-center">
                    <div class="col-12 col-md-6">
                        <div class="brand-title-container">
                            <h1 class="brand-title">GoTeach Sénégal</h1>
                            <span class="brand-underline"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <form method="GET" class="w-100" action="{{ route('admin.national.jeunes.index') }}">
                            <div class="row g-2">
                                <div class="col-12 col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="search" class="form-control border-start-0" name="search" value="{{ request('search') }}" placeholder="Rechercher (nom, email, ville, niveau)…">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <button class="btn btn-primary w-100" type="submit">Rechercher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="row g-2 g-md-3 align-items-end mb-3" action="{{ route('admin.national.jeunes.index') }}">
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Programme</label>
                <select class="form-select" name="programme_id">
                    <option value="">Tous</option>
                    @foreach($programmes as $p)
                        <option value="{{ $p->programme_id }}" @selected((string)request('programme_id')===(string)$p->programme_id)>{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Catégorie</label>
                <select class="form-select" name="categorie_id">
                    <option value="">Toutes</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->categorie_id }}" @selected((string)request('categorie_id')===(string)$c->categorie_id)>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Genre</label>
                <select class="form-select" name="genre">
                    <option value="">Tous</option>
                    <option value="homme" @selected(request('genre')==='homme')>Homme</option>
                    <option value="femme" @selected(request('genre')==='femme')>Femme</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Niveau d'étude</label>
                <select class="form-select" name="niveau_etude">
                    <option value="">Tous</option>
                    @foreach($niveaux as $niv)
                        <option value="{{ $niv }}" @selected(request('niveau_etude')===$niv)>{{ $niv }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Ville</label>
                <select class="form-select" name="ville">
                    <option value="">Toutes</option>
                    @foreach($villes as $v)
                        <option value="{{ $v }}" @selected(request('ville')===$v)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label mb-1">Disponibilité</label>
                <input type="text" class="form-control" name="disponibilite" value="{{ request('disponibilite') }}" placeholder="Ex: Immédiate">
            </div>
        </form>

        <div id="jeunesTableContainerNat">
            @include('pages.admins.national.jeunes.partials.table', ['jeunes' => $jeunes])
        </div>
    </div>
</div>

@push('styles')
<style>
.brand-title-container {
    position: relative;
    display: inline-block;
}

.brand-title {
    font-size: 2.25rem; /* plus discret */
    font-weight: 800;
    margin: 0;
    background: linear-gradient(90deg, #0d6efd, #20c997, #ffc107, #fd7e14, #0d6efd);
    background-size: 200% 100%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 6s linear infinite; /* mouvement doux sans tremblement */
    letter-spacing: 0.5px;
}

.brand-underline {
    position: absolute;
    left: 0;
    right: 0;
    bottom: -6px;
    height: 3px;
    background: linear-gradient(90deg, rgba(13,110,253,0) 0%, rgba(13,110,253,1) 20%, rgba(32,201,151,1) 50%, rgba(253,126,20,1) 80%, rgba(13,110,253,0) 100%);
    background-size: 200% 100%;
    border-radius: 2px;
    animation: underlineSweep 4.5s ease-in-out infinite;
}

@keyframes shimmer {
    0% { background-position: 0% 0%; }
    100% { background-position: 200% 0%; }
}

@keyframes underlineSweep {
    0%, 100% { background-position: 0% 0%; opacity: 0.7; }
    50% { background-position: 200% 0%; opacity: 1; }
}

@media (max-width: 768px) {
    .brand-title { font-size: 1.75rem; }
}
</style>
@endpush

@push('scripts')
<script>
    (function() {
        const container = document.getElementById('jeunesTableContainerNat');

        function fetchTable(params) {
            const url = new URL(`{{ route('admin.national.jeunes.table') }}`, window.location.origin);
            for (const [k, v] of new URLSearchParams(params)) {
                url.searchParams.set(k, v);
            }
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }})
                .then(r => r.json())
                .then(data => {
                    container.innerHTML = data.html;
                    bindEvents();
                });
        }

        function bindEvents() {
            container.querySelectorAll('.pagination a').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchTable(this.search);
                });
            });
        }

        const forms = document.querySelectorAll('form[action="{{ route('admin.national.jeunes.index') }}"]');
        forms.forEach(form => {
            form.addEventListener('change', function() {
                fetchTable(new URLSearchParams(new FormData(form)).toString());
            });
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchTable(new URLSearchParams(new FormData(form)).toString());
            });
        });

        bindEvents();
    })();
</script>
@endpush
@endsection
