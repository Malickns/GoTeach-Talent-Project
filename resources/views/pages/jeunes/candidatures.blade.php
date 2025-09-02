@extends('pages.jeunes.layouts.app')

@section('title', 'GOTeach - Mes Candidatures')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --dhl-red: #d40511;
            --dhl-yellow: #ffcc00;
            --sos-blue: #0066cc;
            --senegal-green: #00a651;
            --primary: #1e293b;
            --secondary: #475569;
            --accent: #3b82f6;
            --light: #ffffff;
            --dark: #0f172a;
            --gray: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --bg-light: #f8fafc;
            --border-light: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: var(--dark);
            line-height: 1.6;
        }

        /* Navigation Bar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 12px 5%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid var(--border-light);
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo i {
            margin-right: 10px;
            font-size: 28px;
            background: linear-gradient(135deg, var(--dhl-red), var(--dhl-yellow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo span {
            background: linear-gradient(135deg, var(--dhl-red), var(--dhl-yellow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .search-container {
            flex: 1;
            max-width: 450px;
            margin: 0 30px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid var(--border-light);
            border-radius: 25px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--sos-blue);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--accent);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark);
            cursor: pointer;
            padding: 8px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .nav-item a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item a:hover {
            color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-item a.active {
            color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.15);
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notifications:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .notifications i {
            font-size: 18px;
            color: var(--secondary);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--dhl-red);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.1);
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Responsive Navigation */
        @media (max-width: 768px) {
            .search-container {
                display: none;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        /* Main Content Styles */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            margin-top: 80px; /* Espace pour le header principal */
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: var(--secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: var(--light);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, var(--sos-blue), var(--accent));
            color: white;
        }

        .stat-icon.en-attente {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
            color: white;
        }

        .stat-icon.retenues {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
        }

        .stat-icon.refusees {
            background: linear-gradient(135deg, var(--danger), #f87171);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--secondary);
            font-weight: 500;
        }

        /* Candidatures List */
        .candidatures-section {
            background: var(--light);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .candidatures-list {
            padding: 0;
        }

        .candidature-item {
            padding: 30px;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .candidature-item:hover {
            background: var(--bg-light);
        }

        .candidature-item:last-child {
            border-bottom: none;
        }

        .candidature-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .candidature-info {
            flex: 1;
        }

        .candidature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .candidature-company {
            font-size: 1.1rem;
            color: var(--sos-blue);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .candidature-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--secondary);
        }

        .detail-item i {
            color: var(--sos-blue);
            width: 20px;
        }

        .candidature-status {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-en-attente {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-retenu {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .status-entretien {
            background: rgba(139, 92, 246, 0.1);
            color: #7c3aed;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .status-embauche {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-rejete {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-annulee {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .candidature-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--sos-blue), var(--accent));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--sos-blue);
            border: 2px solid var(--sos-blue);
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: var(--sos-blue);
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: white;
            color: var(--danger);
            border: 2px solid var(--danger);
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 48px;
            color: white;
        }

        .empty-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-description {
            font-size: 1.1rem;
            color: var(--secondary);
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 30px;
            border-top: 1px solid var(--border-light);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-link {
            padding: 10px 16px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .page-link:hover {
            background: var(--sos-blue);
            color: white;
            border-color: var(--sos-blue);
        }

        .page-link.active {
            background: var(--sos-blue);
            color: white;
            border-color: var(--sos-blue);
        }

        .page-link.disabled {
            color: var(--secondary);
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 20px 15px;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .candidature-header {
                flex-direction: column;
                gap: 15px;
            }

            .candidature-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .candidature-actions {
                flex-direction: column;
            }

            .btn-primary, .btn-secondary, .btn-danger {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-container">
        @if($candidatures->count() > 0)
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-number">{{ $candidatures->total() }}</div>
                    <div class="stat-label">Total des candidatures</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon en-attente">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">{{ $candidatures->where('statut', 'en_attente')->count() }}</div>
                    <div class="stat-label">En attente</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon retenues">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ $candidatures->whereIn('statut', ['retenu', 'entretien_programme', 'entretien_effectue'])->count() }}</div>
                    <div class="stat-label">Retenues</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon refusees">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-number">{{ $candidatures->whereIn('statut', ['rejete', 'annulee'])->count() }}</div>
                    <div class="stat-label">Refusées</div>
                </div>
            </div>

            <!-- Liste des candidatures -->
            <div class="candidatures-section">
                <div class="section-header">
                    <h2 class="section-title">Historique des candidatures</h2>
                    <p class="section-subtitle">Retrouvez toutes vos postulations et leur statut actuel</p>
                </div>
                
                <div class="candidatures-list">
                    @foreach($candidatures as $candidature)
                        <div class="candidature-item">
                            <div class="candidature-header">
                                <div class="candidature-info">
                                    <div class="candidature-title">
                                        <i class="fas fa-briefcase"></i>
                                                {{ $candidature->offreEmplois->titre }}
                                    </div>
                                    <div class="candidature-company">
                                        <i class="fas fa-building"></i>
                                        {{ $candidature->offreEmplois->employeur->nom_entreprise ?? 'Entreprise non spécifiée' }}
                                    </div>
                                    
                                    <div class="candidature-details">
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $candidature->offreEmplois->ville_travail ?? 'Localisation non spécifiée' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Postulé le {{ $candidature->date_postulation ? $candidature->date_postulation->format('d/m/Y') : 'Date inconnue' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-file-contract"></i>
                                            <span>{{ $candidature->offreEmplois->type_contrat ?? 'Type non spécifié' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="candidature-status">
                                    @php
                                        $statusConfig = [
                                            'en_attente' => ['class' => 'status-en-attente', 'label' => 'En attente'],
                                            'retenu' => ['class' => 'status-retenu', 'label' => 'Retenu'],
                                            'entretien_programme' => ['class' => 'status-entretien', 'label' => 'Entretien programmé'],
                                            'entretien_effectue' => ['class' => 'status-entretien', 'label' => 'Entretien effectué'],
                                            'embauche' => ['class' => 'status-embauche', 'label' => 'Embauché'],
                                            'rejete' => ['class' => 'status-rejete', 'label' => 'Refusé'],
                                            'annulee' => ['class' => 'status-annulee', 'label' => 'Annulé']
                                        ];
                                        $status = $statusConfig[$candidature->statut] ?? ['class' => 'status-en-attente', 'label' => ucfirst($candidature->statut)];
                                    @endphp
                                    <span class="status-badge {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                        </div>
                                    </div>

                            @if($candidature->commentaire_employeur)
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                                    <p class="text-sm text-blue-800">
                                        <strong>Message de l'employeur :</strong><br>
                                        {{ $candidature->commentaire_employeur }}
                                            </p>
                                        </div>
                                    @endif
                            
                            <div class="candidature-actions">
                                <a href="{{ route('jeunes.offres.show', $candidature->offreEmplois) }}" class="btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Voir l'offre
                                </a>
                                
                                <a href="{{ route('jeunes.candidatures.show', $candidature) }}" class="btn-secondary">
                                    <i class="fas fa-info-circle"></i>
                                    Détails
                                    </a>
                                    
                                    @if($candidature->statut === 'en_attente')
                                        <form action="{{ route('jeunes.candidatures.annuler', $candidature) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ?')"
                                                class="btn-danger">
                                            <i class="fas fa-times"></i>
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($candidatures->hasPages())
                    <div class="pagination-container">
                        <div class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($candidatures->onFirstPage())
                                <span class="page-link disabled">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $candidatures->previousPageUrl() }}" class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($candidatures->getUrlRange(1, $candidatures->lastPage()) as $page => $url)
                                @if ($page == $candidatures->currentPage())
                                    <span class="page-link active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($candidatures->hasMorePages())
                                <a href="{{ $candidatures->nextPageUrl() }}" class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="page-link disabled">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- État vide -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3 class="empty-title">Aucune candidature</h3>
                <p class="empty-description">Vous n'avez pas encore postulé à des offres d'emploi. Commencez par explorer les offres disponibles et postulez à celles qui vous intéressent.</p>
                <a href="{{ route('jeunes.offres.toutes') }}" class="btn-primary">
                    <i class="fas fa-briefcase"></i>
                    Découvrir les offres
                </a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Recherche dans les candidatures
        const searchBox = document.querySelector('.search-box');
        if (searchBox) {
            searchBox.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const candidatureItems = document.querySelectorAll('.candidature-item');
                
                candidatureItems.forEach(item => {
                    const title = item.querySelector('.candidature-title').textContent.toLowerCase();
                    const company = item.querySelector('.candidature-company').textContent.toLowerCase();
                    
                    if (title.includes(query) || company.includes(query)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Animation des cartes de statistiques
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes de statistiques
        document.querySelectorAll('.stat-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });

        // Animation des éléments de candidature
        document.querySelectorAll('.candidature-item').forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            item.style.transition = 'all 0.6s ease-out';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    </script>
@endsection
