<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoTeach Sénégal - Votre Avenir Professionnel Commence Ici</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dhl-red': '#d40511',
                        'dhl-yellow': '#ffcc00',
                        'sos-blue': '#0066cc',
                        'senegal-green': '#00a651',
                    },
                    fontFamily: {
                        'display': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'gradient-x': 'gradientX 3s ease infinite',
                        'shimmer': 'shimmer 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(50px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        gradientX: {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' }
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        .glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        
        .glass-dark {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(17, 25, 40, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #d40511, #ffcc00, #0066cc, #00a651);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientX 3s ease infinite;
        }
        
        .hero-bg {
           background: url('{{ asset("images/auth/login_background.png") }}') no-repeat center center/cover;
           background-attachment: fixed;
           min-height: 100vh;
        }

        .particle {
            position: absolute;
            background: radial-gradient(circle, rgba(255,204,0,0.8) 0%, rgba(212,5,17,0.4) 100%);
            border-radius: 50%;
            pointer-events: none;
            animation: float 6s ease-in-out infinite;
        }
        
        .btn-glow {
            box-shadow: 0 0 20px rgba(212, 5, 17, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(212, 5, 17, 0.5), 0 0 40px rgba(255, 204, 0, 0.3);
            transform: translateY(-2px);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }
        
        .shape:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { top: 60%; right: 10%; animation-delay: 2s; }
        .shape:nth-child(3) { bottom: 20%; left: 20%; animation-delay: 4s; }
        
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .morphing-blob {
            position: absolute;
            top: 50%;
            right: 10%;
            width: 300px;
            height: 300px;
            background: linear-gradient(45deg, #d40511, #ffcc00);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: morph 8s ease-in-out infinite;
            opacity: 0.1;
            z-index: 0;
        }
        
        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
            100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        }
        
        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.8s ease-out forwards;
        }
        
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
            </style>
    </head>
<body class="font-display overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 animate-fade-in">
                    <div class="w-12 h-12 bg-gradient-to-br from-dhl-red to-dhl-yellow rounded-xl flex items-center justify-center shimmer-effect">
                        <span class="text-white font-bold text-xl">GT</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">GoTeach</h1>
                        <p class="text-sm text-gray-600">Sénégal</p>
                    </div>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <a href="#accueil" class="nav-item px-4 py-2 text-gray-700 hover:text-dhl-red transition-colors font-medium">Accueil</a>
                    <a href="#programme" class="nav-item px-4 py-2 text-gray-700 hover:text-dhl-red transition-colors font-medium">Programme</a>
                    <a href="#opportunites" class="nav-item px-4 py-2 text-gray-700 hover:text-dhl-red transition-colors font-medium">Opportunités</a>
                    <a href="#contact" class="nav-item px-4 py-2 text-gray-700 hover:text-dhl-red transition-colors font-medium">Contact</a>
                </div>
                
                <div class="flex space-x-4 animate-fade-in">
                    <a href="{{ route('login') }}" class="px-6 py-2 text-dhl-red border-2 border-dhl-red rounded-full hover:bg-dhl-red hover:text-white transition-all duration-300 font-semibold">
                        Se connecter
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-dhl-red to-dhl-yellow text-white rounded-full btn-glow font-semibold">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg relative min-h-screen flex items-center justify-center overflow-hidden" id="accueil">
	<div class="absolute inset-0 bg-black/40 z-0"></div>
        <!-- Floating Shapes -->
        <div class="floating-shapes">
        	<div class="shape w-64 h-64 bg-white rounded-full"></div>
        	<div class="shape w-32 h-32 bg-dhl-yellow rounded-lg transform rotate-45"></div>
        	<div class="shape w-48 h-48 bg-sos-blue rounded-full"></div>
    	</div>
        
        <!-- Morphing Blob -->
        <div class="morphing-blob"></div>
        
        <!-- Particles -->
        <div class="particle w-4 h-4 top-1/4 left-1/4" style="animation-delay: 0s;"></div>
        <div class="particle w-6 h-6 top-3/4 right-1/4" style="animation-delay: 2s;"></div>
        <div class="particle w-3 h-3 top-1/2 left-3/4" style="animation-delay: 4s;"></div>
        
        <div class="mt-20 relative z-10 text-center px-4 max-w-6xl mx-auto">
            <!-- Main Title -->
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 animate-fade-in">
                Votre <span class="gradient-text">Avenir</span> 
                <br>
                Commence <span class="text-dhl-yellow">Ici</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto animate-slide-up font-light leading-relaxed">
                Rejoignez la plateforme GoTeach Sénégal et transformez vos compétences en opportunités professionnelles concrètes
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 animate-scale-in">
                <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-gradient-to-r from-dhl-red to-dhl-yellow text-white text-lg font-semibold rounded-full btn-glow overflow-hidden">
                    <span class="relative z-10">Commencer maintenant</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-dhl-yellow to-dhl-red opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                
                <a href="#programme" class="px-8 py-4 glass text-white text-lg font-semibold rounded-full hover:scale-105 transition-transform duration-300">
                    Découvrir le programme
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
                <div class="stats-card p-6 rounded-2xl text-center" style="animation-delay: 0.2s;">
                    <div class="text-3xl font-bold text-white mb-2">37,000+</div>
                    <div class="text-white/80">Jeunes accompagnés</div>
                </div>
                <div class="stats-card p-6 rounded-2xl text-center" style="animation-delay: 0.4s;">
                    <div class="text-3xl font-bold text-white mb-2">55+</div>
                    <div class="text-white/80">Pays présents</div>
                </div>
                <div class="stats-card p-6 rounded-2xl text-center" style="animation-delay: 0.6s;">
                    <div class="text-3xl font-bold text-white mb-2">10,000+</div>
                    <div class="text-white/80">Volontaires DHL</div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce-slow">
            <div class="w-8 h-12 border-2 border-white/50 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50" id="programme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Les <span class="gradient-text">3 Piliers</span> de GoTeach
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Notre approche structurée pour vous accompagner vers le succès professionnel
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Expose -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-dhl-red to-pink-500 rounded-2xl flex items-center justify-center mb-6 shimmer-effect">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">EXPOSE</h3>
                    <p class="text-gray-600 mb-6">Découvrez le monde professionnel à travers des rencontres avec des experts, des visites d'entreprises et des témoignages inspirants.</p>
                    <div class="flex items-center text-dhl-red font-semibold">
                        <span>Explorer →</span>
                    </div>
                </div>
                
                <!-- Explore -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-dhl-yellow to-orange-500 rounded-2xl flex items-center justify-center mb-6 shimmer-effect">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">EXPLORE</h3>
                    <p class="text-gray-600 mb-6">Identifiez vos talents et passions grâce à nos outils d'évaluation et programmes de développement personnel adaptés.</p>
                    <div class="flex items-center text-dhl-red font-semibold">
                        <span>Découvrir →</span>
                    </div>
                </div>
                
                <!-- Practice -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-senegal-green to-green-600 rounded-2xl flex items-center justify-center mb-6 shimmer-effect">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"></path>
                                    </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">PRACTICE</h3>
                    <p class="text-gray-600 mb-6">Mettez en pratique vos compétences avec des stages, simulations d'entretien et projets réels en entreprise.</p>
                    <div class="flex items-center text-dhl-red font-semibold">
                        <span>Pratiquer →</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Final -->
    <section class="py-20 bg-gradient-to-r from-gray-900 via-purple-900 to-violet-900 relative overflow-hidden" id="opportunites">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Prêt à transformer votre avenir ?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Rejoignez des milliers de jeunes qui ont déjà changé leur vie grâce à GoTeach
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="group relative px-10 py-4 bg-gradient-to-r from-dhl-red to-dhl-yellow text-white text-xl font-bold rounded-full btn-glow overflow-hidden min-w-[200px]">
                    <span class="relative z-10">S'inscrire maintenant</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-dhl-yellow to-dhl-red opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                
                <a href="{{ route('login') }}" class="px-10 py-4 glass-dark text-white text-xl font-bold rounded-full hover:scale-105 transition-transform duration-300 min-w-[200px]">
                    Se connecter
                </a>
            </div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-dhl-yellow/20 rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-dhl-red/20 rounded-lg transform rotate-45 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-20 w-16 h-16 bg-white/10 rounded-full animate-pulse-slow"></div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12" id="contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-dhl-red to-dhl-yellow rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">GT</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">GoTeach Sénégal</h3>
                        </div>
                    </div>
                    <p class="text-gray-400">
                        Ensemble, construisons votre avenir professionnel.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Programme</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#programme" class="hover:text-white transition-colors">Expose</a></li>
                        <li><a href="#programme" class="hover:text-white transition-colors">Explore</a></li>
                        <li><a href="#programme" class="hover:text-white transition-colors">Practice</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Ressources</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">CV & Lettres</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Formations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Événements</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>SOS Village d'Enfants</li>
                        <li>Dakar, Sénégal</li>
                        <li>contact@goteach.sn</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 GoTeach Sénégal. Une initiative DHL x SOS Villages d'Enfants.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('backdrop-blur-md', 'bg-white/80');
                navbar.classList.remove('bg-white/75');
            } else {
                navbar.classList.remove('backdrop-blur-md', 'bg-white/80');
                navbar.classList.add('bg-white/75');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in, .stats-card, .feature-card').forEach(el => {
            observer.observe(el);
        });

        // Dynamic particle generation
        function createParticle() {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.width = Math.random() * 6 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            
            document.querySelector('.hero-bg').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 6000);
        }

        // Generate particles periodically
        setInterval(createParticle, 3000);

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.floating-shapes, .morphing-blob');
            parallaxElements.forEach(el => {
                const speed = 0.5;
                el.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Enhanced scroll animations
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.feature-card, .stats-card');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);

        // Add loading animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });

        // Magnetic effect for buttons
        document.querySelectorAll('.btn-glow').forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                button.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
            });
            
            button.addEventListener('mouseleave', () => {
                button.style.transform = '';
            });
        });

        console.log('🚀 GoTeach Sénégal - Plateforme chargée avec succès!');
        console.log('💼 Prêt à transformer des vies professionnelles!');
    </script>
    </body>
</html>
