<?php
use App\Services\StatistiqueService;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulation;

$stats = StatistiqueService::getStatistiquesEmployeur();

// Nouvelles candidatures de l'employeur connecté (5 dernières)
$recentPostulations = collect();
try {
    $employeurId = Auth::user()?->employeur?->employeur_id;
    if ($employeurId) {
        $recentPostulations = Postulation::with(['jeune.user','offreEmplois.employeur'])
            ->whereHas('offreEmplois', fn($q) => $q->where('employeur_id', $employeurId))
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();
    }
} catch (\Throwable $e) {
    $recentPostulations = collect();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTeach - Dashboard Employeur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1f2430;           /* plus doux */
            --secondary: #ffd95a;        /* jaune adouci */
            --accent: #3a6ea5;           /* bleu feutré */
            --light: #ffffff;
            --dark: #11151c;
            --gray: #7a8796;
            --muted: #eef2f6;            /* fond subtil */
        }

        * {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            color: var(--dark);
            line-height: 1.6;
        }

        .navbar {
            background: white;
            padding: 10px 5%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .logo i {
            margin-right: 8px;
            font-size: 22px;
            color: var(--accent);
        }

        .logo span {
            color: var(--secondary);
        }

        /* Navigation header */
        .nav-left { display: flex; align-items: center; gap: 18px; }
        .nav-center { flex: 1; display: flex; align-items: center; justify-content: center; }
        .nav-right { display: flex; align-items: center; gap: 14px; }
        .header-nav { display: flex; align-items: center; gap: 14px; }
        .header-link { color: var(--gray); text-decoration: none; font-weight: 600; font-size: 13px; padding: 8px 10px; border-radius: 8px; transition: background .2s ease, color .2s ease; }
        .header-link:hover, .header-link.active { background: #f5f7fa; color: var(--primary); }

        .search-container {
            min-width: 320px;
            max-width: 420px;
            margin: 0 10px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 10px 40px 10px 16px;
            border: none;
            border-radius: 30px;
            font-size: 13px;
            transition: all 0.3s ease;
            background: #f0f2f5;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .search-box:focus {
            outline: none;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 0 3px rgba(74, 111, 165, 0.2);
        }

        .search-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 16px;
            color: var(--gray);
            cursor: pointer;
            padding: 6px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: #f0f2f5;
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: var(--primary);
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Profile dropdown */
        .profile-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--accent);
            object-fit: cover;
            transition: transform 0.2s ease;
        }
        .profile-img:hover { transform: scale(1.03); }
        .dropdown-menu {
            position: absolute; top: 48px; right: 0; background: #fff; border: 1px solid #eceff3; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); min-width: 200px; padding: 8px 0; display: none; z-index: 1001;
        }
        .dropdown-menu.show { display: block; }
        .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; color: var(--primary); text-decoration: none; font-size: 14px; }
        .dropdown-item:hover { background: #f5f7fa; }
        .dropdown-divider { height: 1px; background: #eceff3; margin: 6px 0; }
        .dropdown-button { background: none; border: none; width: 100%; text-align: left; padding: 10px 14px; color: #b02a37; font-size: 14px; cursor: pointer; }
        .dropdown-button:hover { background: #fdecea; }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: #f0f2f5;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--accent);
        }

        .user-info {
            display: none;
        }

        @media (min-width: 768px) {
            .user-info {
                display: block;
            }
        }

        .main-container {
            display: grid;
            grid-template-columns: 260px 1fr 280px;
            gap: 20px;
            max-width: 1200px;
            margin: 22px auto;
            padding: 0 20px;
        }

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 280px 1fr;
            }
            .right-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 0 15px;
            }
            .left-sidebar {
                display: none;
            }
        }

        .sidebar-card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            margin-bottom: 16px;
            border: 1px solid #eceff3;
        }

        .profile-card {
            text-align: center;
        }

        .company-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8eef6, #f4f7fb);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 22px;
            color: var(--accent);
        }

        .company-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .company-type {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .stats-list {
            list-style: none;
            text-align: left;
        }

        .stats-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .stats-list li:last-child {
            border-bottom: none;
        }

        .stats-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 18px;
        }

        .quick-nav {
            list-style: none;
        }

        .quick-nav li {
            margin-bottom: 8px;
        }

        .quick-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--gray);
            transition: all 0.3s ease;
        }

        .quick-nav a:hover,
        .quick-nav a.active {
            background: var(--secondary);
            color: var(--primary);
        }

        .quick-nav i {
            width: 20px;
            text-align: center;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            border: 1px solid #eceff3;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--secondary);
        }

        .btn-secondary {
            background: #f5f7fa;
            color: var(--primary);
            padding: 8px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-secondary:hover {
            background: var(--secondary);
            color: var(--primary);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .action-card {
            background: #fafbfd;
            border-radius: 10px;
            padding: 14px;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e6ebf1;
            display: grid;
            grid-template-columns: 36px 1fr;
            align-items: center;
            gap: 10px;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: #dfe6ee;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #f1f5fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--accent);
        }

        .action-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .action-desc {
            color: var(--gray);
            font-size: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 16px;
            background: #fafbfd;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: 1px solid #e6ebf1;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .stat-label {
            color: var(--gray);
            font-size: 14px;
            font-weight: 600;
        }

        /* Quick actions styled like stats */
        .stat-action { display: grid; grid-template-columns: 28px 1fr 14px; align-items: center; gap: 10px; cursor: pointer; }
        .stat-action:hover { background: #f9fbfd; }
        .action-icon.small { width: 28px; height: 28px; border-radius: 6px; font-size: 14px; }
        .stat-text { font-weight: 700; color: var(--primary); font-size: 14px; }
        .chevron { color: #aab4c2; font-size: 12px; }

        .candidates-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .candidate-item {
            display: grid;
            grid-template-columns: 48px 1fr auto;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: #ffffff;
            border: 1px solid #e6ebf1;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .candidate-item:hover {
            background: #fafbfd;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .candidate-avatar {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #f1f5fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--accent);
            font-size: 16px;
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .candidate-skills {
            color: var(--gray);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #e6ebf1;
        }

        .badge-primary {
            background: #fff7d6;
            color: #7a5b00;
        }

        .badge-secondary {
            background: #eef2f6;
            color: #6a7687;
        }

        .candidate-actions {
            display: flex;
            gap: 6px;
        }

        .btn-sm {
            padding: 6px 10px;
            border-radius: 16px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid #e6ebf1;
            background: #fff;
            color: var(--primary);
        }

        .btn-view {
            background: #f5f7fa;
            color: var(--primary);
        }

        .btn-view:hover {
            background: #ffd700;
        }

        .btn-contact {
            background: var(--accent);
            color: white;
            border-color: transparent;
        }

        .btn-contact:hover {
            background: #004466;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 16px;
        }

        .activity-content h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .activity-content p {
            color: var(--gray);
            font-size: 13px;
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--secondary);
            color: var(--primary);
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #ffd700;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-container {
                margin: 0;
                max-width: 100%;
            }
            
            .nav-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="nav-left">
                <a href="{{ route('pages.employeurs.dashboard') }}" class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    GO<span>Teach</span>
                </a>
                <div class="header-nav">
                    <a href="{{ route('pages.employeurs.dashboard') }}" class="header-link active">Accueil</a>
                    <a href="#" class="header-link">Talents</a>
                    <a href="{{ route('offres-emplois.index') }}" class="header-link">Mes offres</a>
                    <a href="#" class="header-link">Messages</a>
                </div>
            </div>
            <div class="nav-center">
                <div class="search-container">
                    <input type="text" class="search-box" placeholder="Rechercher des talents...">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="nav-right" style="position: relative;">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="profile-section">
                    <img src="https://via.placeholder.com/40x40/4a6fa5/ffffff?text={{ urlencode(substr(Auth::user()->prenom ?? 'E', 0, 1)) }}" alt="Profile" class="profile-img" id="profileToggle">
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
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="main-container">
        <!-- Sidebar Gauche -->
        <aside class="left-sidebar">
            <!-- Profil Entreprise -->
            <div class="sidebar-card profile-card">
                <div class="company-logo">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="company-name">{{ Auth::user()->prenom ?? 'Mon Entreprise' }} {{ Auth::user()->nom ?? '' }}</h3>
                <p class="company-type">Partenaire Premium</p>
                
                <ul class="stats-list">
                    <li>
                        <span>Offres publiées</span>
                        <span class="stats-value">{{ $stats['offres_publiees'] }}</span>
                    </li>
                    <li>
                        <span>Candidatures</span>
                        <span class="stats-value">{{ $stats['candidatures_recues'] }}</span>
                    </li>
                    <li>
                        <span>Talents contactés</span>
                        <span class="stats-value">{{ $stats['talents_contactes'] }}</span>
                    </li>
                    <li>
                        <span>Entretiens</span>
                        <span class="stats-value">{{ $stats['entretiens'] }}</span>
                    </li>
                </ul>
            </div>

            <!-- Navigation Rapide -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Navigation Rapide</h4>
                <ul class="quick-nav">
                    <li><a href="{{ route('dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i>Tableau de bord</a></li>
                    <li><a href="{{ route('offres-emplois.create') }}"><i class="fas fa-plus-circle"></i>Publier une offre</a></li>
                    <li><a href="{{ route('offres-emplois.index') }}"><i class="fas fa-briefcase"></i>Mes offres</a></li>
                    <li><a href="#"><i class="fas fa-search"></i>Rechercher talents</a></li>
                    <li><a href="#"><i class="fas fa-star"></i>Talents favoris</a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i>Statistiques</a></li>
                    <li><a href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i>Paramètres</a></li>
                </ul>
            </div>
        </aside>

        <!-- Contenu Principal -->
        <main class="main-content">
            <!-- Nouvelles Candidatures (mise en avant, épuré) -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-user-check"></i>
                        Nouvelles candidatures
                    </h2>
                    <a href="#" class="btn-secondary" onclick="employeurDashboard.showToast('Tous les candidats (à venir)','info'); return false;"><i class="fas fa-users"></i> Voir toutes</a>
                </div>

                <div class="candidates-list" id="new-applications-list" style="gap: 10px;">
                    @forelse($recentPostulations as $postulation)
                        <?php
                            $initials = substr($postulation->jeune?->user?->prenom ?? 'C', 0, 1) . substr($postulation->jeune?->user?->nom ?? 'N', 0, 1);
                            $nom = trim(($postulation->jeune?->user?->prenom ?? '') . ' ' . ($postulation->jeune?->user?->nom ?? '')) ?: 'Candidat';
                            $offreTitre = $postulation->offreEmplois?->titre ?? 'Offre inconnue';
                            $dateStr = optional($postulation->created_at)->diffForHumans();
                            $statut = $postulation->statut;
                        ?>
                        <div class="candidate-item" data-postulation-id="{{ $postulation->postulation_id }}" style="grid-template-columns: 40px 1fr auto;">
                            <div class="candidate-avatar" style="width:40px;height:40px;">
                                <i class="fas fa-user" style="font-size:14px;color:var(--accent);"></i>
                            </div>
                            <div class="candidate-info">
                                <h4 class="candidate-name" style="margin-bottom:2px;">{{ $nom }}</h4>
                                <p class="candidate-skills" style="gap:6px;">
                                    <i class="fas fa-briefcase" style="font-size:12px;color:var(--gray);"></i> <span style="color:var(--primary);font-weight:600;">{{ $offreTitre }}</span>
                                    <span class="badge {{ in_array($statut, ['en_attente','en_revue']) ? 'badge-primary' : 'badge-secondary' }}" style="text-transform:capitalize;">{{ str_replace('_',' ', $statut) }}</span>
                                    <span style="color: var(--gray); font-size:12px;">
                                        <i class="far fa-clock"></i> {{ $dateStr }}
                                    </span>
                                </p>
                            </div>
                            <div class="candidate-actions">
                                <button class="btn-sm btn-view" data-action="view-candidature"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    @empty
                        <div class="candidate-item" style="justify-content:center;">
                            <div class="candidate-info" style="text-align:center;">
                                <h4 class="candidate-name">Aucune nouvelle candidature</h4>
                                <p class="candidate-skills"><i class="fas fa-info-circle"></i> Encouragez les talents en publiant une nouvelle offre.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Statistiques -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Aperçu des Performances
                    </h2>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-chart-bar"></i>
                        Voir plus
                    </a>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['vues_profil'] }}</span>
                        <div class="stat-label">Vues Profil</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['candidatures_recues'] }}</span>
                        <div class="stat-label">Candidatures</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['offres_publiees'] }}</span>
                        <div class="stat-label">Offres Publiées</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['entretiens'] }}</span>
                        <div class="stat-label">Entretiens</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['taux_reponse'] }}%</span>
                        <div class="stat-label">Taux de Réponse</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['embauches'] }}</span>
                        <div class="stat-label">Embauches</div>
                    </div>
                </div>
            </div>

            
        </main>

        <!-- Sidebar Droite -->
        <aside class="right-sidebar">
            <!-- Actions Rapides (style tuiles Aperçu des Performances) -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 12px; color: var(--dark); font-size: 16px;">Actions rapides</h4>
                <div class="stats-grid" style="grid-template-columns: 1fr; gap: 10px;">
                    <div class="stat-item stat-action" onclick="window.location.href='{{ route('offres-emplois.create') }}'">
                        <div class="action-icon small"><i class="fas fa-plus"></i></div>
                        <div class="stat-text">Publier une offre</div>
                        <div class="chevron"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="stat-item stat-action" onclick="employeurDashboard.showToast('Candidatures (à venir)','info')">
                        <div class="action-icon small"><i class="fas fa-file-alt"></i></div>
                        <div class="stat-text">Candidatures</div>
                        <div class="chevron"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="stat-item stat-action" onclick="openModal('searchTalents')">
                        <div class="action-icon small"><i class="fas fa-search"></i></div>
                        <div class="stat-text">Rechercher talents</div>
                        <div class="chevron"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="stat-item stat-action" onclick="window.location.href='{{ route('offres-emplois.index') }}'">
                        <div class="action-icon small"><i class="fas fa-briefcase"></i></div>
                        <div class="stat-text">Gérer mes offres</div>
                        <div class="chevron"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script>
        function openModal(modalType) {
            console.log('Ouverture du modal:', modalType);
            alert(`Fonctionnalité ${modalType} sera implémentée`);
        }

        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('mobile-active');
        }

        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 20) {
                navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
            }
        });

        const searchBox = document.querySelector('.search-box');
        const placeholders = [
            'Rechercher des talents, compétences...',
            'Ex: Développeur PHP expérimenté',
            'Ex: Responsable Marketing Digital',
            'Ex: Comptable junior',
            'Ex: Designer UI/UX'
        ];
        let currentPlaceholder = 0;

        setInterval(() => {
            currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
            searchBox.placeholder = placeholders[currentPlaceholder];
        }, 3000);

        function updateNotificationCount(count) {
            const badge = document.querySelector('.notification-badge');
            badge.textContent = count;
            badge.classList.add('pulse');
            setTimeout(() => {
                badge.classList.remove('pulse');
            }, 1500);
        }

        setInterval(() => {
            const randomCount = Math.floor(Math.random() * 5) + 1;
            updateNotificationCount(randomCount);
        }, 30000);

        // Interactive stats animation
        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = finalValue / 20;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue + (stat.textContent.includes('%') ? '%' : '');
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('%') ? '%' : '');
                    }
                }, 50);
            });
        }

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const statsGrid = document.querySelector('.stats-grid');
            if (statsGrid) {
                statsObserver.observe(statsGrid);
            }
        });

        document.querySelectorAll('.action-card, .candidate-item, .stat-item').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                if (searchTerm.trim() !== '') {
                    alert(`Recherche pour: "${searchTerm}" - Cette fonctionnalité sera implémentée`);
                }
            }
        });

        document.querySelector('.profile-img').addEventListener('click', function() {
            alert('Menu profil sera implémenté');
        });

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                transform: translateX(120%);
                transition: transform 0.3s ease;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                toast.style.transform = 'translateX(120%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        setTimeout(() => {
            showToast('Nouveau candidat pour votre offre "Développeur Web"', 'success');
        }, 5000);

        setTimeout(() => {
            showToast('Rappel: Entretien prévu demain à 14h', 'info');
        }, 10000);
    </script>
</body>
</html> 