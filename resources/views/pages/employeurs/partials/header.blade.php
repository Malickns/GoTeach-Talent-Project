<header class="employeur-header">
    <div class="header-content">
        <a href="{{ route('pages.employeurs.dashboard') }}" class="header-title">
            <i class="fas fa-graduation-cap"></i>
            GO<span>Teach</span>
        </a>

        <div class="search-container">
            <input type="text" class="search-box" placeholder="Rechercher des talents...">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>

        <div class="profile-section">
            <div class="notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <img src="https://via.placeholder.com/40x40/4a6fa5/ffffff?text=E" alt="Profile" class="profile-img" id="profileToggle">
            <div class="dropdown-menu" id="profileMenu">
                <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> Mon profil</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-button"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</header>

