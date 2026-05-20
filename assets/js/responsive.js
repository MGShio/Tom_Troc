/**
 * Responsive Navigation - TomTroc
 * Gestion du menu mobile, sidebar
 */

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initChatOverlay();
    initFilterOverlay();
});

/**
 * Initialisation du menu hamburger mobile
 */
function initMobileMenu() {
    const hamburger = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.mobile-nav-sidebar');
    const overlay = document.querySelector('.mobile-nav-overlay');
    const closeBtn = document.querySelector('.mobile-nav-sidebar .close-btn');
    
    // Si le menu hamburger n'existe pas, on sort
    if (!hamburger || !sidebar) return;
    
    // Toggle menu au clic sur le hamburger
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    });
    
    // Fermer au clic sur le bouton fermeture
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            hamburger.classList.remove('active');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // Fermer au clic sur l'overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            hamburger.classList.remove('active');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
}

/**
 * Initialisation de l'overlay chat pour mobile
 */
function initChatOverlay() {
    const chatMain = document.querySelector('.messagerie-main');
    const conversationItems = document.querySelectorAll('.conversation-item');
    
    if (!chatMain || !conversationItems.length) return;
    
    // Clic sur une conversation pour ouvrir le chat
    conversationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (window.innerWidth <= 767) {
                e.preventDefault();
                // Récupérer l'URL du lien
                const link = this.querySelector('a');
                if (link) {
                    // Charger le chat via AJAX ou navigation
                    // Pour l'instant, on simule l'ouverture
                    chatMain.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    });
}

/**
 * Initialisation de l'overlay filtres pour mobile
 */
function initFilterOverlay() {
    const filterBtn = document.querySelector('.filter-btn');
    const filterOverlay = document.querySelector('.filter-overlay');
    const filterPanel = document.querySelector('.filter-panel');
    const closeFilterBtn = document.querySelector('.filter-panel .close-btn');
    
    if (!filterBtn || !filterOverlay || !filterPanel) return;
    
    // Ouvrir les filtres
    filterBtn.addEventListener('click', function() {
        filterOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    // Fermer les filtres
    filterOverlay.addEventListener('click', function(e) {
        if (e.target === filterOverlay) {
            filterOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Bouton fermer dans le panel
    if (closeFilterBtn) {
        closeFilterBtn.addEventListener('click', function() {
            filterOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
}

/**
 * Adaptation des images pour le mobile
 */
function optimizeImagesForMobile() {
    if (window.innerWidth <= 767) {
        const images = document.querySelectorAll('img[data-src-mobile]');
        images.forEach(img => {
            img.src = img.dataset.srcMobile;
        });
    }
}

// Appeler au chargement et au resize
window.addEventListener('load', optimizeImagesForMobile);
window.addEventListener('resize', optimizeImagesForMobile);
