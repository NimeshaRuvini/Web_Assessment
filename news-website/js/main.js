// LankaTimes — Main JS
document.addEventListener('DOMContentLoaded', function () {

    // Auto-update header date/time
    function updateTime() {
        const el = document.querySelector('.header-date div');
        if (el) {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            el.textContent = h + ':' + m + ' IST';
        }
    }
    setInterval(updateTime, 60000);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Image error fallback
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function () {
            this.src = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=60';
        });
    });

    // Newsletter form feedback
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    newsletterForms.forEach(form => {
        // Let form submit normally; feedback is on subscribe.php
    });

});
