document.addEventListener('DOMContentLoaded', function () {
    // Countdown Timer
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
        countdownItems.days.textContent = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
        countdownItems.hours.textContent = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
        countdownItems.minutes.textContent = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        countdownItems.seconds.textContent = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
    }
    if (countdownItems.days) {
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // enablePageTransitions();
    initScrollAnimations();
    initHeroParallax();
    initButtonMicroInteractions();
    initHeaderScroll();
    initBackToTop();
    initCounterAnimations();
});

// Page Transitions (Disabled for speed/reliability)
function enablePageTransitions() {
    // No page transition delays
}

// Scroll Animations (IntersectionObserver)
function initScrollAnimations() {
    const items = document.querySelectorAll('.animate-on-scroll, section.section');
    if (!items.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -80px 0px', threshold: 0.08 });

    items.forEach(item => observer.observe(item));
}

// Parallax Hero
function initHeroParallax() {
    const hero = document.querySelector('.hero-section');
    if (!hero) return;
    window.addEventListener('scroll', () => {
        const offset = window.scrollY * 0.15;
        hero.style.backgroundPosition = `center calc(50% + ${offset}px)`;
    }, { passive: true });
}

// Button Hover Micro-Interactions
function initButtonMicroInteractions() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => btn.classList.add('btn-hovered'));
        btn.addEventListener('mouseleave', () => btn.classList.remove('btn-hovered'));
    });
}

// Header Shrink on Scroll
function initHeaderScroll() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 60) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

// Back to Top Button
function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    }, { passive: true });

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Animated Counters
function initCounterAnimations() {
    const counters = document.querySelectorAll('.counter-value');
    if (!counters.length) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
}

function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    const duration = 2000;
    const start = performance.now();
    const suffix = target >= 1000 ? '+' : (target === 100 ? '%' : '+');

    function tick(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 4);
        const current = Math.round(eased * target);

        if (target >= 1000) {
            el.textContent = current.toLocaleString() + suffix;
        } else {
            el.textContent = current + suffix;
        }

        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    }

    requestAnimationFrame(tick);
}

// Image Lazy Load (Standard behavior)
// (Removed transition opacity to prevent flashing)
