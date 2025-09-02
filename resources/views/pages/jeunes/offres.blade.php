@extends('pages.jeunes.layouts.app')

@section('title', 'GOTeach - Toutes les Offres')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            padding: 12px 45px 12px 20px;
            border: 2px solid var(--border-light);
            border-radius: 40px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--sos-blue);
            cursor: pointer;
            font-size: 16px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 6px;
        }

        .nav-item a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--secondary);
            font-size: 11px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px 14px;
            border-radius: 14px;
            position: relative;
            min-width: 70px;
        }

        .nav-item a:hover {
            color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .nav-item a.active {
            color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.15);
        }

        .nav-item a.active:after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background: var(--dhl-yellow);
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(255, 204, 0, 0.5);
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: 16px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
            padding: 6px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notifications:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--dhl-yellow);
            color: var(--dark);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(255, 204, 0, 0.4);
        }

        .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--sos-blue);
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.1);
            box-shadow: 0 3px 15px rgba(59, 130, 246, 0.3);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--sos-blue);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        /* Responsive Navigation */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-container {
                margin: 0;
                max-width: 100%;
            }
            
            .nav-menu {
                gap: 4px;
            }
            
            .nav-item a {
                min-width: 70px;
                padding: 10px 12px;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 5%;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(0, 102, 204, 0.1));
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%230066cc" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .page-title {
            font-size: 48px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .page-subtitle {
            font-size: 18px;
            color: var(--secondary);
            position: relative;
            z-index: 1;
        }

        .stats-banner {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: var(--sos-blue);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filters Section */
        .filters-section {
            background: var(--light);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-light);
        }

        .filters-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filters-title i {
            color: var(--dhl-yellow);
            font-size: 26px;
        }

        .clear-filters {
            background: none;
            border: 2px solid var(--danger);
            color: var(--danger);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .clear-filters:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-input {
            padding: 12px 16px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .filter-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--sos-blue);
        }

        .filter-checkbox span {
            font-size: 14px;
            color: var(--dark);
            font-weight: 500;
        }

        .filters-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-apply-filters {
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-apply-filters:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }

        .btn-reset-filters {
            background: white;
            color: var(--secondary);
            border: 2px solid var(--border-light);
            padding: 14px 28px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-reset-filters:hover {
            border-color: var(--sos-blue);
            color: var(--sos-blue);
            transform: translateY(-2px);
        }

        /* Results Section */
        .results-section {
            background: var(--light);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-light);
        }

        .results-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .results-title i {
            color: var(--dhl-yellow);
            font-size: 26px;
        }

        .results-count {
            font-size: 16px;
            color: var(--secondary);
            font-weight: 500;
        }

        .sort-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sort-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .sort-select {
            padding: 8px 16px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
            cursor: pointer;
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Offres Grid */
        .offres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .offre-card {
            background: var(--light);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .offre-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--dhl-red), var(--dhl-yellow), var(--sos-blue));
        }

        .offre-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .offre-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .offre-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .offre-company {
            font-size: 16px;
            color: var(--sos-blue);
            font-weight: 600;
        }

        .offre-badge {
            background: var(--senegal-green);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .offre-badge.urgent {
            background: var(--dhl-red);
            animation: pulse 2s infinite;
        }

        .offre-badge.recent {
            background: var(--dhl-yellow);
            color: var(--dark);
        }

        .offre-details {
            margin-bottom: 20px;
        }

        .offre-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: var(--secondary);
        }

        .offre-detail i {
            color: var(--sos-blue);
            width: 18px;
            font-size: 16px;
        }

        .offre-description {
            font-size: 15px;
            color: var(--dark);
            line-height: 1.6;
            margin-bottom: 25px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .offre-actions {
            display: flex;
            gap: 12px;
        }

        .btn-secondary {
            background: white;
            color: var(--sos-blue);
            border: 2px solid var(--sos-blue);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: var(--sos-blue);
            color: white;
            transform: translateY(-2px);
        }

        .btn-postuler {
            background: linear-gradient(135deg, var(--senegal-green), var(--sos-blue));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-postuler:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .page-link:hover {
            border-color: var(--sos-blue);
            color: var(--sos-blue);
            transform: translateY(-2px);
        }

        .page-link.active {
            background: var(--sos-blue);
            border-color: var(--sos-blue);
            color: white;
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-link.disabled:hover {
            transform: none;
            border-color: var(--border-light);
            color: var(--secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 64px;
            color: var(--border-light);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 25px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .filters-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .offres-grid {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 36px;
            }
            
            .stats-banner {
                flex-direction: column;
                gap: 20px;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .offres-grid {
                grid-template-columns: 1fr;
            }
            
            .filters-header, .results-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .sort-controls {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* Modal Styles - Expert Level */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            background: var(--light);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 10000;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .modal-overlay.active .modal-container {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: var(--dark);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            z-index: 10001;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-close:hover {
            background: white;
            border-color: var(--sos-blue);
            color: var(--sos-blue);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-close:active {
            transform: scale(0.95);
        }

        .modal-title {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .modal-company {
            font-size: 18px;
            opacity: 0.9;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .modal-badge {
            position: absolute;
            top: 30px;
            right: 80px;
            background: var(--dhl-yellow);
            color: var(--dark);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3);
        }

        .modal-content {
            padding: 40px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-section {
            margin-bottom: 30px;
        }

        .modal-section:last-child {
            margin-bottom: 0;
        }

        .modal-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-light);
        }

        .modal-section-title i {
            color: var(--sos-blue);
            font-size: 20px;
        }

        .modal-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .modal-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .modal-info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--sos-blue);
        }

        .modal-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }

        .modal-info-content h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-info-content p {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .modal-description {
            font-size: 16px;
            line-height: 1.7;
            color: var(--dark);
            background: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--sos-blue);
        }

        .modal-requirements {
            background: linear-gradient(135deg, rgba(255, 204, 0, 0.1), rgba(59, 130, 246, 0.1));
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 204, 0, 0.3);
        }

        .modal-requirements h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-requirements h4 i {
            color: var(--dhl-yellow);
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
            font-size: 14px;
            color: var(--dark);
        }

        .requirements-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--senegal-green);
            font-weight: bold;
            font-size: 16px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding-top: 20px;
            border-top: 2px solid var(--border-light);
        }

        .btn-postuler {
            background: linear-gradient(135deg, var(--senegal-green), var(--sos-blue));
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-postuler::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-postuler:hover::before {
            left: 100%;
        }

        .btn-postuler:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }

        .btn-postuler:active {
            transform: translateY(-1px);
        }

        .btn-secondary-modal {
            background: white;
            color: var(--sos-blue);
            border: 2px solid var(--sos-blue);
            border-radius: 50px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary-modal:hover {
            background: var(--sos-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* Scrollbar personnalisée pour le modal */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: var(--bg-light);
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: var(--sos-blue);
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: var(--dhl-red);
        }

        /* Modal Tabs */
        .modal-tabs {
            display: flex;
            border-bottom: 2px solid var(--border-light);
            margin-bottom: 30px;
        }

        .modal-tab {
            background: none;
            border: none;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: 600;
            color: var(--secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-tab:hover {
            color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.05);
        }

        .modal-tab.active {
            color: var(--sos-blue);
            border-bottom-color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.1);
        }

        .modal-tab-content {
            display: none;
        }

        .modal-tab-content.active {
            display: block;
        }

        .modal-subtitle {
            font-size: 16px;
            color: var(--secondary);
            margin-bottom: 25px;
            text-align: center;
        }

        .modal-section-subtitle {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-light);
        }

        .modal-section-subtitle i {
            color: var(--sos-blue);
            font-size: 20px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--sos-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* File Upload Styles */
        .file-upload-wrapper {
            position: relative;
            border: 2px dashed var(--border-light);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            background: var(--bg-light);
        }

        .file-upload-wrapper:hover {
            border-color: var(--sos-blue);
            background: rgba(59, 130, 246, 0.05);
        }

        .file-upload-wrapper input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-info {
            pointer-events: none;
        }

        .file-upload-info i {
            font-size: 32px;
            color: var(--sos-blue);
            margin-bottom: 15px;
            display: block;
        }

        .file-upload-info span {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .file-upload-info small {
            color: var(--secondary);
            font-size: 14px;
        }

        /* Animations pour SweetAlert */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .animated {
            animation-duration: 0.6s;
            animation-fill-mode: both;
        }

        .fadeInDown {
            animation-name: fadeInDown;
        }

        .shake {
            animation-name: shake;
        }

        /* Styles pour les boutons d'utilisation des documents existants */
        .btn-use-existing {
            background: linear-gradient(135deg, var(--senegal-green), #00c853);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 8px;
        }

        .btn-use-existing:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3);
        }

        .btn-replace {
            background: linear-gradient(135deg, var(--dhl-red), #ef4444);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-replace:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .file-upload-wrapper.has-existing {
            background: rgba(0, 166, 81, 0.05);
            border-color: var(--senegal-green);
        }

        .file-upload-wrapper.using-existing {
            background: linear-gradient(135deg, rgba(255, 204, 0, 0.1), rgba(255, 204, 0, 0.05));
            border-color: var(--dhl-yellow);
        }

        .file-upload-wrapper.replacing {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border-color: var(--dhl-red);
            animation: pulse 2s infinite;
        }

        .file-upload-wrapper.uploading-new {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
            border-color: var(--sos-blue);
        }



        /* Styles pour SweetAlert au-dessus du modal */
        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-popup {
            z-index: 100000 !important;
        }

        .swal2-backdrop {
            z-index: 99998 !important;
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-container {
                width: 95%;
                max-height: 95vh;
            }

            .modal-header {
                padding: 20px;
            }

            .modal-title {
                font-size: 24px;
            }

            .modal-company {
                font-size: 16px;
            }

            .modal-badge {
                position: static;
                display: inline-block;
                margin-top: 10px;
            }

            .modal-content {
                padding: 20px;
            }

            .modal-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .modal-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn-postuler, .btn-secondary-modal {
                width: 100%;
                justify-content: center;
            }

            .modal-tabs {
                flex-direction: column;
            }

            .modal-tab {
                text-align: center;
                justify-content: center;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

@section('content')
    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1 class="page-title">Découvrez Toutes les Offres</h1>
            <p class="page-subtitle">Trouvez l'opportunité parfaite qui correspond à vos compétences et aspirations</p>
            
            <div class="stats-banner">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Offres disponibles</div>
                    </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['urgentes'] }}</div>
                    <div class="stat-label">Offres urgentes</div>
                    </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['recentes'] }}</div>
                    <div class="stat-label">Offres récentes</div>
                </div>
            </div>
        </header>

        <!-- Filters Section -->
        <section class="filters-section">
            <div class="filters-header">
                <h2 class="filters-title">
                    <i class="fas fa-filter"></i>
                    Filtres de Recherche
                </h2>
                <button class="clear-filters" onclick="clearAllFilters()">
                    <i class="fas fa-times"></i>
                    Effacer tous les filtres
                </button>
    </div>

                            <form method="GET" action="{{ route('jeunes.offres.toutes') }}" id="filtersForm">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Recherche</label>
                        <input type="text" name="search" class="filter-input" placeholder="Titre, entreprise, compétences..." value="{{ $request->get('search') }}">
                        </div>

                    <div class="filter-group">
                        <label class="filter-label">Type de contrat</label>
                        <select name="type_contrat" class="filter-select">
                            <option value="">Tous les types</option>
                            @foreach($stats['types'] as $type)
                                <option value="{{ $type }}" {{ $request->get('type_contrat') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    <div class="filter-group">
                        <label class="filter-label">Ville</label>
                        <select name="ville" class="filter-select">
                            <option value="">Toutes les villes</option>
                            @foreach($stats['villes'] as $ville)
                                <option value="{{ $ville }}" {{ $request->get('ville') == $ville ? 'selected' : '' }}>
                                    {{ $ville }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    <div class="filter-group">
                        <label class="filter-label">Options</label>
                        <div class="filter-checkbox">
                            <input type="checkbox" name="urgent" id="urgent" value="1" {{ $request->get('urgent') ? 'checked' : '' }}>
                            <span>Offres urgentes uniquement</span>
                        </div>
                        <div class="filter-checkbox">
                            <input type="checkbox" name="recent" id="recent" value="1" {{ $request->get('recent') ? 'checked' : '' }}>
                            <span>Offres récentes (30 derniers jours)</span>
                        </div>
                        </div>
                    </div>

                <div class="filters-actions">
                    <button type="submit" class="btn-apply-filters">
                        <i class="fas fa-search"></i>
                        Appliquer les filtres
                        </button>
                    <button type="button" class="btn-reset-filters" onclick="resetFilters()">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                    </div>
                </form>
        </section>

        <!-- Results Section -->
        <section class="results-section">
            <div class="results-header">
                <div>
                    <h2 class="results-title">
                        <i class="fas fa-briefcase"></i>
                        Résultats de la recherche
                </h2>
                    <p class="results-count">{{ $offres->total() }} offre(s) trouvée(s)</p>
                </div>

                <div class="sort-controls">
                    <label class="sort-label">Trier par:</label>
                    <select class="sort-select" onchange="changeSort(this.value)">
                        <option value="created_at-desc" {{ $request->get('sort') == 'created_at' && $request->get('order') == 'desc' ? 'selected' : '' }}>Plus récentes</option>
                        <option value="created_at-asc" {{ $request->get('sort') == 'created_at' && $request->get('order') == 'asc' ? 'selected' : '' }}>Plus anciennes</option>
                        <option value="urgent" {{ $request->get('sort') == 'urgent' ? 'selected' : '' }}>Urgentes en premier</option>
                    </select>
            </div>
        </div>

        @if($offres->count() > 0)
                <div class="offres-grid">
                @foreach($offres as $offre)
                        <div class="offre-card fade-in-up">
                            <div class="offre-header">
                                <div>
                                    <h3 class="offre-title">{{ $offre->titre }}</h3>
                                    <p class="offre-company">{{ $offre->employeur->user->name ?? 'Entreprise non spécifiée' }}</p>
                                </div>
                                <span class="offre-badge {{ $offre->offre_urgente ? 'urgent' : '' }} {{ $offre->created_at && $offre->created_at->diffInDays(now()) <= 30 ? 'recent' : '' }}">
                                    {{ ucfirst($offre->type_contrat) }}
                                    @if($offre->offre_urgente)
                                        • Urgent
                                    @endif
                                </span>
                            </div>

                            <div class="offre-details">
                                <div class="offre-detail">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $offre->ville_travail }}</span>
                                </div>
                                <div class="offre-detail">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Publiée {{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date non spécifiée' }}</span>
                                </div>
                                @if($offre->date_expiration)
                                    <div class="offre-detail">
                                        <i class="fas fa-clock"></i>
                                        <span>Expire {{ $offre->date_expiration->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>

                            <p class="offre-description">
                                {{ Str::limit($offre->description, 150) }}
                            </p>

                            <div class="offre-actions">
                                <button class="btn-secondary" onclick="openOffreModal('{{ $offre->offre_id }}')">
                                    <i class="fas fa-eye"></i>
                                    Voir détails
                                </button>
                                <button class="btn-postuler" onclick="postulerOffre('{{ $offre->offre_id }}')">
                                    <i class="fas fa-paper-plane"></i>
                                        Postuler
                                </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($offres->onFirstPage())
                            <span class="page-link disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $offres->previousPageUrl() }}" class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </a>
            @endif

                        {{-- Pagination Elements --}}
                        @foreach ($offres->getUrlRange(1, $offres->lastPage()) as $page => $url)
                            @if ($page == $offres->currentPage())
                                <span class="page-link active">{{ $page }}</span>
        @else
                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($offres->hasMorePages())
                            <a href="{{ $offres->nextPageUrl() }}" class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-link disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                @endif
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Aucune offre trouvée</h3>
                    <p>Essayez de modifier vos critères de recherche ou revenez plus tard.</p>
                    <a href="{{ route('jeunes.offres.toutes') }}" class="btn-primary">
                        <i class="fas fa-refresh"></i>
                        Voir toutes les offres
                    </a>
            </div>
        @endif
        </section>
    </div>

    <!-- Modal Détails Offre -->
    <div class="modal-overlay" id="offreModal">
        <div class="modal-container">
            <div class="modal-header">
                <button class="modal-close" onclick="closeOffreModal()" title="Fermer le modal" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="modal-title" id="modalTitle">Titre de l'offre</h2>
                <p class="modal-company" id="modalCompany">Nom de l'entreprise</p>
                <span class="modal-badge" id="modalBadge">Type de contrat</span>
</div>

            <div class="modal-content">
                <!-- Onglets du modal -->
                <div class="modal-tabs">
                    <button class="modal-tab active" onclick="switchTab('details')">
                        <i class="fas fa-info-circle"></i>
                        Détails de l'offre
                    </button>
                    <button class="modal-tab" onclick="switchTab('candidature')">
                        <i class="fas fa-paper-plane"></i>
                        Candidature
                    </button>
                </div>
                
                <!-- Onglet Détails -->
                <div id="tab-details" class="modal-tab-content active">
                    <div class="modal-section">
                        <h3 class="modal-section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations Générales
                        </h3>
                        <div class="modal-info-grid" id="modalInfoGrid">
                            <!-- Les informations seront injectées dynamiquement -->
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <h3 class="modal-section-title">
                            <i class="fas fa-align-left"></i>
                            Description du Poste
                        </h3>
                        <div class="modal-description" id="modalDescription">
                            <!-- La description sera injectée dynamiquement -->
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <h3 class="modal-section-title">
                            <i class="fas fa-list-check"></i>
                            Compétences Requises
                        </h3>
                        <div class="modal-requirements">
                            <h4>
                                <i class="fas fa-star"></i>
                                Profil recherché
                            </h4>
                            <ul class="requirements-list" id="modalRequirements">
                                <!-- Les compétences seront injectées dynamiquement -->
                            </ul>
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button class="btn-postuler" onclick="switchTab('candidature')">
                            <i class="fas fa-paper-plane"></i>
                            Postuler maintenant
                        </button>
                        <button class="btn-secondary-modal" onclick="closeOffreModal()">
                            <i class="fas fa-times"></i>
                            Fermer
                        </button>
                    </div>
                </div>
                
                <!-- Onglet Candidature -->
                <div id="tab-candidature" class="modal-tab-content">
                    <div class="modal-section">
                        <h3 class="modal-section-title">
                            <i class="fas fa-paper-plane"></i>
                            Formulaire de Candidature
                        </h3>
                        <p class="modal-subtitle">Remplissez ce formulaire pour postuler à cette offre</p>
                    </div>
                    
                    <form id="candidatureForm" enctype="multipart/form-data">
                        <div class="modal-section">
                            <h4 class="modal-section-subtitle">
                                <i class="fas fa-file-upload"></i>
                                Documents de Candidature
                            </h4>
                            
                            <div class="form-group">
                                <label for="cv_file">CV *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx">
                                    <div class="file-upload-info">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Glissez votre CV ici ou cliquez pour sélectionner</span>
                                        <small>Formats acceptés: PDF, DOC, DOCX (Max: 2MB)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="lettre_motivation_file">Lettre de Motivation</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="lettre_motivation_file" name="lettre_motivation_file" accept=".pdf,.doc,.docx">
                                    <div class="file-upload-info">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Glissez votre lettre de motivation ici</span>
                                        <small>Formats acceptés: PDF, DOC, DOCX (Max: 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-section">
                            <h4 class="modal-section-subtitle">
                                <i class="fas fa-user-edit"></i>
                                Informations Complémentaires
                            </h4>
                            
                                                         <div class="form-group">
                                 <label for="disponibilite">Disponibilité *</label>
                                 <select id="disponibilite" name="disponibilite" required>
                                     <option value="">Sélectionnez votre disponibilité</option>
                                     <option value="immediate">Immédiate</option>
                                     <option value="1_semaine">Dans 1 semaine</option>
                                     <option value="2_semaines">Dans 2 semaines</option>
                                     <option value="1_mois">Dans 1 mois</option>
                                     <option value="plus">Plus tard</option>
                                 </select>
                             </div>
                             
                             <div class="form-group">
                                 <label for="niveau_etude">Niveau d'études *</label>
                                 <input type="text" id="niveau_etude" name="niveau_etude" placeholder="Ex: Bac+3, Master, etc." required>
                             </div>
                            
                            <div class="form-group">
                                <label for="message_motivation">Message de motivation</label>
                                <textarea id="message_motivation" name="message_motivation" rows="4" placeholder="Expliquez brièvement pourquoi vous êtes intéressé par ce poste et ce que vous pouvez apporter à l'entreprise..."></textarea>
                            </div>
                        </div>
                        
                        <div class="modal-section">
                            <h4 class="modal-section-subtitle">
                                <i class="fas fa-tags"></i>
                                Compétences et Langues
                            </h4>
                            
                            <div class="form-group">
                                <label for="competences">Compétences clés (séparées par des virgules)</label>
                                <input type="text" id="competences" name="competences" placeholder="Ex: Gestion de projet, Marketing digital, Analyse de données">
                            </div>
                            
                            <div class="form-group">
                                <label for="langues">Langues maîtrisées (séparées par des virgules)</label>
                                <input type="text" id="langues" name="langues" placeholder="Ex: Français, Anglais, Wolof">
                            </div>
                        </div>
                        
                        <div class="modal-actions">
                            <button type="submit" class="btn-postuler" id="submitCandidature">
                                <i class="fas fa-paper-plane"></i>
                                Soumettre ma candidature
                            </button>
                            <button type="button" class="btn-secondary-modal" onclick="switchTab('details')">
                                <i class="fas fa-arrow-left"></i>
                                Retour aux détails
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Fonction pour changer le tri
        function changeSort(value) {
            const [sort, order] = value.split('-');
            const url = new URL(window.location);
            url.searchParams.set('sort', sort);
            url.searchParams.set('order', order);
            window.location.href = url.toString();
        }

        // Fonction pour réinitialiser les filtres
        function resetFilters() {
            window.location.href = '{{ route("jeunes.offres.toutes") }}';
        }

        // Fonction pour effacer tous les filtres
        function clearAllFilters() {
            const form = document.getElementById('filtersForm');
            const inputs = form.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            form.submit();
        }

        // Animation des cartes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.offre-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Variables globales pour le modal
        let currentOffreId = null;
        let currentOffreData = null;

        // Fonction pour ouvrir le modal avec les détails de l'offre
        function openOffreModal(offreId, openCandidature = false) {
            currentOffreId = offreId;
            
            // Récupérer les données de l'offre depuis la carte
            const offreCard = document.querySelector(`[onclick="openOffreModal('${offreId}')"]`).closest('.offre-card');
            
            if (!offreCard) {
                console.error('Carte de l\'offre non trouvée');
                return;
            }

            // Extraire les données de l'offre depuis le DOM
            const offreData = {
                title: offreCard.querySelector('.offre-title')?.textContent?.trim() || 'Titre non disponible',
                company: offreCard.querySelector('.offre-company')?.textContent?.trim() || 'Entreprise non disponible',
                badge: offreCard.querySelector('.offre-badge')?.textContent?.trim() || 'Type non disponible',
                location: offreCard.querySelector('.offre-detail i.fas.fa-map-marker-alt')?.nextElementSibling?.textContent?.trim() || 'Localisation non disponible',
                sector: offreCard.querySelector('.offre-detail i.fas.fa-folder')?.nextElementSibling?.textContent?.trim() || 'Secteur non disponible',
                description: offreCard.querySelector('.offre-description')?.textContent?.trim() || 'Description non disponible',
                requirements: offreCard.querySelector('.offre-requirements')?.textContent?.trim() || 'Compétences non disponibles'
            };

            currentOffreData = offreData;

            // Remplir le contenu du modal
            document.getElementById('modalTitle').textContent = offreData.title;
            document.getElementById('modalCompany').textContent = offreData.company;
            document.getElementById('modalBadge').textContent = offreData.badge;
            document.getElementById('modalDescription').textContent = offreData.description;

            // Remplir les informations générales
            const infoGrid = document.getElementById('modalInfoGrid');
            infoGrid.innerHTML = `
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="modal-info-content">
                        <h4>Localisation</h4>
                        <p>${offreData.location}</p>
                    </div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="modal-info-content">
                        <h4>Secteur</h4>
                        <p>${offreData.sector}</p>
                    </div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="modal-info-content">
                        <h4>Type de contrat</h4>
                        <p>${offreData.badge}</p>
                    </div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="modal-info-content">
                        <h4>Expérience</h4>
                        <p>À définir selon le profil</p>
                    </div>
                </div>
            `;

            // Remplir les compétences requises (simulation basée sur la description)
            const requirementsList = document.getElementById('modalRequirements');
            const requirements = [
                'Profil dynamique et motivé',
                'Capacité d\'adaptation',
                'Sens du relationnel',
                'Autonomie dans le travail',
                'Maîtrise des outils bureautiques'
            ];
            
            requirementsList.innerHTML = requirements.map(req => 
                `<li>${req}</li>`
            ).join('');

            // Afficher le modal
            const modal = document.getElementById('offreModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Animation d'entrée
            setTimeout(() => {
                modal.querySelector('.modal-container').style.opacity = '1';
                modal.querySelector('.modal-container').style.transform = 'translate(-50%, -50%) scale(1)';
            }, 100);

            // Si on doit ouvrir directement l'onglet candidature
            if (openCandidature) {
                setTimeout(() => {
                    switchTab('candidature');
                }, 150); // Petit délai pour laisser l'animation se terminer
            }
        }

        // Fonction pour fermer le modal
        function closeOffreModal() {
            const modal = document.getElementById('offreModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';

            // Animation de sortie
            modal.querySelector('.modal-container').style.opacity = '0';
            modal.querySelector('.modal-container').style.transform = 'translate(-50%, -50%) scale(0.9)';

            // Réinitialiser les variables
            currentOffreId = null;
            currentOffreData = null;
        }

        // Fonction pour changer d'onglet
        function switchTab(tabName) {
            // Masquer tous les onglets
            document.querySelectorAll('.modal-tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Désactiver tous les onglets
            document.querySelectorAll('.modal-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Afficher l'onglet sélectionné
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            // Activer l'onglet sélectionné (seulement si c'est un clic utilisateur)
            if (event && event.target) {
                event.target.classList.add('active');
            } else {
                // Si appelé programmatiquement, activer le bon onglet
                document.querySelectorAll('.modal-tab').forEach(tab => {
                    if (tab.getAttribute('onclick') && tab.getAttribute('onclick').includes(tabName)) {
                        tab.classList.add('active');
                    }
                });
            }
        }

        // Fonction pour postuler
        function postulerOffre(offreId) {
            if (!offreId) {
                console.error('Aucune offre sélectionnée');
                return;
            }

            // Ouvrir directement le modal avec l'onglet candidature
            openOffreModal(offreId, true);
        }

        // Fermer le modal en cliquant sur l'overlay
        document.getElementById('offreModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeOffreModal();
            }
        });

        // Fermer le modal avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOffreModal();
            }
        });

        // Récupérer les documents existants au chargement de la page
        let existingDocuments = {};
        
        async function loadExistingDocuments() {
            try {
                const response = await fetch('/jeunes/documents/existants', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    existingDocuments = data.documents;
                    updateDocumentFields();
                }
            } catch (error) {
                console.error('Erreur lors du chargement des documents:', error);
            }
        }
        
        // Mettre à jour l'affichage des champs de documents
        function updateDocumentFields() {
            const cvField = document.getElementById('cv_file');
            const lmField = document.getElementById('lettre_motivation_file');
            const cvWrapper = cvField.closest('.file-upload-wrapper');
            const lmWrapper = lmField.closest('.file-upload-wrapper');
            
            // Gestion du CV
            if (existingDocuments.cv) {
                cvWrapper.classList.add('has-existing');
                const cvInfo = cvWrapper.querySelector('.file-upload-info');
                cvInfo.innerHTML = `
                    <i class="fas fa-check-circle" style="color: var(--senegal-green);"></i>
                    <span>CV existant : ${existingDocuments.cv.nom_original}</span>
                    <small>Cliquez pour le remplacer ou utilisez le bouton "Utiliser ce CV"</small>
                    <div class="mt-3">
                        <button type="button" class="btn-use-existing" onclick="useExistingDocument('cv')">
                            <i class="fas fa-recycle"></i> Utiliser ce CV
                        </button>
                    </div>
                `;
                
                // Ajouter automatiquement le champ caché pour utiliser le CV existant
                addHiddenField('use_existing_cv', '1');
            }
            
            // Gestion de la lettre de motivation
            if (existingDocuments.lettre_motivation) {
                lmWrapper.classList.add('has-existing');
                const lmInfo = lmWrapper.querySelector('.file-upload-info');
                lmInfo.innerHTML = `
                    <i class="fas fa-check-circle" style="color: var(--senegal-green);"></i>
                    <span>Lettre existante : ${existingDocuments.lettre_motivation.nom_original}</span>
                    <small>Cliquez pour la remplacer ou utilisez le bouton "Utiliser cette lettre"</small>
                    <div class="mt-3">
                        <button type="button" class="btn-use-existing" onclick="useExistingDocument('lettre_motivation')">
                            <i class="fas fa-recycle"></i> Utiliser cette lettre
                        </button>
                    </div>
                `;
                
                // Ajouter automatiquement le champ caché pour utiliser la lettre existante
                addHiddenField('use_existing_lm', '1');
            }
        }
        
        // Fonction pour ajouter un champ caché
        function addHiddenField(name, value) {
            const form = document.getElementById('candidatureForm');
            let hiddenField = form.querySelector(`input[name="${name}"]`);
            
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = name;
                hiddenField.value = value;
                form.appendChild(hiddenField);
            }
        }
        
        // Fonction pour gérer le remplacement de fichiers
        function handleFileReplacement(type) {
            const field = document.getElementById(type === 'cv' ? 'cv_file' : 'lettre_motivation_file');
            const wrapper = field.closest('.file-upload-wrapper');
            
            // Retirer les classes d'état
            wrapper.classList.remove('has-existing');
            wrapper.classList.add('replacing');
            
            // Supprimer le champ caché correspondant
            const fieldName = `use_existing_${type === 'cv' ? 'cv' : 'lm'}`;
            const form = document.getElementById('candidatureForm');
            const hiddenField = form.querySelector(`input[name="${fieldName}"]`);
            if (hiddenField) {
                hiddenField.remove();
            }
            
            // Mettre à jour l'affichage
            const info = wrapper.querySelector('.file-upload-info');
            info.innerHTML = `
                <div class="replacing-document-info">
                    <i class="fas fa-upload" style="color: var(--dhl-red);"></i>
                    <span><strong>Remplacement en cours...</strong></span>
                    <small>Sélectionnez un nouveau fichier pour remplacer le document existant</small>
                </div>
            `;
            
            // Réactiver le champ
            field.disabled = false;
            field.focus();
        }
        
        // Charger les documents existants au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingDocuments();
            
            // Ajouter des écouteurs d'événements pour les champs de fichiers
            const cvField = document.getElementById('cv_file');
            const lmField = document.getElementById('lettre_motivation_file');
            
            // Permettre de cliquer sur les champs pour remplacer les documents existants
            cvField.addEventListener('click', function() {
                const wrapper = this.closest('.file-upload-wrapper');
                if (wrapper.classList.contains('has-existing')) {
                    handleFileReplacement('cv');
                }
            });
            
            lmField.addEventListener('click', function() {
                const wrapper = this.closest('.file-upload-wrapper');
                if (wrapper.classList.contains('has-existing')) {
                    handleFileReplacement('lettre_motivation');
                }
            });
            
            // Gérer les changements de fichiers
            cvField.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    const wrapper = this.closest('.file-upload-wrapper');
                    wrapper.classList.remove('replacing');
                    wrapper.classList.add('uploading-new');
                    
                    const info = wrapper.querySelector('.file-upload-info');
                    info.innerHTML = `
                        <div class="new-file-selected">
                            <i class="fas fa-file-upload" style="color: var(--dhl-red);"></i>
                            <span><strong>Nouveau fichier sélectionné :</strong> ${this.files[0].name}</span>
                            <small>Ce fichier remplacera votre document existant</small>
                        </div>
                    `;
                    
                    // Supprimer le champ caché pour utiliser l'existant
                    const form = document.getElementById('candidatureForm');
                    const hiddenField = form.querySelector('input[name="use_existing_cv"]');
                    if (hiddenField) {
                        hiddenField.remove();
                    }
                }
            });
            
            lmField.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    const wrapper = this.closest('.file-upload-wrapper');
                    wrapper.classList.remove('replacing');
                    wrapper.classList.add('uploading-new');
                    
                    const info = wrapper.querySelector('.file-upload-info');
                    info.innerHTML = `
                        <div class="new-file-selected">
                            <i class="fas fa-file-upload" style="color: var(--dhl-red);"></i>
                            <span><strong>Nouveau fichier sélectionné :</strong> ${this.files[0].name}</span>
                            <small>Ce fichier remplacera votre document existant</small>
                        </div>
                    `;
                    
                    // Supprimer le champ caché pour utiliser l'existant
                    const form = document.getElementById('candidatureForm');
                    const hiddenField = form.querySelector('input[name="use_existing_lm"]');
                    if (hiddenField) {
                        hiddenField.remove();
                    }
                }
            });
        });
        
        // Gestion du formulaire de candidature
        document.getElementById('candidatureForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentOffreId) {
                alert('Erreur: Aucune offre sélectionnée');
                return;
            }

            // Validation côté client pour les documents
            const cvFile = document.getElementById('cv_file');
            const lmFile = document.getElementById('lettre_motivation_file');
            const useExistingCv = document.querySelector('input[name="use_existing_cv"]');
            const useExistingLm = document.querySelector('input[name="use_existing_lm"]');
            
            // Vérifier le CV - soit un fichier est sélectionné, soit un document existant est utilisé
            if (!useExistingCv && (!cvFile.files || cvFile.files.length === 0)) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ CV requis',
                    text: 'Vous devez soit uploader un nouveau CV, soit utiliser votre CV existant (qui est utilisé automatiquement par défaut).',
                    confirmButtonText: 'Compris',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            
            // Vérifier la lettre de motivation - soit un fichier est sélectionné, soit un document existant est utilisé
            if (!useExistingLm && (!lmFile.files || lmFile.files.length === 0)) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Lettre de motivation requise',
                    text: 'Vous devez soit uploader une nouvelle lettre, soit utiliser votre lettre existante (qui est utilisée automatiquement par défaut).',
                    confirmButtonText: 'Compris',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            const formData = new FormData(this);
            
            // Ajouter l'ID de l'offre
            formData.append('offre_id', currentOffreId);
            
            // Traiter les compétences et langues (chaînes simples)
            const competences = formData.get('competences') || '';
            const langues = formData.get('langues') || '';
            
            formData.set('competences', competences);
            formData.set('langues', langues);
            
            // Désactiver le bouton de soumission
            const submitBtn = document.getElementById('submitCandidature');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;
            
            // Envoyer la candidature
            fetch(`/jeunes/offres/${currentOffreId}/candidature`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                                 if (data.success) {
                     // Succès
                     submitBtn.innerHTML = '<i class="fas fa-check"></i> Candidature envoyée !';
                     submitBtn.style.background = 'linear-gradient(135deg, var(--senegal-green), var(--senegal-green))';
                     
                                           // SweetAlert de succès avec z-index élevé
                      Swal.fire({
                          icon: 'success',
                          title: '🎉 Candidature Soumise !',
                          text: data.message,
                          confirmButtonText: 'OK',
                          confirmButtonColor: '#00a651',
                          background: '#ffffff',
                          backdrop: `
                              rgba(0,0,123,0.4)
                              url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48-25.304A12.001 12.001 0 0 0 7 73.196V61a1 1 0 1 1 0-2v14.196A12.001 12.001 0 0 0 19 49.304V61a1 1 0 1 1 0 2V42.696z' fill='%23ffffff' fill-opacity='0.4'/%3E%3C/svg%3E")
                              left top
                              no-repeat
                          `,
                          customClass: {
                              popup: 'animated fadeInDown'
                          },
                          showClass: {
                              popup: 'swal2-show',
                              backdrop: 'swal2-backdrop-show'
                          },
                          hideClass: {
                              popup: 'swal2-hide',
                              backdrop: 'swal2-backdrop-hide'
                          }
                      }).then((result) => {
                         if (result.isConfirmed) {
                             // Fermer le modal
                             closeOffreModal();
                             
                             // Optionnel : Rediriger vers le dashboard après un délai
                             setTimeout(() => {
                                 if (data.redirect_url) {
                                     window.location.href = data.redirect_url;
                                 }
                             }, 500);
                         }
                     });
                                  } else {
                     // Erreur avec SweetAlert
                     Swal.fire({
                         icon: 'error',
                         title: '❌ Erreur de Soumission',
                         text: data.message,
                         confirmButtonText: 'Réessayer',
                         confirmButtonColor: '#ef4444',
                         background: '#ffffff',
                         customClass: {
                             popup: 'animated shake'
                         },
                         showClass: {
                             popup: 'swal2-show',
                             backdrop: 'swal2-backdrop-show'
                         },
                         hideClass: {
                             popup: 'swal2-hide',
                             backdrop: 'swal2-backdrop-hide'
                         }
                     });
                     
                     // Réactiver le bouton
                     submitBtn.innerHTML = originalText;
                     submitBtn.disabled = false;
                 }
            })
                         .catch(error => {
                 console.error('Erreur lors de la soumission:', error);
                 
                 // SweetAlert pour les erreurs de catch
                 Swal.fire({
                     icon: 'error',
                     title: '❌ Erreur Système',
                     text: 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.',
                     confirmButtonText: 'Fermer',
                     confirmButtonColor: '#ef4444',
                     background: '#ffffff',
                     customClass: {
                         popup: 'animated shake'
                     },
                     showClass: {
                         popup: 'swal2-show',
                         backdrop: 'swal2-backdrop-show'
                     },
                     hideClass: {
                         popup: 'swal2-hide',
                         backdrop: 'swal2-backdrop-show'
                     }
                 });
                 
                 // Réactiver le bouton
                 submitBtn.innerHTML = originalText;
                 submitBtn.disabled = false;
             });
        });

        // Gestion des fichiers uploadés
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const wrapper = this.closest('.file-upload-wrapper');
                    const info = wrapper.querySelector('.file-upload-info');
                    
                    // Retirer les classes d'état existantes
                    wrapper.classList.remove('has-existing', 'using-existing');
                    
                    // Mettre à jour l'affichage
                    info.innerHTML = `
                        <i class="fas fa-check-circle" style="color: var(--senegal-green);"></i>
                        <span>${file.name}</span>
                        <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    `;
                    
                    // Changer le style
                    wrapper.style.borderColor = 'var(--senegal-green)';
                    wrapper.style.background = 'rgba(16, 185, 129, 0.1)';
                    
                    // Supprimer le champ caché s'il existe
                    const type = this.id === 'cv_file' ? 'cv' : 'lm';
                    const hiddenField = wrapper.querySelector(`input[name="use_existing_${type}"]`);
                    if (hiddenField) {
                        hiddenField.remove();
                    }
                }
            });
            
            // Permettre de cliquer sur le wrapper pour réactiver l'upload
            input.addEventListener('click', function() {
                const wrapper = this.closest('.file-upload-wrapper');
                if (wrapper.classList.contains('using-existing')) {
                    const type = this.id === 'cv_file' ? 'cv' : 'lm';
                    handleFileReplacement(type);
                }
            });
        });

        // Fonction pour utiliser un document existant
        function useExistingDocument(type) {
            const field = document.getElementById(type === 'cv' ? 'cv_file' : 'lettre_motivation_file');
            const wrapper = field.closest('.file-upload-wrapper');
            const form = document.getElementById('candidatureForm');
            
            // Ajouter un indicateur visuel
            wrapper.classList.remove('has-existing');
            wrapper.classList.add('using-existing');
            
            // Ajouter un champ caché pour indiquer l'utilisation du document existant
            const fieldName = `use_existing_${type === 'cv' ? 'cv' : 'lm'}`;
            let hiddenField = form.querySelector(`input[name="${fieldName}"]`);
            
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = fieldName;
                hiddenField.value = '1';
                form.appendChild(hiddenField);
            }
            
            // Mettre à jour l'affichage
            const info = wrapper.querySelector('.file-upload-info');
            info.innerHTML = `
                <i class="fas fa-recycle" style="color: var(--dhl-yellow);"></i>
                <span>Utilisation du document existant activée</span>
                <small>Cliquez sur le champ pour le remplacer si nécessaire</small>
            `;
            
            // Désactiver le champ de fichier
            field.disabled = true;
            
            // Ajouter un indicateur de succès
            Swal.fire({
                icon: 'success',
                title: '✅ Document existant sélectionné',
                text: `Votre ${type === 'cv' ? 'CV' : 'lettre de motivation'} existant sera utilisé pour cette candidature.`,
                confirmButtonText: 'Parfait !',
                confirmButtonColor: '#00a651',
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>
@endsection
