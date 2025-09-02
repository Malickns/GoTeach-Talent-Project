@extends('pages.jeunes.layouts.app')

@section('title', 'GOTeach - Dashboard Jeune')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            display: grid;
            grid-template-columns: 320px 1fr 320px;
            gap: 25px;
        }

        /* Cards */
        .sidebar-card, .content-card {
            background: var(--light);
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-card:before, .content-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--dhl-red), var(--dhl-yellow), var(--sos-blue));
        }

        .sidebar-card:hover, .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        /* Left Sidebar */
        .left-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .profile-card {
            text-align: center;
            position: relative;
        }

        .profile-card .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            border: 4px solid white;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .profile-card .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .user-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .user-status {
            color: var(--sos-blue);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            padding: 6px 16px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 16px;
        }

        .stats-list {
            list-style: none;
            text-align: left;
            margin-top: 20px;
        }

        .stats-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-light);
            font-size: 13px;
        }

        .stats-list li:last-child {
            border-bottom: none;
        }

        .stats-label {
            color: var(--secondary);
            font-weight: 500;
        }

        .stats-value {
            color: var(--sos-blue);
            font-weight: 700;
            font-size: 15px;
        }

        .quick-nav {
            list-style: none;
        }

        .quick-nav li {
            margin-bottom: 10px;
        }

        .quick-nav a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: var(--secondary);
            text-decoration: none;
            border-radius: 14px;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .quick-nav a:hover, .quick-nav a.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(0, 102, 204, 0.1));
            color: var(--sos-blue);
            border-color: var(--sos-blue);
            transform: translateX(6px);
        }

        .quick-nav i {
            margin-right: 12px;
            width: 18px;
            color: var(--sos-blue);
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-light);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--dhl-yellow);
            font-size: 22px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--sos-blue), var(--dhl-red));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
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
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        /* Offres Grid */
        .offres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .offre-card {
            background: var(--light);
            border-radius: 16px;
            padding: 20px;
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
            margin-bottom: 15px;
        }

        .offre-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .offre-company {
            font-size: 14px;
            color: var(--sos-blue);
            font-weight: 600;
        }

        .offre-badge {
            background: var(--senegal-green);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            color: var(--secondary);
        }

        .offre-detail i {
            color: var(--sos-blue);
            width: 16px;
            font-size: 14px;
        }

        .offre-description {
            font-size: 14px;
            color: var(--dark);
            line-height: 1.5;
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .offre-actions {
            display: flex;
            gap: 10px;
        }

        .btn-secondary {
            background: white;
            color: var(--sos-blue);
            border: 2px solid var(--sos-blue);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .btn-secondary:hover {
            background: var(--sos-blue);
            color: white;
            transform: translateY(-1px);
        }

        /* Right Sidebar */
        .right-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .candidature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 18px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .candidature-item:last-child {
            border-bottom: none;
        }

        .candidature-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(0, 102, 204, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sos-blue);
            flex-shrink: 0;
            font-size: 18px;
        }

        .candidature-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .candidature-content p {
            font-size: 13px;
            color: var(--secondary);
            margin-bottom: 6px;
        }

        .candidature-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--warning);
            font-weight: 500;
        }

        .tips-section h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tips-section h3 i {
            color: var(--dhl-yellow);
        }

        .tip-item {
            background: linear-gradient(135deg, rgba(255, 204, 0, 0.1), rgba(59, 130, 246, 0.1));
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 12px;
            border-left: 3px solid var(--dhl-yellow);
        }

        .tip-item h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tip-item p {
            font-size: 13px;
            color: var(--secondary);
            line-height: 1.4;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 280px 1fr 280px;
                gap: 20px;
            }
        }

        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .left-sidebar, .right-sidebar {
                order: 2;
            }

            .main-content {
                order: 1;
            }
            }

        @media (max-width: 768px) {
            .offres-grid {
                grid-template-columns: 1fr;
            }
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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Modal Styles */
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
            padding: 8px 16px;
            border-radius: 20px;
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

        .btn-postuler:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary-modal {
            background: white;
            color: var(--sos-blue);
            border: 2px solid var(--sos-blue);
            padding: 16px 32px;
            border-radius: 50px;
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
            font-size: 24px;
            color: var(--sos-blue);
            margin-bottom: 10px;
        }

        .file-upload-info span {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .file-upload-info small {
            color: var(--secondary);
            font-size: 12px;
        }
    </style>

@section('content')
    <!-- Main Container -->
    <div class="main-container">
        <!-- Left Sidebar -->
        <aside class="left-sidebar">
            <!-- Profile Card -->
            <div class="sidebar-card profile-card">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="user-name">{{ $jeune->user->prenom }} {{ $jeune->user->nom }}</h2>
                <div class="user-status">{{ $jeune->niveau_etude ?? 'Niveau non spécifié' }}</div>
                
                <ul class="stats-list">
                    <li>
                        <span class="stats-label">Candidatures</span>
                        <span class="stats-value">{{ $statistiques['total_candidatures'] ?? 0 }}</span>
                    </li>
                    <li>
                        <span class="stats-label">Entretiens</span>
                        <span class="stats-value">{{ $statistiques['entretiens'] ?? 0 }}</span>
                    </li>
                    <li>
                        <span class="stats-label">Emplois obtenus</span>
                        <span class="stats-value">{{ $statistiques['emplois_obtenus'] ?? 0 }}</span>
                    </li>
                    <li>
                        <span class="stats-label">Offres disponibles</span>
                        <span class="stats-value">{{ $statistiques['offres_disponibles'] ?? 0 }}</span>
                    </li>
                </ul>
            </div>

            <!-- Quick Navigation -->
            <div class="sidebar-card">
                <h3 style="font-size: 18px; font-weight: 600; color: var(--dark); margin-bottom: 20px;">
                    Navigation Rapide
                </h3>
                <ul class="quick-nav">
                    <li><a href="#" class="active"><i class="fas fa-star"></i>Offres recommandées</a></li>
                    <li><a href="#"><i class="fas fa-clock"></i>Offres récentes</a></li>
                    <li><a href="#"><i class="fas fa-fire"></i>Offres urgentes</a></li>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i>Par localisation</a></li>
                    <li><a href="#"><i class="fas fa-filter"></i>Filtres avancés</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dernières Offres -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-clock"></i>
                        Dernières Offres
                    </h2>
                    <a href="{{ route('jeunes.offres.toutes') }}" class="btn-primary">
                        <i class="fas fa-search"></i>
                        Voir toutes les offres
                    </a>
                </div>
                
                <div class="offres-grid">
                    @forelse($offresRecentes as $offre)
                    <div class="offre-card fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <div class="offre-header">
                            <div>
                                <h3 class="offre-title">{{ $offre->titre }}</h3>
                                <p class="offre-company">{{ $offre->employeur->user->nom_entreprise ?? 'Entreprise non spécifiée' }}</p>
                            </div>
                            <span class="offre-badge">{{ $offre->type_contrat }}</span>
                        </div>
                        
                        <div class="offre-details">
                            <div class="offre-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $offre->ville_travail ?? 'Localisation non spécifiée' }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-folder"></i>
                                <span>{{ $offre->type_contrat ?? 'Type non spécifié' }}</span>
                            </div>
                            <div class="offre-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date inconnue' }}</span>
                            </div>
                        </div>
                        
                        <p class="offre-description">
                            {{ Str::limit($offre->description, 120) }}
                        </p>
                        
                        <div class="offre-actions">
                            <button class="btn-primary" onclick="openOffreModal('{{ $offre->offre_id }}')">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </button>
                            <button class="btn-secondary" onclick="postulerOffre('{{ $offre->offre_id }}')">
                                <i class="fas fa-paper-plane"></i>
                                Postuler
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Aucune offre disponible pour le moment</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
            <!-- Recent Applications -->
            <div class="sidebar-card">
                <h3 style="font-size: 18px; font-weight: 600; color: var(--dark); margin-bottom: 20px;">
                    Mes Candidatures Récentes
                </h3>
                
                @forelse($candidatures as $candidature)
                <div class="candidature-item">
                    <div class="candidature-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="candidature-content">
                        <h4>{{ $candidature->offreEmplois->titre ?? 'Titre non disponible' }}</h4>
                        <p>{{ $candidature->offreEmplois->employeur->user->nom_entreprise ?? 'Entreprise non spécifiée' }}</p>
                        <div class="candidature-status">
                            <i class="fas fa-clock"></i>
                            <span>{{ $candidature->created_at ? $candidature->created_at->diffForHumans() : 'Date inconnue' }} • {{ ucfirst($candidature->statut ?? 'En attente') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">Aucune candidature pour le moment</p>
                </div>
                @endforelse
            </div>

            <!-- Daily Tips -->
            <div class="sidebar-card">
                <div class="tips-section">
                    <h3>
                        <i class="fas fa-lightbulb"></i>
                        Conseils du Jour
                    </h3>
                    
                    <div class="tip-item">
                        <h4>
                            <i class="fas fa-check-circle" style="color: var(--senegal-green);"></i>
                            CV parfait
                        </h4>
                        <p>Assurez-vous que votre CV soit à jour et adapté au poste visé. Personnalisez-le selon l'entreprise.</p>
                    </div>
                    
                    <div class="tip-item">
                        <h4>
                            <i class="fas fa-envelope" style="color: var(--sos-blue);"></i>
                            Lettre de motivation
                        </h4>
                        <p>Personnalisez votre lettre pour chaque candidature. Montrez votre intérêt pour l'entreprise.</p>
                    </div>
                    
                    <div class="tip-item">
                        <h4>
                            <i class="fas fa-network-wired" style="color: var(--dhl-yellow);"></i>
                            Réseautage
                        </h4>
                        <p>Développez votre réseau professionnel. Participez aux événements et salons de votre secteur.</p>
                </div>
                    </div>
                    </div>
        </aside>
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
        // Variables globales pour le modal
        let currentOffreId = null;
        let currentOffreData = null;

        // Fonction pour récupérer les données d'une offre depuis le DOM
        function getOffreDataFromDOM(offreId) {
            const offreCard = document.querySelector(`[onclick*="${offreId}"]`)?.closest('.offre-card');
            if (!offreCard) return null;

            return {
                title: offreCard.querySelector('.offre-title')?.textContent?.trim() || 'Titre non disponible',
                company: offreCard.querySelector('.offre-company')?.textContent?.trim() || 'Entreprise non spécifiée',
                badge: offreCard.querySelector('.offre-badge')?.textContent?.trim() || 'Type non spécifié',
                location: offreCard.querySelector('.offre-detail i.fas.fa-map-marker-alt')?.nextElementSibling?.textContent?.trim() || 'Localisation non spécifiée',
                type: offreCard.querySelector('.offre-detail i.fas.fa-folder')?.nextElementSibling?.textContent?.trim() || 'Type non spécifié',
                description: offreCard.querySelector('.offre-description')?.textContent?.trim() || 'Description non disponible',
                requirements: [
                    'Profil dynamique et motivé',
                    'Capacité d\'adaptation',
                    'Sens du relationnel',
                    'Autonomie dans le travail',
                    'Maîtrise des outils bureautiques'
                ]
            };
        }

        // Fonction pour ouvrir le modal avec les détails de l'offre
        function openOffreModal(offreId, openCandidature = false) {
            currentOffreId = offreId;
            
            // Récupérer les données de l'offre depuis la carte
            const offreData = getOffreDataFromDOM(offreId);
            
            if (!offreData) {
                console.error('Données de l\'offre non trouvées');
                return;
            }

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
                        <h4>Type</h4>
                        <p>${offreData.type}</p>
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

            // Remplir les compétences requises
            const requirementsList = document.getElementById('modalRequirements');
            requirementsList.innerHTML = offreData.requirements.map(req => 
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
            
            // Activer l'onglet sélectionné
            document.querySelectorAll('.modal-tab').forEach(tab => {
                if (tab.getAttribute('onclick') && tab.getAttribute('onclick').includes(tabName)) {
                    tab.classList.add('active');
                }
            });
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

        // Add smooth scrolling and interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.offre-card, .sidebar-card');
            cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

            // Add click effects to buttons
            const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Add search functionality
            const searchBox = document.querySelector('.search-box');
            if (searchBox) {
                searchBox.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const offreCards = document.querySelectorAll('.offre-card');
                    
                    offreCards.forEach(card => {
                        const title = card.querySelector('.offre-title').textContent.toLowerCase();
                        const company = card.querySelector('.offre-company').textContent.toLowerCase();
                        const description = card.querySelector('.offre-description').textContent.toLowerCase();
                        
                        if (title.includes(query) || company.includes(query) || description.includes(query)) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeInUp 0.6s ease-out';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Add navigation active state
            const navItems = document.querySelectorAll('.nav-item a');
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Ne bloquer que les liens internes (ceux qui commencent par #)
                    if (this.getAttribute('href') && this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                    }
                    
                    // Mettre à jour l'état actif
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Gestion du formulaire de candidature
            const candidatureForm = document.getElementById('candidatureForm');
            if (candidatureForm) {
                candidatureForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Validation côté client pour les documents
                    const cvFile = document.getElementById('cv_file');
                    const lmFile = document.getElementById('lettre_motivation_file');
                    const useExistingCv = document.querySelector('input[name="use_existing_cv"]');
                    const useExistingLm = document.querySelector('input[name="use_existing_lm"]');
                    
                    // Vérifier le CV - soit un fichier est sélectionné, soit un document existant est utilisé
                    if (!useExistingCv && (!cvFile.files || cvFile.files.length === 0)) {
                        alert('⚠️ CV requis : Vous devez soit uploader un nouveau CV, soit utiliser votre CV existant.');
                        return;
                    }
                    
                    // Vérifier la lettre de motivation - soit un fichier est sélectionné, soit un document existant est utilisé
                    if (!useExistingLm && (!lmFile.files || lmFile.files.length === 0)) {
                        alert('⚠️ Lettre de motivation requise : Vous devez soit uploader une nouvelle lettre, soit utiliser votre lettre existante.');
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
                            
                            // Alert de succès
                            alert('🎉 Candidature Soumise ! ' + data.message);
                            
                            // Fermer le modal
                            closeOffreModal();
                            
                            // Optionnel : Rediriger vers le dashboard après un délai
            setTimeout(() => {
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                }
                            }, 500);
                        } else {
                            // Erreur
                            alert('❌ Erreur : ' + (data.message || 'Erreur lors de la soumission de la candidature'));
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la soumission:', error);
                        alert('❌ Erreur : Une erreur est survenue lors de la soumission de la candidature.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection