<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GoTeach') }} - Connexion</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth/login.css', 'resources/js/auth/login.js'])
</head>
<body>
    <div class="container">
        <!-- Section gauche avec le formulaire -->
        <div class="left-section">
            <div class="form-container">
                <div class="logo-container">
                    <div class="logo">
                        <span class="logo-text">GoTeach</span>
                        <span class="logo-hash">#</span>
                    </div>
                    <p class="welcome-text">Plateforme de mise en relation talents-recruteurs</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Entrez votre adresse email" required autofocus autocomplete="username"
                                   class="{{ $errors->has('email') ? 'error' : '' }}">
                        </div>
                        @if ($errors->has('email'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" 
                                   placeholder="Entrez votre mot de passe" required autocomplete="current-password"
                                   class="{{ $errors->has('password') ? 'error' : '' }}">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Se souvenir de moi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    <button type="submit" class="login-btn" id="loginBtn">
                        <span class="btn-text">Se connecter</span>
                        <i class="fas fa-arrow-right btn-icon"></i>
                    </button>
                </form>

                <div class="signup-section">
                    <p>Pas encore de compte ? <a href="{{ route('register') }}" class="signup-link">Créer un compte</a></p>
                </div>
            </div>
        </div>

        <!-- Section droite avec l'image et les éléments décoratifs -->
        <div class="right-section" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.3) 0%, rgba(51, 65, 85, 0.4) 100%), url('{{ asset('images/auth/login_background.png') }}');">
            <div class="background-overlay"></div>
            <div class="decorative-elements">
                <div class="floating-card card-1">
                    <i class="fas fa-handshake"></i>
                    <span>Connexion</span>
                </div>
                <div class="floating-card card-2">
                    <i class="fas fa-briefcase"></i>
                    <span>Opportunités</span>
                </div>
                <div class="floating-card card-3">
                    <i class="fas fa-users"></i>
                    <span>Talents</span>
                </div>
            </div>
            <div class="hero-content">
                <h1>Connectez talents et opportunités</h1>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <p>Connexion en cours...</p>
    </div>
</body>
</html>
