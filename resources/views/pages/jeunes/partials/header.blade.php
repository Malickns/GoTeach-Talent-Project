<nav class="navbar">
    <div class="navbar-container">
        <a href="#" class="logo">
            <i class="fas fa-graduation-cap"></i>
            GO<span>Teach</span>
        </a>

        <div class="search-container">
            <input type="text" class="search-box" placeholder="Rechercher des offres d'emploi...">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>

        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('jeunes.dashboard') }}" class="active">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/jeunes/offres/toutes" onclick="console.log('Clic sur Offres détecté')">
                    <i class="fas fa-briefcase"></i>
                    <span>Offres</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('jeunes.candidatures') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Candidatures</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('jeunes.documents') }}">
                    <i class="fas fa-folder"></i>
                    <span>Documents</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>
        </ul>

        <div class="profile-section">
            <div class="notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge pulse">{{ ($candidatures ?? collect())->where('statut', 'en_attente')->count() }}</span>
            </div>
            <img src="https://via.placeholder.com/40x40/4a6fa5/ffffff?text=J" alt="Profile" class="profile-img">
        </div>
    </div>
</nav>


