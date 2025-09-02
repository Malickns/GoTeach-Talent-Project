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
    if (searchBox) {
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

        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                if (searchTerm.trim() !== '') {
                    window.location.href = '{{ route("jeunes.offres") }}?search=' + encodeURIComponent(searchTerm);
                }
            }
        });
    }

    document.querySelectorAll('.offre-card, .stat-item').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
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
        setTimeout(() => { toast.style.transform = 'translateX(0)'; }, 100);
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => { document.body.removeChild(toast); }, 300);
        }, 3000);
    }

    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif
</script>


