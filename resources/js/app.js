import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggles = document.querySelectorAll('.sidebar-toggle');

    if (sidebar) {
        sidebarToggles.forEach(function(btn) {
            btn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
            });
        });
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
    }

    const faqItems = document.querySelectorAll('.faq-item .faq-question');
    faqItems.forEach(function(q) {
        q.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            item.classList.toggle('active');
        });
    });

    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true, offset: 40 });
    }
});
