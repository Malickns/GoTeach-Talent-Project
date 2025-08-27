/**
 * Script pour la page de connexion GoTeach
 * Gère les interactions utilisateur et la validation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loginBtn = document.getElementById('loginBtn');
    
    // Initialisation
    initPasswordToggle();
    initInputAnimations();
    initFormValidation();
    initFloatingCards();
    initKeyboardHandlers();
    
    /**
     * Gestion du toggle de visibilité du mot de passe
     */
    function initPasswordToggle() {
        if (!togglePassword || !passwordInput) return;
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    }
    
    /**
     * Animations des champs de saisie
     */
    function initInputAnimations() {
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
            
            // Animation de frappe
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    }
    
    /**
     * Validation en temps réel des champs
     */
    function initFormValidation() {
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                validateField(this);
            });
        }
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                validateField(this);
            });
        }
        
        if (loginForm) {
            loginForm.addEventListener('submit', handleFormSubmit);
        }
    }
    
    /**
     * Valide un champ spécifique
     */
    function validateField(field) {
        const value = field.value.trim();
        const wrapper = field.parentElement;
        
        // Supprimer les classes d'erreur existantes
        wrapper.classList.remove('valid', 'invalid');
        field.classList.remove('error');
        
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value.length > 0 && emailRegex.test(value)) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
        
        if (field.type === 'password') {
            if (value.length >= 6) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
    }
    
    /**
     * Gestion de la soumission du formulaire
     */
    function handleFormSubmit(e) {
        // Validation côté client
        const email = emailInput ? emailInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value.trim() : '';
        
        if (!email || !password) {
            e.preventDefault();
            showMessage('Veuillez remplir tous les champs', 'error');
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showMessage('Veuillez entrer une adresse email valide', 'error');
            return;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            showMessage('Le mot de passe doit contenir au moins 6 caractères', 'error');
            return;
        }
        
        // Afficher le loading
        showLoading();
    }
    
    /**
     * Animation des cartes flottantes
     */
    function initFloatingCards() {
        const floatingCards = document.querySelectorAll('.floating-card');
        
        floatingCards.forEach((card, index) => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
                this.style.boxShadow = '0 10px 25px rgba(59, 130, 246, 0.3)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Effet de parallaxe pour les cartes
        initParallaxEffect(floatingCards);
    }
    
    /**
     * Effet de parallaxe pour les cartes flottantes
     */
    function initParallaxEffect(cards) {
        let mouseX = 0;
        let mouseY = 0;
        let isMouseMoving = false;
        let mouseTimeout;
        
        document.addEventListener('mousemove', function(e) {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
            isMouseMoving = true;
            
            clearTimeout(mouseTimeout);
            mouseTimeout = setTimeout(() => {
                isMouseMoving = false;
            }, 150);
            
            requestAnimationFrame(() => updateCardsPosition(cards, mouseX, mouseY, isMouseMoving));
        });
    }
    
    /**
     * Met à jour la position des cartes
     */
    function updateCardsPosition(cards, mouseX, mouseY, isMouseMoving) {
        if (!isMouseMoving) return;
        
        cards.forEach((card, index) => {
            const speed = (index + 1) * 0.8;
            const rotationSpeed = (index + 1) * 0.3;
            
            const x = mouseX * speed;
            const y = mouseY * speed;
            const rotation = (mouseX + mouseY) * rotationSpeed;
            
            card.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
        });
    }
    
    /**
     * Gestion des raccourcis clavier
     */
    function initKeyboardHandlers() {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                e.preventDefault();
                if (loginForm) {
                    loginForm.dispatchEvent(new Event('submit'));
                }
            }
            
            if (e.key === 'Escape') {
                document.activeElement.blur();
            }
        });
    }
    
    /**
     * Affiche le loading overlay
     */
    function showLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.add('active');
        }
        if (loginBtn) {
            loginBtn.disabled = true;
        }
    }
    
    /**
     * Cache le loading overlay
     */
    function hideLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('active');
        }
        if (loginBtn) {
            loginBtn.disabled = false;
        }
    }
    
    /**
     * Affiche un message de notification
     */
    function showMessage(message, type = 'info') {
        // Supprimer les messages existants
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => {
            if (document.body.contains(msg)) {
                document.body.removeChild(msg);
            }
        });
        
        // Créer le message
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;
        
        document.body.appendChild(messageDiv);
        
        // Supprimer le message après 3 secondes
        setTimeout(() => {
            if (document.body.contains(messageDiv)) {
                messageDiv.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (document.body.contains(messageDiv)) {
                        document.body.removeChild(messageDiv);
                    }
                }, 300);
            }
        }, 3000);
    }
    
    /**
     * Gestion des erreurs de validation côté serveur
     */
    function handleServerErrors() {
        // Vérifier s'il y a des erreurs de validation
        const errorMessages = document.querySelectorAll('.error-message');
        if (errorMessages.length > 0) {
            errorMessages.forEach(error => {
                const input = error.previousElementSibling?.querySelector('input');
                if (input) {
                    input.classList.add('error');
                    input.parentElement.classList.add('invalid');
                }
            });
        }
    }
    
    // Gérer les erreurs de validation côté serveur au chargement
    handleServerErrors();
    
    // Exposer les fonctions pour un usage externe si nécessaire
    window.GoTeachLogin = {
        showMessage,
        showLoading,
        hideLoading,
        validateField
    };
}); 