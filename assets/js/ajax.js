const shopFilterState = {
    timeout: null,
    delay: 250
};

function showSkeletons(container) {
    if (!container) return;
    const skeletonHtml = Array.from({ length: 6 }).map(() => `
        <div class="col-md-4">
            <div class="card product-card product-skeleton">
                <div class="skeleton-image"></div>
                <div class="card-body">
                    <div class="skeleton-line short"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line button"></div>
                </div>
            </div>
        </div>
    `).join('');
    container.innerHTML = skeletonHtml;
}

function updateShopCount(count) {
    const countEl = document.querySelector('.shop-product-count');
    if (countEl) {
        countEl.textContent = `${count} products found`;
    }
}

function fetchShopProducts(params) {
    const resultsArea = document.querySelector('#searchResults');
    if (!resultsArea) return;

    showSkeletons(resultsArea);
    const url = 'shop.php?ajax=1&' + params.toString();

    fetch(url)
        .then(response => response.json())
        .then(data => {
            resultsArea.innerHTML = data.html;
            updateShopCount(data.count);
            initScrollAnimations();
            if (typeof initCartButtonInteractions === 'function') {
                initCartButtonInteractions();
            }
            history.replaceState(null, '', 'shop.php?' + params.toString());
        })
        .catch(() => {
            resultsArea.innerHTML = '<div class="col-12"><div class="alert alert-danger">Something went wrong. Please refresh to try again.</div></div>';
        });
}

function updateSearchField() {
    const searchInput = document.querySelector('#shopSearch');
    const filterForm = document.querySelector('#filterForm');
    if (!searchInput || !filterForm) return;

    searchInput.addEventListener('input', function () {
        clearTimeout(shopFilterState.timeout);
        shopFilterState.timeout = setTimeout(() => {
            const params = new URLSearchParams(new FormData(filterForm));
            fetchShopProducts(params);
        }, shopFilterState.delay);
    });

    filterForm.addEventListener('change', function () {
        clearTimeout(shopFilterState.timeout);
        shopFilterState.timeout = setTimeout(() => {
            const params = new URLSearchParams(new FormData(filterForm));
            fetchShopProducts(params);
        }, shopFilterState.delay);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateSearchField();
    initCartButtonInteractions();
});

function initCartButtonInteractions() {
    document.querySelectorAll('.ajax-add-cart, .ajax-add-wishlist').forEach(btn => {
        btn.removeEventListener('click', handleAjaxAdd);
        btn.addEventListener('click', handleAjaxAdd);
    });
}

function handleAjaxAdd(e) {
    e.preventDefault();
    const btn = e.currentTarget;
    let url = btn.href || btn.dataset.url;
    if (!url) return;
    
    url += (url.includes('?') ? '&' : '?') + 'ajax=1';
    const isCart = btn.classList.contains('ajax-add-cart');

    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    btn.classList.add('disabled');
    btn.style.pointerEvents = 'none';

    fetch(url)
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.classList.remove('disabled');
            btn.style.pointerEvents = 'auto';
            
            if (data.success) {
                const badge = document.getElementById(isCart ? 'cart-badge' : 'wishlist-badge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.classList.remove('pulse');
                    void badge.offsetWidth;
                    badge.classList.add('pulse');
                }
                showToast(data.message, 'success');
            } else if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showToast(data.message || 'Error occurred', 'error');
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.classList.remove('disabled');
            btn.style.pointerEvents = 'auto';
            showToast('Network error', 'error');
        });
}

function showToast(message, type) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.position = 'fixed';
        container.style.bottom = '1rem';
        container.style.right = '1rem';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type} visible mt-2`;
    toast.innerHTML = `<i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
