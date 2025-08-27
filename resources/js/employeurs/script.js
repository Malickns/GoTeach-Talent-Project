// Variables globales
let currentNotificationCount = 3;
let searchPlaceholders = [
    'Rechercher des talents, compétences...',
    'Ex: Développeur PHP expérimenté',
    'Ex: Responsable Marketing Digital',
    'Ex: Comptable junior',
    'Ex: Designer UI/UX'
];
let currentPlaceholderIndex = 0;

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    setupEventListeners();
    startAnimations();
    initializeSearchPlaceholder();
    setupRealTimeUpdates();
    // removed async hydration and nav counts in revert
});

// Initialisation du dashboard
function initializeDashboard() {
    console.log('Dashboard employeur initialisé');
    
    // Animation des statistiques
    animateStatsOnScroll();
    
    // Initialisation du menu profil
    initializeProfileDropdown();
    
    // Initialisation des modals
    initializeModals();
    
    // Animation des cartes au hover
    setupCardHoverEffects();
}

// Configuration des écouteurs d'événements
function setupEventListeners() {
    // Recherche
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        searchBox.addEventListener('keypress', handleSearch);
        searchBox.addEventListener('focus', handleSearchFocus);
        searchBox.addEventListener('blur', handleSearchBlur);
    }

    // Bouton de recherche
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', handleSearchClick);
    }

    // Notifications
    const notificationBell = document.querySelector('.notifications');
    if (notificationBell) {
        notificationBell.addEventListener('click', handleNotificationClick);
    }

    // Actions rapides
    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('click', handleActionCardClick);
    });

    // Boutons des candidats
    document.querySelectorAll('#new-applications-list .btn-view').forEach(btn => {
        btn.addEventListener('click', handleViewCandidature);
    });

    document.querySelectorAll('#new-applications-list .btn-contact').forEach(btn => {
        btn.addEventListener('click', handleContactCandidate);
    });

    // Actions retenir / rejeter
    document.querySelectorAll('#new-applications-list [data-action="accept-candidature"]').forEach(btn => {
        btn.addEventListener('click', handleAcceptCandidature);
    });
    document.querySelectorAll('#new-applications-list [data-action="reject-candidature"]').forEach(btn => {
        btn.addEventListener('click', handleRejectCandidature);
    });

    // Navigation rapide
    document.querySelectorAll('.quick-nav a').forEach(link => {
        link.addEventListener('click', handleQuickNavClick);
    });
    // reverted: no postulations ajax wiring on dashboard-new
}

// Gestion de la recherche
function handleSearch(event) {
    if (event.key === 'Enter') {
        const searchTerm = event.target.value.trim();
        if (searchTerm !== '') {
            performSearch(searchTerm);
        }
    }
}

function handleSearchFocus(event) {
    event.target.style.boxShadow = 'inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 0 3px rgba(74, 111, 165, 0.2)';
}

function handleSearchBlur(event) {
    event.target.style.boxShadow = 'inset 0 1px 3px rgba(0, 0, 0, 0.05)';
}

function handleSearchClick() {
    const searchBox = document.querySelector('.search-box');
    const searchTerm = searchBox.value.trim();
    if (searchTerm !== '') {
        performSearch(searchTerm);
    }
}

function performSearch(searchTerm) {
    console.log('Recherche pour:', searchTerm);
    showToast(`Recherche en cours pour: "${searchTerm}"`, 'info');
    
    // Simulation d'une recherche
    setTimeout(() => {
        showToast(`Résultats trouvés pour "${searchTerm}"`, 'success');
    }, 2000);
}

// Gestion des notifications
function handleNotificationClick() {
    showToast('Notifications: 3 nouvelles candidatures', 'info');
    updateNotificationCount(Math.floor(Math.random() * 5) + 1);
}

function updateNotificationCount(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.classList.add('pulse');
        setTimeout(() => {
            badge.classList.remove('pulse');
        }, 1500);
    }
}

// Gestion du menu profil
function initializeProfileDropdown() {
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');
    
    if (profileToggle && profileMenu) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        // Fermer le menu en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
    }
}

// Gestion des cartes d'action
function handleActionCardClick(event) {
    const card = event.currentTarget;
    const action = card.querySelector('.action-title').textContent;
    
    // Animation de clic
    card.style.transform = 'scale(0.95)';
    setTimeout(() => {
        card.style.transform = '';
    }, 150);
    
    console.log('Action cliquée:', action);
}

// Gestion des profils de candidats
function handleViewProfile(event) {
    event.preventDefault();
    const candidateName = event.target.closest('.candidate-item').querySelector('.candidate-name').textContent;
    showToast(`Ouverture du profil de ${candidateName}`, 'info');
}

// Vue d'une candidature (nouveau pour dashboard)
function handleViewCandidature(event) {
    event.preventDefault();
    const item = event.target.closest('.candidate-item');
    const candidateName = item.querySelector('.candidate-name')?.textContent || 'candidat';
    showToast(`Ouverture de la candidature de ${candidateName}`, 'info');
}

function handleContactCandidate(event) {
    event.preventDefault();
    const candidateName = event.target.closest('.candidate-item').querySelector('.candidate-name').textContent;
    showToast(`Contact de ${candidateName} en cours...`, 'info');
    
    // Simulation d'envoi de message
    setTimeout(() => {
        showToast(`Message envoyé à ${candidateName}`, 'success');
    }, 1500);
}

// Retenir une candidature (UX simulée)
function handleAcceptCandidature(event) {
    event.preventDefault();
    const item = event.target.closest('.candidate-item');
    const id = item?.dataset?.postulationId;
    const name = item.querySelector('.candidate-name')?.textContent || 'candidat';
    // Simulation
    item.style.opacity = '0.6';
    showToast(`Candidature de ${name} retenue`, 'success');
    setTimeout(() => { item.style.opacity = '1'; }, 800);
}

// Rejeter une candidature (UX simulée)
function handleRejectCandidature(event) {
    event.preventDefault();
    const item = event.target.closest('.candidate-item');
    const id = item?.dataset?.postulationId;
    const name = item.querySelector('.candidate-name')?.textContent || 'candidat';
    // Simulation
    item.style.opacity = '0.6';
    showToast(`Candidature de ${name} rejetée`, 'warning');
    setTimeout(() => { item.style.opacity = '1'; }, 800);
}

// Gestion de la navigation rapide
function handleQuickNavClick(event) {
    const link = event.currentTarget;
    const isActive = link.classList.contains('active');
    
    // Retirer la classe active de tous les liens
    document.querySelectorAll('.quick-nav a').forEach(l => l.classList.remove('active'));
    
    // Ajouter la classe active au lien cliqué
    if (!isActive) {
        link.classList.add('active');
    }
}

// Initialisation des modals
function initializeModals() {
    // Fermer les modals en cliquant sur X
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });

    // Fermer les modals en cliquant à l'extérieur
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
}

// Fonctions pour ouvrir les modals
function openModal(modalType) {
    const modal = document.getElementById(modalType + 'Modal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modal) {
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Animation des statistiques
function animateStatsOnScroll() {
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                statsObserver.unobserve(entry.target);
            }
        });
    });

    const statsGrid = document.querySelector('.stats-grid');
    if (statsGrid) {
        statsObserver.observe(statsGrid);
    }
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
                stat.textContent = finalValue + (stat.textContent.includes('%') ? '%' : '');
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('%') ? '%' : '');
            }
        }, 50);
    });
}

// Effets de hover sur les cartes
function setupCardHoverEffects() {
    document.querySelectorAll('.action-card, .candidate-item, .stat-item').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Animation du placeholder de recherche
function initializeSearchPlaceholder() {
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        setInterval(() => {
            currentPlaceholderIndex = (currentPlaceholderIndex + 1) % searchPlaceholders.length;
            searchBox.placeholder = searchPlaceholders[currentPlaceholderIndex];
        }, 3000);
    }
}

// Mises à jour en temps réel
function setupRealTimeUpdates() {
    // Simulation de nouvelles notifications
    setInterval(() => {
        const randomCount = Math.floor(Math.random() * 3) + 1;
        if (Math.random() > 0.7) {
            updateNotificationCount(randomCount);
            showToast('Nouvelle candidature reçue', 'success');
        }
    }, 30000);

    // Simulation d'activité
    setInterval(() => {
        if (Math.random() > 0.8) {
            showToast('Nouveau talent disponible', 'info');
        }
    }, 45000);
}

// Chargement asynchrone des sections
let nextPostulationsUrl = null;

function hydrateAsyncSections() {
    loadNouveautes();
    loadOffres();
    loadTalents();
    loadActivite();
    loadPostulations(true);

    // Polling léger pour l'activité
    setInterval(loadActivite, 30000);
}

function loadNouveautes() {
    const el = document.getElementById('nouveautes-container');
    if (!el) return;
    fetch(el.dataset.endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(({ html }) => { el.innerHTML = html; })
        .catch(() => {});
}

function loadOffres() {
    const el = document.getElementById('offres-cards-container');
    if (!el) return;
    fetch(el.dataset.endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(({ html }) => { el.innerHTML = html; })
        .catch(() => {});
}

function loadTalents() {
    const el = document.getElementById('talents-container');
    if (!el) return;
    fetch(el.dataset.endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(({ html }) => { el.innerHTML = html; })
        .catch(() => {});
}

function loadActivite() {
    const el = document.getElementById('activite-container');
    if (!el) return;
    fetch(el.dataset.endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(({ html }) => { el.innerHTML = html; })
        .catch(() => {});
}

function loadPostulations(reset) {
    const list = document.getElementById('postulations-list');
    if (!list) return;
    const endpoint = new URL(reset || !nextPostulationsUrl ? list.dataset.endpoint : nextPostulationsUrl, window.location.origin);
    const statut = document.getElementById('filter-statut')?.value || '';
    const sort = document.getElementById('sort-postulations')?.value || 'recent';
    if (reset) {
        endpoint.searchParams.set('page', '1');
        endpoint.searchParams.set('sort', sort);
        if (statut) endpoint.searchParams.set('statut', statut); else endpoint.searchParams.delete('statut');
    }

    fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(({ html, next, counts }) => {
            if (reset) list.innerHTML = '';
            list.insertAdjacentHTML('beforeend', html);
            nextPostulationsUrl = next;
            const moreBtn = document.getElementById('load-more-postulations');
            if (moreBtn) moreBtn.style.display = next ? 'inline-flex' : 'none';

            // update badges
            if (counts) {
                const setBadge = (id, val) => { const el = document.getElementById(id); if (!el) return; const b = el.querySelector('.badge'); if (b) b.textContent = val; };
                setBadge('badge-total', counts.total);
                setBadge('badge-en-attente', counts.en_attente);
                setBadge('badge-retenu', counts.retenu);
                setBadge('badge-rejete', counts.rejete);
            }
        })
        .catch(() => {});
}

// Nav badges counters
function loadNavCounts() {
    fetch((document.body.dataset.countsEndpoint || '/api/employeurs/counts'), { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json())
        .then(({ candidatures, offres }) => {
            const c = document.getElementById('nav-badge-candidatures');
            const o = document.getElementById('nav-badge-offres');
            if (c) c.textContent = candidatures;
            if (o) o.textContent = offres;
        })
        .catch(() => {});
}

// Système de notifications toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Animation de sortie
    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

// Animations au démarrage
function startAnimations() {
    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.content-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation du header
    const header = document.querySelector('.employeur-header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            header.style.transition = 'all 0.5s ease-out';
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }
}

// Gestion du scroll
window.addEventListener('scroll', () => {
    const header = document.querySelector('.employeur-header');
    if (header) {
        if (window.scrollY > 20) {
            header.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
        }
    }
});

// Fonctions utilitaires
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export des fonctions pour utilisation globale
window.employeurDashboard = {
    openModal,
    closeModal,
    showToast,
    updateNotificationCount,
    performSearch
};

console.log('Script employeur dashboard chargé avec succès');
