document.addEventListener('DOMContentLoaded', function () {
    const countdownTarget = new Date();
    countdownTarget.setDate(countdownTarget.getDate() + 9);

    const countdownItems = {
        days: document.querySelector('#countdown-days'),
        hours: document.querySelector('#countdown-hours'),
        minutes: document.querySelector('#countdown-minutes'),
        seconds: document.querySelector('#countdown-seconds')
    };

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = countdownTarget - now;
        if (distance < 0) return;
        countdownItems.days.textContent = Math.floor(distance / (1000 * 60 * 60 * 24));
        countdownItems.hours.textContent = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        countdownItems.minutes.textContent = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        countdownItems.seconds.textContent = Math.floor((distance % (1000 * 60)) / 1000);
    }
    if (countdownItems.days) {
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    enablePageTransitions();
    initScrollAnimations();
    initHeroParallax();
    initCartButtonInteractions();
    initButtonMicroInteractions();

    const quickViewButtons = document.querySelectorAll('[data-quick-view]');
    quickViewButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = btn.dataset.quickView;
            fetch('product.php?id=' + productId)
                .then(response => response.text())
                .then(html => {
                    const modalHolder = document.createElement('div');
                    modalHolder.innerHTML = html;
                    document.body.appendChild(modalHolder);
                });
        });
    });
});

function enablePageTransitions() {
    const body = document.body;
    body.classList.add('page-transition-enter');
    requestAnimationFrame(() => body.classList.add('page-transition-ready'));

    document.querySelectorAll('a[href]').forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || link.target === '_blank' || link.dataset.noTransition) {
            return;
        }
        const isExternal = href.startsWith('http') && !href.includes(window.location.hostname);
        if (isExternal) return;

        link.addEventListener('click', function (event) {
            if (href.startsWith('javascript:')) return;
            event.preventDefault();
            body.classList.remove('page-transition-ready');
            body.classList.add('page-transition-exit');
            setTimeout(() => {
                window.location.href = href;
            }, 220);
        });
    });
}

function initScrollAnimations() {
    const items = document.querySelectorAll('.animate-on-scroll, section');
    if (!items.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -120px 0px', threshold: 0.12 });

    items.forEach(item => observer.observe(item));
}

function initHeroParallax() {
    const hero = document.querySelector('.hero-section');
    if (!hero) return;
    window.addEventListener('scroll', () => {
        const offset = window.scrollY * 0.18;
        hero.style.backgroundPosition = `center calc(50% + ${offset}px)`;
    }, { passive: true });
}

function initButtonMicroInteractions() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => btn.classList.add('btn-hovered'));
        btn.addEventListener('mouseleave', () => btn.classList.remove('btn-hovered'));
    });
}

function initCartButtonInteractions() {
    const cartButtons = Array.from(document.querySelectorAll('a.btn-gold[href*="cart.php?add="]'));
    if (!cartButtons.length) return;

    cartButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const url = new URL(button.href, window.location.origin);
            const productId = url.searchParams.get('add');
            if (!productId) {
                window.location.href = button.href;
                return;
            }

            const requestUrl = 'cart.php?ajax=1&add=' + encodeURIComponent(productId);
            fetch(requestUrl, { credentials: 'same-origin' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartBadge(data.count);
                        showToast('Added to cart', 'success');
                    } else {
                        showToast(data.message || 'Unable to add to cart', 'error');
                    }
                })
                .catch(() => {
                    showToast('Unable to add to cart', 'error');
                });
        });
    });
}

function updateCartBadge(count) {
    const badge = document.querySelector('.site-header .badge.bg-maroon');
    if (!badge) return;
    badge.textContent = count;
    badge.classList.add('pulse');
    setTimeout(() => badge.classList.remove('pulse'), 500);
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('visible'));
    setTimeout(() => {
        toast.classList.remove('visible');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }, 2200);
}
