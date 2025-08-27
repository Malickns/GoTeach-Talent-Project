/**
 * Script pour la page d'inscription GoTeach
 * Gère les interactions utilisateur et la validation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const typeButtons = document.querySelectorAll('.type-btn');
    const registrationForms = document.querySelectorAll('.registration-form');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    // Initialisation
    initTypeSelector();
    initPasswordToggles();
    initFormValidation();
    initFloatingCards();
    initKeyboardHandlers();
    initAjaxValidations();
    
    /**
     * Gestion du sélecteur de type de compte
     */
    function initTypeSelector() {
        typeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                
                // Mettre à jour les boutons
                typeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Afficher le formulaire correspondant
                registrationForms.forEach(form => {
                    form.classList.remove('active');
                    if (form.id === type + 'Form') {
                        form.classList.add('active');
                    }
                });
                
                // Réinitialiser reCAPTCHA
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            });
        });
    }
    
    /**
     * Gestion des toggles de mot de passe
     */
    function initPasswordToggles() {
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput) {
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
                }
            });
        });
    }
    
    /**
     * Validation en temps réel des formulaires
     */
    function initFormValidation() {
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Animation des champs de saisie
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                validateField(this);
            });
            
            // Validation en temps réel
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
                
                // Validation en temps réel pour certains champs
                if (this.type === 'email' || this.name === 'telephone') {
                    validateField(this);
                }
            });
        });
        
        // Gestion de la soumission des formulaires
        registrationForms.forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });
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
        
        // Validation selon le type de champ
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value.length > 0 && emailRegex.test(value)) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
        
        if (field.type === 'tel' || field.name === 'telephone') {
            const phoneRegex = /^[0-9+\-\s\(\)]+$/;
            if (value.length > 0 && phoneRegex.test(value) && value.length >= 8) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
        
        if (field.type === 'password') {
            if (value.length >= 8) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
        
        if (field.type === 'date') {
            const date = new Date(value);
            const today = new Date();
            const age = today.getFullYear() - date.getFullYear();
            
            if (value.length > 0 && age >= 16) {
                wrapper.classList.add('valid');
            } else if (value.length > 0) {
                wrapper.classList.add('invalid');
                field.classList.add('error');
            }
        }
    }
    
    /**
     * Gestion de la soumission des formulaires
     */
    function handleFormSubmit(e) {
        const form = e.target;
        const formData = new FormData(form);
        
        // Validation côté client
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                field.parentElement.classList.add('invalid');
            }
        });
        
        // Validation du mot de passe
        const password = form.querySelector('input[name="password"]');
        const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');
        
        if (password && passwordConfirmation) {
            if (password.value !== passwordConfirmation.value) {
                isValid = false;
                passwordConfirmation.classList.add('error');
                passwordConfirmation.parentElement.classList.add('invalid');
                showMessage('Les mots de passe ne correspondent pas', 'error');
            }
        }
        
        // Validation des conditions d'utilisation
        const terms = form.querySelector('input[name="terms"]');
        if (terms && !terms.checked) {
            isValid = false;
            showMessage('Vous devez accepter les conditions d\'utilisation', 'error');
        }
        
        if (!isValid) {
            e.preventDefault();
            showMessage('Veuillez corriger les erreurs dans le formulaire', 'error');
            return;
        }
        
        // Afficher le loading
        showLoading();
    }
    
    /**
     * Validation AJAX des champs uniques
     */
    function initAjaxValidations() {
        // Validation email
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkEmailAvailability(this.value, this);
                }, 500);
            });
        });
        
        // Validation RCCM
        const rccmInput = document.getElementById('employeur_numero_rccm');
        if (rccmInput) {
            let timeout;
            rccmInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkRccmAvailability(this.value, this);
                }, 500);
            });
        }
        
        // Validation NINEA
        const nineaInput = document.getElementById('employeur_numero_ninea');
        if (nineaInput) {
            let timeout;
            nineaInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkNineaAvailability(this.value, this);
                }, 500);
            });
        }
    }
    
    /**
     * Vérifier la disponibilité d'un email
     */
    function checkEmailAvailability(email, input) {
        if (!email || email.length < 3) return;
        
        fetch('/check-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            const wrapper = input.parentElement;
            
            if (data.available) {
                wrapper.classList.remove('invalid');
                wrapper.classList.add('valid');
                input.classList.remove('error');
            } else {
                wrapper.classList.remove('valid');
                wrapper.classList.add('invalid');
                input.classList.add('error');
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification email:', error);
        });
    }
    
    /**
     * Vérifier la disponibilité d'un numéro RCCM
     */
    function checkRccmAvailability(rccm, input) {
        if (!rccm || rccm.length < 3) return;
        
        fetch('/check-rccm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ numero_rccm: rccm })
        })
        .then(response => response.json())
        .then(data => {
            const wrapper = input.parentElement;
            
            if (data.available) {
                wrapper.classList.remove('invalid');
                wrapper.classList.add('valid');
                input.classList.remove('error');
            } else {
                wrapper.classList.remove('valid');
                wrapper.classList.add('invalid');
                input.classList.add('error');
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification RCCM:', error);
        });
    }
    
    /**
     * Vérifier la disponibilité d'un numéro NINEA
     */
    function checkNineaAvailability(ninea, input) {
        if (!ninea || ninea.length < 3) return;
        
        fetch('/check-ninea', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ numero_ninea: ninea })
        })
        .then(response => response.json())
        .then(data => {
            const wrapper = input.parentElement;
            
            if (data.available) {
                wrapper.classList.remove('invalid');
                wrapper.classList.add('valid');
                input.classList.remove('error');
            } else {
                wrapper.classList.remove('valid');
                wrapper.classList.add('invalid');
                input.classList.add('error');
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification NINEA:', error);
        });
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
                const activeForm = document.querySelector('.registration-form.active');
                if (activeForm) {
                    activeForm.dispatchEvent(new Event('submit'));
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
    }
    
    /**
     * Cache le loading overlay
     */
    function hideLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('active');
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
        
        // Styles pour le message
        Object.assign(messageDiv.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '1rem 1.5rem',
            borderRadius: '8px',
            color: 'white',
            fontWeight: '500',
            zIndex: '10000',
            animation: 'slideIn 0.3s ease-out',
            maxWidth: '300px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            fontFamily: 'Inter, sans-serif'
        });
        
        // Couleurs selon le type
        if (type === 'success') {
            messageDiv.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        } else if (type === 'error') {
            messageDiv.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        } else {
            messageDiv.style.background = 'linear-gradient(135deg, #3b82f6, #2563eb)';
        }
        
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
                const input = error.previousElementSibling?.querySelector('input, select, textarea');
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
    window.GoTeachRegister = {
        showMessage,
        showLoading,
        hideLoading,
        validateField
    };
}); 