@extends('pages.admins.local.layout.app')

@section('title', 'Jeunes – Admin local')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="mb-3">
            <div class="bg-white border rounded p-3 p-md-4">
                <div class="row g-2 g-md-3 align-items-center">
                    <div class="col-12 col-md-5">
                        @php
                            $programmeNom = $admin->programme?->nom ?? '';
                            $programmeNomNet = preg_replace('/^Programme\s+(de|du|des)\s+/i', '', $programmeNom);
                        @endphp
                        <div class="brand-title-container">
                            <h1 class="brand-title">GoTeach {{ $programmeNomNet }}</h1>
                            <span class="brand-underline"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <form method="GET" class="w-100" action="{{ route('admin.local.jeunes.index') }}">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="search" class="form-control border-start-0" name="search" value="{{ request('search') }}" placeholder="Rechercher (nom, email, ville, niveau)…">
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-3 text-md-end">
                        <a href="{{ route('admin.local.jeunes.create') }}" class="btn btn-dark w-100 w-md-auto">
                            <i class="fa fa-plus me-1"></i> Ajouter un jeune
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="row g-2 g-md-3 align-items-end mb-3" action="{{ route('admin.local.jeunes.index') }}">
            <div class="col-6 col-md-3">
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
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Ville</label>
                <select class="form-select" name="ville">
                    <option value="">Toutes</option>
                    @foreach($villes as $v)
                        <option value="{{ $v }}" @selected(request('ville')===$v)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div id="jeunesTableContainer">
            @include('pages.admins.local.jeunes.partials.table', ['jeunes' => $jeunes, 'admin' => $admin])
        </div>
    </div>
</div>

<style>
@media (max-width: 576px) {
    table.table thead { display: none; }
    table.table tbody tr { display: block; background: #fff; margin-bottom: .75rem; border-radius: .5rem; padding: .75rem; }
    table.table tbody td { display: flex; justify-content: space-between; padding: .25rem 0; border: 0; }
}
</style>

@push('scripts')
<script>
    (function() {
        const container = document.getElementById('jeunesTableContainer');

        function fetchTable(params) {
            const url = new URL(`{{ route('admin.local.jeunes.table') }}`, window.location.origin);
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
            // Pagination links
            container.querySelectorAll('.pagination a').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchTable(this.search);
                });
            });

            // Update status
            container.querySelectorAll('.js-update-status').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const statut = this.getAttribute('data-statut');
                    fetch(`{{ url('/admins/local/jeunes') }}/${id}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ statut })
                    }).then(r => r.json()).then(() => {
                        // refresh current page
                        fetchTable(window.location.search);
                    });
                });
            });
        }

        // Filters/sorts form
        const forms = document.querySelectorAll('form[action="{{ route('admin.local.jeunes.index') }}"]');
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
<style>
/* Animation harmonisée, rythme différent pour l'admin local */
.brand-title-container { position: relative; display: inline-block; }
.brand-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    background: linear-gradient(90deg, #0d6efd, #20c997, #ffc107, #fd7e14, #0d6efd);
    background-size: 250% 100%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: shimmerLocal 8s ease-in-out infinite; /* plus lent et plus fluide */
    letter-spacing: .5px;
}
.brand-underline {
    position: absolute; left: 0; right: 0; bottom: -6px; height: 3px;
    background: linear-gradient(90deg, rgba(13,110,253,0) 0%, rgba(13,110,253,1) 20%, rgba(32,201,151,1) 50%, rgba(253,126,20,1) 80%, rgba(13,110,253,0) 100%);
    background-size: 250% 100%; border-radius: 2px; 
    animation: underlineSweepLocal 6.5s cubic-bezier(.4,0,.2,1) infinite; /* plus lent, easing doux */
    animation-delay: .6s; /* léger décalage pour un effet plus naturel */
}
@keyframes shimmerLocal { 0% { background-position: 0% 0%; } 50% { background-position: 200% 0%; } 100% { background-position: 0% 0%; } }
@keyframes underlineSweepLocal { 0%, 100% { background-position: 0% 0%; opacity: .7; } 50% { background-position: 200% 0%; opacity: 1; } }
</style>
@endpush
@endsection


