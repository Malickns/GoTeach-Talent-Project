<aside class="left-sidebar">
    <div class="sidebar-card profile-card">
        <div class="company-logo"><i class="fas fa-building"></i></div>
        <h3 class="company-name">{{ $employeur->nom_entreprise ?? (Auth::user()->prenom.' '.Auth::user()->nom) }}</h3>
        <p class="company-type">Partenaire Premium</p>
        <ul class="stats-list">
            <li><span>Offres publiées</span><span class="stats-value">{{ $stats['offres_publiees'] ?? 0 }}</span></li>
            <li><span>Candidatures</span><span class="stats-value">{{ $stats['candidatures_recues'] ?? 0 }}</span></li>
            <li><span>Talents contactés</span><span class="stats-value">{{ $stats['talents_contactes'] ?? 0 }}</span></li>
            <li><span>Entretiens</span><span class="stats-value">{{ $stats['entretiens'] ?? 0 }}</span></li>
        </ul>
    </div>
    <div class="sidebar-card">
        <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Navigation Rapide</h4>
        <ul class="quick-nav">
            <li><a href="{{ route('pages.employeurs.dashboard') }}" class="{{ request()->routeIs('pages.employeurs.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i>Tableau de bord</a></li>
            <li><a href="{{ route('offres-emplois.create') }}"><i class="fas fa-plus-circle"></i>Publier une offre</a></li>
            <li><a href="{{ route('employeurs.offres') }}" class="{{ request()->routeIs('employeurs.offres') ? 'active' : '' }}"><i class="fas fa-briefcase"></i>Mes offres</a></li>
            <li><a href="{{ route('employeurs.postulations') }}" class="{{ request()->routeIs('employeurs.postulations') ? 'active' : '' }}"><i class="fas fa-file-alt"></i>Postulations</a></li>
            <li><a href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i>Paramètres</a></li>
        </ul>
    </div>
</aside>


