</main>
<!-- Mobile Bottom Nav -->
<nav class="mobile-bottom-nav d-md-none">
    <a href="index.php" class="<?php echo $currentPage==='index.php' ? 'active' : ''; ?>"><i class="fa fa-home"></i> Home</a>
    <a href="shop.php" class="<?php echo $currentPage==='shop.php' ? 'active' : ''; ?>"><i class="fa fa-store"></i> Shop</a>
    <a href="cart.php" class="<?php echo $currentPage==='cart.php' ? 'active' : ''; ?>"><i class="fa fa-shopping-bag"></i> Cart</a>
    <a href="wishlist.php" class="<?php echo $currentPage==='wishlist.php' ? 'active' : ''; ?>"><i class="fa fa-heart"></i> Wishlist</a>
    <a href="<?php echo is_logged_in() ? 'account.php' : 'login.php'; ?>" class="<?php echo in_array($currentPage, ['account.php','login.php']) ? 'active' : ''; ?>"><i class="fa fa-user"></i> Account</a>
</nav>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" title="Back to top"><i class="fa fa-arrow-up"></i></button>

<footer class="site-footer text-white pt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <h5 style="font-size:1.4rem;">Muzammil Lace Center</h5>
                <p class="mt-3">Premium lace, bridal borders, fashion trims, and sewing accessories crafted for elegant designers and couture customers since 2004.</p>
                <div class="footer-social mt-3">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/923001234567" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php">Home</a></li>
                    <li class="mb-2"><a href="shop.php">Shop</a></li>
                    <li class="mb-2"><a href="about.php">About Us</a></li>
                    <li class="mb-2"><a href="blog.php">Blog</a></li>
                    <li class="mb-2"><a href="contact.php">Contact</a></li>
                    <li class="mb-2"><a href="track-order.php">Track Order</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="mb-3">Contact Info</h6>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-2" style="color:var(--secondary-light);"></i>Allama Iqbal Road, Street 4, Mian Channu</p>
                <p class="mb-2"><i class="fa fa-phone me-2" style="color:var(--secondary-light);"></i><a href="tel:+923001234567">+92 300 1234567</a></p>
                <p class="mb-2"><i class="fa fa-envelope me-2" style="color:var(--secondary-light);"></i><a href="mailto:info@muzammillacecenter.com">info@muzammillacecenter.com</a></p>
                <p class="mb-0"><i class="fa fa-clock me-2" style="color:var(--secondary-light);"></i>Mon–Sat: 9am – 8pm</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="mb-3">Newsletter</h6>
                <p>Subscribe for exclusive deals, new arrivals, and styling tips.</p>
                <form class="d-flex mt-2" onsubmit="event.preventDefault(); this.querySelector('input').value=''; alert('Subscribed!');">
                    <input type="email" class="form-control newsletter-input" placeholder="Your email" required>
                    <button class="btn btn-gold" style="border-radius:0 var(--radius-pill) var(--radius-pill) 0; padding: 0.7rem 1.2rem;"><i class="fa fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <div class="text-center py-4 border-top mt-4" style="border-color:rgba(255,255,255,0.08)!important;">
            <small style="color:rgba(255,255,255,0.4);">© <?php echo date('Y'); ?> Muzammil Lace Center. Crafted with <i class="fa fa-heart" style="color:var(--secondary);font-size:0.7em;"></i> for premium textile e-commerce.</small>
        </div>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/ajax.js"></script><script>
(function() {
    const langToggle = document.getElementById('langToggle');
    if (!langToggle) return;

    const currentLang = getCookie('site_language') || 'en';
    applyLanguage(currentLang);

    langToggle.addEventListener('click', function() {
        const newLang = currentLang === 'en' ? 'ur' : 'en';
        setCookie('site_language', newLang, 365);
        location.reload();
    });

    function applyLanguage(lang) {
        document.querySelectorAll('.lang-en, .lang-ur').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('.lang-' + lang).forEach(el => {
            el.style.display = '';
        });
        if (lang === 'ur') {
            document.documentElement.dir = 'rtl';
            document.documentElement.lang = 'ur';
        } else {
            document.documentElement.dir = 'ltr';
            document.documentElement.lang = 'en';
        }
    }

    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
        }
        return null;
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
    }
})();
</script></body>
</html>
