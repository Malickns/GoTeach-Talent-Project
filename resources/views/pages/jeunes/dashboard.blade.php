<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTeach - Dashboard Jeune</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1c1f26;
            --secondary: #ffcc00;
            --accent: #005577;
            --light: #ffffff;
            --dark: #0d1117;
            --gray: #6e7a8a;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
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
            padding: 12px 5%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
            margin-right: 10px;
            font-size: 28px;
            color: var(--secondary);
        }

        .logo span {
            color: var(--secondary);
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            margin: 0 25px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f0f2f5;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
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

        .nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 20px;
        }

        .nav-item a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray);
            font-size: 12px;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
            position: relative;
        }

        .nav-item a:hover, .nav-item a.active {
            color: var(--primary);
        }

        .nav-item a.active:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background: var(--secondary);
            border-radius: 50%;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: 15px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--accent);
            object-fit: cover;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
            margin-left: 15px;
        }

        .main-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 5%;
            display: grid;
            grid-template-columns: 280px 1fr 300px;
            gap: 25px;
        }

        .sidebar-card, .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .sidebar-card:hover, .content-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .left-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .profile-card {
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .profile-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .profile-card .user-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 20px auto 15px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .user-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .user-status {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .user-status:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: var(--secondary);
            border-radius: 3px;
        }

        .stats-list {
            list-style: none;
            text-align: left;
            margin-top: 20px;
        }

        .stats-list li {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 14px;
        }

        .stats-list li:last-child {
            border-bottom: none;
        }

        .stats-value {
            color: var(--primary);
            font-weight: 600;
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
            padding: 12px 15px;
            color: var(--gray);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .quick-nav a:hover, .quick-nav a.active {
            background: linear-gradient(90deg, rgba(74, 111, 165, 0.1), rgba(107, 211, 184, 0.1));
            color: var(--primary);
            transform: translateX(5px);
        }

        .quick-nav i {
            margin-right: 12px;
            width: 20px;
            color: var(--primary);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--secondary);
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(74, 111, 165, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 111, 165, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: rgba(74, 111, 165, 0.1);
        }

        .btn-success {
            background: var(--success);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-warning {
            background: var(--warning);
            color: var(--dark);
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .offres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }

        .offre-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .offre-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .offre-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .offre-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .offre-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .offre-company {
            font-size: 14px;
            color: var(--gray);
        }

        .offre-badge {
            background: var(--success);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .offre-details {
            margin-bottom: 15px;
        }

        .offre-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--gray);
        }

        .offre-detail i {
            color: var(--primary);
            width: 16px;
        }

        .offre-description {
            font-size: 14px;
            color: var(--dark);
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .offre-actions {
            display: flex;
            gap: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--gray);
        }

        .right-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .candidature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .candidature-item:last-child {
            border-bottom: none;
        }

        .candidature-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(90deg, rgba(74, 111, 165, 0.1), rgba(107, 211, 184, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            flex-shrink: 0;
            font-size: 18px;
        }

        .candidature-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .candidature-content p {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.4;
        }

        .candidature-status {
            font-size: 12px;
            color: var(--gray);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-en-attente {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-retenue {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-rejetee {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .status-embauche {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 250px 1fr;
            }

            .right-sidebar {
                grid-column: 1 / -1;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 992px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .left-sidebar {
                order: 2;
            }

            .main-content {
                order: 1;
            }

            .right-sidebar {
                order: 3;
            }

            .offres-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 4%;
            }
            
            .search-container {
                display: none;
            }

            .nav-menu {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                z-index: 999;
                border-radius: 0 0 12px 12px;
            }

            .nav-menu.mobile-active {
                display: flex;
            }

            .nav-item {
                width: 100%;
            }

            .nav-item a {
                flex-direction: row;
                justify-content: flex-start;
                gap: 15px;
                padding: 12px 15px;
                border-radius: 8px;
            }

            .nav-item a.active:after {
                display: none;
            }

            .nav-item a.active {
                background: linear-gradient(90deg, rgba(74, 111, 165, 0.1), rgba(107, 211, 184, 0.1));
            }

            .nav-item i {
                margin-bottom: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .profile-section {
                margin-left: auto;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .main-container {
                padding: 0 3%;
                margin-top: 15px;
            }

            .content-card {
                padding: 20px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .offre-actions {
                flex-direction: column;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .content-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .content-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                    <a href="{{ route('jeunes.offres') }}">
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
                    <span class="notification-badge pulse">{{ $candidatures->where('statut', 'en_attente')->count() }}</span>
                </div>
                <img src="https://via.placeholder.com/40x40/4a6fa5/ffffff?text=J" alt="Profile" class="profile-img">
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="main-container">
        <!-- Sidebar Gauche -->
        <aside class="left-sidebar">
            <!-- Profil Jeune -->
            <div class="sidebar-card profile-card">
                <div class="user-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="user-name">{{ $jeune->nom_complet }}</h3>
                <p class="user-status">{{ $jeune->niveau_etude ?? 'Étudiant' }}</p>
                
                <ul class="stats-list">
                    <li>
                        <span>Candidatures</span>
                        <span class="stats-value">{{ $statistiques['total_candidatures'] }}</span>
                    </li>
                    <li>
                        <span>Entretiens</span>
                        <span class="stats-value">{{ $statistiques['entretiens'] }}</span>
                    </li>
                    <li>
                        <span>Emplois obtenus</span>
                        <span class="stats-value">{{ $statistiques['emplois_obtenus'] }}</span>
                    </li>
                    <li>
                        <span>Offres disponibles</span>
                        <span class="stats-value">{{ $statistiques['offres_disponibles'] }}</span>
                    </li>
                </ul>
            </div>

            <!-- Navigation Rapide -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Navigation Rapide</h4>
                <ul class="quick-nav">
                    <li><a href="{{ route('jeunes.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i>Tableau de bord</a></li>
                    <li><a href="{{ route('jeunes.offres') }}"><i class="fas fa-search"></i>Rechercher des offres</a></li>
                    <li><a href="{{ route('jeunes.candidatures') }}"><i class="fas fa-file-alt"></i>Mes candidatures</a></li>
                    <li><a href="{{ route('jeunes.documents') }}"><i class="fas fa-folder"></i>Mes documents</a></li>
                    <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i>Modifier profil</a></li>
                    <li><a href="#"><i class="fas fa-star"></i>Offres favorites</a></li>
                </ul>
            </div>
        </aside>

        <!-- Contenu Principal -->
        <main class="main-content">
            <!-- Statistiques -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Mon Activité
                    </h2>
                    <a href="{{ route('jeunes.candidatures') }}" class="btn-secondary">
                        <i class="fas fa-eye"></i>
                        Voir tout
                    </a>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">{{ $statistiques['total_candidatures'] }}</span>
                        <div class="stat-label">Candidatures</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $statistiques['entretiens'] }}</span>
                        <div class="stat-label">Entretiens</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $statistiques['emplois_obtenus'] }}</span>
                        <div class="stat-label">Emplois Obtenus</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $statistiques['offres_disponibles'] }}</span>
                        <div class="stat-label">Offres Disponibles</div>
                    </div>
                </div>
            </div>

            <!-- Offres Recommandées -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-star"></i>
                        Offres Recommandées
                    </h2>
                    <a href="{{ route('jeunes.offres') }}" class="btn-primary">
                        <i class="fas fa-search"></i>
                        Voir toutes les offres
                    </a>
                </div>
                <div class="offres-grid">
                    @forelse($offresRecommandees as $offre)
                    <div class="offre-card">
                        <div class="offre-header">
                            <div>
                                <h3 class="offre-title">{{ $offre->titre }}</h3>
                                <p class="offre-company">{{ $offre->employeur->nom_entreprise ?? 'Entreprise' }}</p>
                            </div>
                            <span class="offre-badge">{{ $offre->type_contrat }}</span>
                        </div>
                        <div class="offre-details">
                            <div class="offre-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $offre->ville_travail }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>{{ $offre->grade ?? 'À négocier' }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date inconnue' }}</span>
                            </div>
                        </div>
                        <p class="offre-description">{{ Str::limit($offre->description, 150) }}</p>
                        <div class="offre-actions">
                            <a href="{{ route('jeunes.offres.show', $offre) }}" class="btn-secondary">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </a>
                            @if(!$jeune->hasPostule($offre->offre_id))
                            <a href="{{ route('jeunes.offres.candidature', $offre) }}" class="btn-success">
                                <i class="fas fa-paper-plane"></i>
                                Postuler
                            </a>
                            @else
                            <span class="btn-warning">
                                <i class="fas fa-check"></i>
                                Déjà postulé
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="offre-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <i class="fas fa-search" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--gray); margin-bottom: 10px;">Aucune offre recommandée</h3>
                        <p style="color: var(--gray);">Complétez votre profil pour recevoir des offres personnalisées.</p>
                        <a href="{{ route('profile.edit') }}" class="btn-primary" style="margin-top: 20px;">
                            <i class="fas fa-user-edit"></i>
                            Compléter mon profil
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Offres Récentes -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-clock"></i>
                        Offres Récentes
                    </h2>
                    <a href="{{ route('jeunes.offres') }}" class="btn-secondary">
                        <i class="fas fa-list"></i>
                        Voir tout
                    </a>
                </div>
                <div class="offres-grid">
                    @forelse($offresRecentes as $offre)
                    <div class="offre-card">
                        <div class="offre-header">
                            <div>
                                <h3 class="offre-title">{{ $offre->titre }}</h3>
                                <p class="offre-company">{{ $offre->employeur->nom_entreprise ?? 'Entreprise' }}</p>
                            </div>
                            <span class="offre-badge">{{ $offre->type_contrat }}</span>
                        </div>
                        <div class="offre-details">
                            <div class="offre-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $offre->ville_travail }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>{{ $offre->grade ?? 'À négocier' }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date inconnue' }}</span>
                            </div>
                        </div>
                        <p class="offre-description">{{ Str::limit($offre->description, 150) }}</p>
                        <div class="offre-actions">
                            <a href="{{ route('jeunes.offres.show', $offre) }}" class="btn-secondary">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </a>
                            @if(!$jeune->hasPostule($offre->offre_id))
                            <a href="{{ route('jeunes.offres.candidature', $offre) }}" class="btn-success">
                                <i class="fas fa-paper-plane"></i>
                                Postuler
                            </a>
                            @else
                            <span class="btn-warning">
                                <i class="fas fa-check"></i>
                                Déjà postulé
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="offre-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <i class="fas fa-briefcase" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--gray); margin-bottom: 10px;">Aucune offre disponible</h3>
                        <p style="color: var(--gray);">Aucune offre d'emploi n'est actuellement disponible.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>

        <!-- Sidebar Droite -->
        <aside class="right-sidebar">
            <!-- Candidatures Récentes -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Mes Candidatures Récentes</h4>
                @forelse($candidatures as $candidature)
                <div class="candidature-item">
                    <div class="candidature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="candidature-content">
                        <h4>{{ $candidature->offreEmplois->titre }}</h4>
                        <p>{{ $candidature->offreEmplois->employeur->nom_entreprise ?? 'Entreprise' }}</p>
                        <div class="candidature-status">
                            <i class="far fa-clock"></i>
                            {{ $candidature->created_at ? $candidature->created_at->diffForHumans() : 'Date inconnue' }}
                            <span class="status-badge status-{{ $candidature->statut }}">
                                {{ ucfirst(str_replace('_', ' ', $candidature->statut)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 20px; color: var(--gray);">
                    <i class="fas fa-file-alt" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                    <p>Aucune candidature pour le moment</p>
                    <a href="{{ route('jeunes.offres') }}" class="btn-primary" style="margin-top: 10px;">
                        <i class="fas fa-search"></i>
                        Chercher des offres
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Conseils et Astuces -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Conseils du Jour</h4>
                <div class="candidature-item">
                    <div class="candidature-icon" style="background: rgba(40, 167, 69, 0.1); color: var(--success);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="candidature-content">
                        <h4>CV parfait</h4>
                        <p>Assurez-vous que votre CV soit à jour et adapté au poste visé</p>
                    </div>
                </div>
                <div class="candidature-item">
                    <div class="candidature-icon" style="background: rgba(23, 162, 184, 0.1); color: var(--info);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="candidature-content">
                        <h4>Lettre de motivation</h4>
                        <p>Personnalisez votre lettre pour chaque candidature</p>
                    </div>
                </div>
                <div class="candidature-item">
                    <div class="candidature-icon" style="background: rgba(255, 193, 7, 0.1); color: var(--warning);">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="candidature-content">
                        <h4>Réseautage</h4>
                        <p>Participez aux événements GoTeach pour élargir votre réseau</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script>
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
            'Rechercher des offres d\'emploi...',
            'Ex: Développeur PHP',
            'Ex: Marketing Digital',
            'Ex: Comptable',
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

        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = finalValue / 20;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue);
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

        document.querySelectorAll('.offre-card, .stat-item').forEach(card => {
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
                    window.location.href = '{{ route("jeunes.offres") }}?search=' + encodeURIComponent(searchTerm);
                }
            }
        });

        document.querySelector('.profile-img').addEventListener('click', function() {
            window.location.href = '{{ route("profile.edit") }}';
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

        // Afficher les messages de succès/erreur
        @if(session('success'))
            showToast('{{ session("success") }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session("error") }}', 'error');
        @endif
    </script>
</body>
</html>