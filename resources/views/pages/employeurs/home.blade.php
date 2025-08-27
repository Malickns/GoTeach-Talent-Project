@extends('pages.employeurs.layouts.app')

@section('title', 'Dashboard Employeur')

@section('content')

    @include('pages.employeurs.partials.home.link')
    @include('pages.employeurs.partials.home.grid')

    @php
        use App\Services\StatistiqueService;
        use Illuminate\Support\Facades\Auth;
        use App\Models\Postulation;

        $stats = StatistiqueService::getStatistiquesEmployeur();
        $recentPostulations = collect();
        $candidatures = collect();
        $offres = collect();
        try {
            $employeurId = Auth::user()?->employeur?->employeur_id;
            if ($employeurId) {
                $recentPostulations = Postulation::with(['jeune.user','offreEmplois.employeur'])
                    ->whereHas('offreEmplois', fn($q) => $q->where('employeur_id', $employeurId))
                    ->orderBy('created_at','desc')
                    ->limit(5)
                    ->get();

                // Pagination principale
                $candidatures = Postulation::with(['jeune.user','offreEmplois'])
                    ->whereHas('offreEmplois', fn($q) => $q->where('employeur_id', $employeurId))
                    ->orderBy('created_at','desc')
                    ->paginate(5, ['*'], 'candidatures_page');

                $offres = \App\Models\OffreEmplois::where('employeur_id', $employeurId)
                    ->orderBy('created_at','desc')
                    ->paginate(5, ['*'], 'offres_page');
            }
        } catch (\Throwable $e) {
            $recentPostulations = collect();
        }
    @endphp

    <div class="main-container">
        <aside class="left-sidebar">
            @include('pages.employeurs.partials.profil.card', ['stats' => $stats])
            @include('pages.employeurs.partials.profil.quick-nav')
        </aside>

        <main class="main-content">
            @include('pages.employeurs.partials.candidatures.nouvelles', ['recentPostulations' => $recentPostulations, 'candidatures' => $candidatures])
            @include('pages.employeurs.partials.offres.dernieres', ['offres' => $offres])
            @include('pages.employeurs.partials.performances.overview', ['stats' => $stats])
        </main>

        <aside class="right-sidebar">
            @include('pages.employeurs.partials.actions_rapides.list')
        </aside>
    </div>

    @include('pages.employeurs.partials.home.script')

@endsection


