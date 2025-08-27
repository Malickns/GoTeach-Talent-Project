<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOTeach - Dashboard Employeur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1c1f26;       /* Gris-noir profond */
            --secondary: #ffcc00;     /* Jaune DHL */
            --accent: #005577;        /* Bleu pétrole foncé */
            --light: #ffffff;         /* Fond blanc */
            --dark: #0d1117;
            --gray: #6e7a8a;
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

        /* Navigation - Style unique */
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

        /* Container Principal */
        .main-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 5%;
            display: grid;
            grid-template-columns: 280px 1fr 300px;
            gap: 25px;
        }

        /* Cartes avec style unique */
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

        /* Sidebar Gauche */
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

        .profile-card .company-logo {
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

        .company-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .company-type {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .company-type:after {
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

        /* Menu de navigation rapide */
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

        /* Contenu Principal */
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

        /* Actions rapides */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .action-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            font-size: 36px;
            margin-bottom: 15px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .action-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 16px;
        }

        .action-desc {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.5;
        }

        /* Statistiques */
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

        /* Sidebar Droite */
        .right-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
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

        .activity-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .activity-content p {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.4;
        }

        .activity-time {
            font-size: 12px;
            color: var(--gray);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .activity-time i {
            font-size: 12px;
        }

        /* Liste des candidats */
        .candidate-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .candidate-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .candidate-avatar {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            flex-shrink: 0;
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-name {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
            font-size: 16px;
        }

        .candidate-skills {
            font-size: 13px;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .candidate-skills i {
            color: var(--secondary);
            font-size: 12px;
        }

        .candidate-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-view {
            background: rgba(74, 111, 165, 0.1);
            color: var(--primary);
        }

        .btn-view:hover {
            background: rgba(74, 111, 165, 0.2);
        }

        .btn-contact {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            color: white;
            box-shadow: 0 4px 10px rgba(74, 111, 165, 0.3);
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(74, 111, 165, 0.4);
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: rgba(74, 111, 165, 0.1);
            color: var(--primary);
        }

        .badge-secondary {
            background: rgba(255, 126, 95, 0.1);
            color: var(--secondary);
        }

        /* Responsive Design */
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

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
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

            .quick-actions {
                grid-template-columns: 1fr;
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

            .candidate-item {
                flex-direction: column;
                text-align: center;
            }

            .candidate-info {
                text-align: center;
                margin-bottom: 15px;
            }

            .candidate-skills {
                justify-content: center;
            }

            .candidate-actions {
                justify-content: center;
                width: 100%;
            }

            .btn-sm {
                width: 100%;
            }
        }

        /* Animations */
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
                <input type="text" class="search-box" placeholder="Rechercher des talents, compétences...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="active">
                        <i class="fas fa-home"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-users"></i>
                        <span>Talents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-briefcase"></i>
                        <span>Offres</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Événements</span>
                    </a>
                </li>
            </ul>

            <div class="profile-section">
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge pulse">3</span>
                </div>
                <img src="https://via.placeholder.com/40x40/4a6fa5/ffffff?text=E" alt="Profile" class="profile-img">
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
                <h3 class="company-name">{{ Auth::user()->name ?? 'Mon Entreprise' }}</h3>
                <p class="company-type">Partenaire Premium</p>
                
                <ul class="stats-list">
                    <li>
                        <span>Offres publiées</span>
                        <span class="stats-value">{{ \App\Models\OffreEmplois::where('employeur_id', Auth::id())->count() }}</span>
                    </li>
                    <li>
                        <span>Candidatures</span>
                        <span class="stats-value">{{ \App\Models\Postulation::whereHas('offreEmplois', function($q) { $q->where('employeur_id', Auth::id()); })->count() }}</span>
                    </li>
                    <li>
                        <span>Talents contactés</span>
                        <span class="stats-value">156</span>
                    </li>
                    <li>
                        <span>Entretiens</span>
                        <span class="stats-value">8</span>
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
            <!-- Actions Rapides -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="quick-actions">
                    <div class="action-card" onclick="window.location.href='{{ route('offres-emplois.create') }}'">
                        <div class="action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4 class="action-title">Publier une Offre</h4>
                        <p class="action-desc">Créez une nouvelle opportunité d'emploi</p>
                    </div>
                    <div class="action-card" onclick="openModal('searchTalents')">
                        <div class="action-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 class="action-title">Rechercher Talents</h4>
                        <p class="action-desc">Explorez notre base de talents qualifiés</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='{{ route('offres-emplois.index') }}'">
                        <div class="action-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4 class="action-title">Gérer mes Offres</h4>
                        <p class="action-desc">Modifiez ou archivez vos offres</p>
                    </div>
                    <div class="action-card" onclick="openModal('viewApplications')">
                        <div class="action-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4 class="action-title">Candidatures</h4>
                        <p class="action-desc">Consultez les nouvelles postulations</p>
                    </div>
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
                        <span class="stat-number">156</span>
                        <div class="stat-label">Vues Profil</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\Postulation::whereHas('offreEmplois', function($q) { $q->where('employeur_id', Auth::id()); })->count() }}</span>
                        <div class="stat-label">Candidatures</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\OffreEmplois::where('employeur_id', Auth::id())->count() }}</span>
                        <div class="stat-label">Offres Publiées</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">8</span>
                        <div class="stat-label">Entretiens</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">92%</span>
                        <div class="stat-label">Taux de Réponse</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">15</span>
                        <div class="stat-label">Embauches</div>
                    </div>
                </div>
            </div>

            <!-- Candidats Récents -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-user-graduate"></i>
                        Nouveaux Talents
                    </h2>
                    <a href="#" class="btn-primary">
                        <i class="fas fa-eye"></i>
                        Voir Tous
                    </a>
                </div>
                <div class="candidates-list">
                    <div class="candidate-item">
                        <div class="candidate-avatar">AM</div>
                        <div class="candidate-info">
                            <h4 class="candidate-name">Aminata Diallo</h4>
                            <p class="candidate-skills">
                                <i class="fas fa-code"></i> Développement Web • PHP, JavaScript
                                <span class="badge badge-primary">Disponible</span>
                            </p>
                        </div>
                        <div class="candidate-actions">
                            <button class="btn-sm btn-view">Voir Profil</button>
                            <button class="btn-sm btn-contact">Contacter</button>
                        </div>
                    </div>
                    <div class="candidate-item">
                        <div class="candidate-avatar">IS</div>
                        <div class="candidate-info">
                            <h4 class="candidate-name">Ibrahima Sarr</h4>
                            <p class="candidate-skills">
                                <i class="fas fa-bullhorn"></i> Marketing Digital • SEO, Réseaux Sociaux
                                <span class="badge badge-secondary">Nouveau</span>
                            </p>
                        </div>
                        <div class="candidate-actions">
                            <button class="btn-sm btn-view">Voir Profil</button>
                            <button class="btn-sm btn-contact">Contacter</button>
                        </div>
                    </div>
                    <div class="candidate-item">
                        <div class="candidate-avatar">FN</div>
                        <div class="candidate-info">
                            <h4 class="candidate-name">Fatou Ndiaye</h4>
                            <p class="candidate-skills">
                                <i class="fas fa-calculator"></i> Comptabilité • Gestion, Finance
                            </p>
                        </div>
                        <div class="candidate-actions">
                            <button class="btn-sm btn-view">Voir Profil</button>
                            <button class="btn-sm btn-contact">Contacter</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Sidebar Droite -->
        <aside class="right-sidebar">
            <!-- Activité Récente -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Activité Récente</h4>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Nouveau candidat</h4>
                        <p>Aminata a postulé pour votre offre "Développeur Web Senior"</p>
                        <div class="activity-time">
                            <i class="far fa-clock"></i> Il y a 2h
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Entretien confirmé</h4>
                        <p>Entretien avec Ibrahima Sarr prévu demain à 14h</p>
                        <div class="activity-time">
                            <i class="far fa-clock"></i> Il y a 4h
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Profil consulté</h4>
                        <p>Fatou a consulté votre profil entreprise</p>
                        <div class="activity-time">
                            <i class="far fa-clock"></i> Il y a 6h
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Nouveau favori</h4>
                        <p>Votre offre "Marketing Digital" a été ajoutée aux favoris</p>
                        <div class="activity-time">
                            <i class="far fa-clock"></i> Hier
                        </div>
                    </div>
                </div>
            </div>

            <!-- Événements GoTeach -->
            <div class="sidebar-card">
                <h4 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">Événements à venir</h4>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(255, 126, 95, 0.1); color: var(--secondary);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Forum de l'emploi</h4>
                        <p>Rencontrez des talents qualifiés en recherche d'opportunités</p>
                        <div class="activity-time">
                            <i class="far fa-calendar"></i> 15 Mars 2024
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(107, 211, 184, 0.1); color: var(--accent);">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Webinaire Recrutement</h4>
                        <p>Découvrez les meilleures pratiques pour attirer les meilleurs talents</p>
                        <div class="activity-time">
                            <i class="far fa-calendar"></i> 20 Mars 2024
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(74, 111, 165, 0.1); color: var(--primary);">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Session Networking</h4>
                        <p>Échangez avec d'autres employeurs et partagez vos expériences</p>
                        <div class="activity-time">
                            <i class="far fa-calendar"></i> 25 Mars 2024
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script>
        // Fonction pour ouvrir les modals
        function openModal(modalType) {
            console.log('Ouverture du modal:', modalType);
            alert(`Fonctionnalité ${modalType} sera implémentée`);
        }

        // Toggle menu mobile
        function toggleMobileMenu() {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.toggle('mobile-active');
        }

        // Animation au scroll
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 20) {
                navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
            }
        });

        // Effet de typing pour la recherche
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

        // Notification counter animation
        function updateNotificationCount(count) {
            const badge = document.querySelector('.notification-badge');
            badge.textContent = count;
            badge.classList.add('pulse');
            setTimeout(() => {
                badge.classList.remove('pulse');
            }, 1500);
        }

        // Simulated real-time updates
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

        // Observer pour déclencher l'animation des stats quand elles sont visibles
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

        // Hover effects for cards
        document.querySelectorAll('.action-card, .candidate-item, .stat-item').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Search functionality placeholder
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                if (searchTerm.trim() !== '') {
                    alert(`Recherche pour: "${searchTerm}" - Cette fonctionnalité sera implémentée`);
                }
            }
        });

        // Profile menu toggle
        document.querySelector('.profile-img').addEventListener('click', function() {
            alert('Menu profil sera implémenté');
        });

        // Toast notifications system
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

        // Simulation d'événements en temps réel
        setTimeout(() => {
            showToast('Nouveau candidat pour votre offre "Développeur Web"', 'success');
        }, 5000);

        setTimeout(() => {
            showToast('Rappel: Entretien prévu demain à 14h', 'info');
        }, 10000);
    </script>
</body>
</html>