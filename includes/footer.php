</main>
<footer class="site-footer bg-dark text-white pt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="text-gold">Muzammil Lace Center</h5>
                <p>Premium lace, bridal borders, fashion trims, and sewing accessories crafted for elegant designers and couture customers.</p>
            </div>
            <div class="col-md-3">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="track-order.php">Track Order</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Contact</h6>
                <p><i class="fa fa-map-marker-alt"></i> Allama Iqbal Road, Street 4, Mian Channu</p>
                <p><i class="fa fa-phone"></i> +92 300 1234567</p>
                <p><i class="fa fa-envelope"></i> info@muzammillacecenter.com</p>
            </div>
            <div class="col-md-2">
                <h6>Hours</h6>
                <p>Mon–Sat: 9am - 8pm</p>
                <p>Sun: Closed</p>
            </div>
        </div>
        <div class="text-center py-4 border-top border-secondary mt-4">
            <small>© <?php echo date('Y'); ?> Muzammil Lace Center. Designed for premium textile e-commerce.</small>
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
