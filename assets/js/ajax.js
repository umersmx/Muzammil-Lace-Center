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

document.addEventListener('DOMContentLoaded', updateSearchField);
