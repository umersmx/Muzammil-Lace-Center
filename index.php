<?php
require_once __DIR__ . '/includes/header.php';
$featured = get_featured_products(8);
$categories = get_categories();
?>
<section class="section owner-section" style="background: linear-gradient(135deg, rgba(128, 0, 32, 0.05) 0%, rgba(212, 175, 55, 0.05) 100%); padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-5">
                <img src="assets/images/owner.jpg" alt="Owner Muzammil Lace Center" class="rounded-4 shadow-lg owner-photo">
            </div>
            <div class="col-lg-7">
                <span class="section-title text-gold">Meet the Founder</span>
                <h2><span class="lang-en">Owner & Founder of Muzammil Lace Center</span><span class="lang-ur" style="display:none;">مزمل لیس سنٹر کے بانی</span></h2>
                <h3 class="text-maroon mt-2">MUZAMMIL SHAHZAD</h3>
                <p><span class="lang-en">Leading Muzammil Lace Center with over 20 years of experience, our founder brings trusted quality and luxury style to every lace and accessory collection. His commitment ensures premium materials, fast service, and a beautiful shopping experience for designers and fashion customers.</span><span class="lang-ur" style="display:none;">مزمل شہزاد 20 سال سے زیادہ کے تجربے کے ساتھ مزمل لیس سنٹر کی قیادت کر رہے ہیں۔ وہ ہر لیس اور اکسیسری کالکشن میں قابلِ اعتماد معیار اور لگژری انداز لاتے ہیں۔ ان کی تعہد اعلیٰ معیار کے مواد، تیز رفتار سروس اور ڈیزائنرز اور فیشن کسٹمرز کے لیے خوبصورت شاپنگ کا تجربہ یقینی بناتی ہے۔</span></p>
                <div class="owner-badges d-flex flex-wrap gap-3 mt-4">
                    <div class="owner-card p-4"><h5>20+ <span class="lang-ur" style="display:none;">برسے</span></h5><p><span class="lang-en">Industry leadership</span><span class="lang-ur" style="display:none;">صنعت میں قیادت</span></p></div>
                    <div class="owner-card p-4"><h5><span class="lang-en">Designer Quality</span><span class="lang-ur" style="display:none;">ڈیزائنر کوالٹی</span></h5><p><span class="lang-en">Premium textile expertise</span><span class="lang-ur" style="display:none;">بہترین ٹیکسٹائل کی مہارت</span></p></div>
                    <div class="owner-card p-4"><h5><span class="lang-en">Trusted Service</span><span class="lang-ur" style="display:none;">قابلِ اعتماد سروس</span></h5><p><span class="lang-en">Customer-first support</span><span class="lang-ur" style="display:none;">کسٹمر پہلے سپورٹ</span></p></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="hero-section d-flex align-items-center text-white">
    <div class="container hero-content">
        <span class="section-title text-gold">Premium Textile & Lace Store</span>
        <h1>Pakistan's Trusted Lace Center Since 2004</h1>
        <p>Explore premium quality bridal laces, embroidery borders, decorative ribbons, fashion trims, garment accessories, and sewing materials.</p>
        <div class="d-flex flex-wrap gap-3">
            <a href="shop.php" class="btn btn-gold btn-lg">Shop Now</a>
            <a href="contact.php" class="btn btn-outline-maroon btn-lg">Contact Us</a>
            <a href="https://wa.me/923001234567" class="btn btn-light btn-lg"><i class="fa fa-whatsapp text-success"></i> WhatsApp Order</a>
        </div>
    </div>
</section>
<section class="section bg-white">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <img src="assets/images/hero-sample.svg" alt="Luxury Lace" class="rounded-4 shadow-lg">
            </div>
            <div class="col-lg-6">
                <h2>Luxury Lace & Fashion Accessories for Every Designer</h2>
                <p>Muzammil Lace Center has proudly served customers for over 20 years from Mian Channu. We specialize in premium lace collections, bridal accessories, embroidery borders, decorative ribbons, garment trims, sewing supplies, and fashion embellishments.</p>
                <div class="row text-center mt-4">
                    <div class="col-6 mb-3"><h3 class="text-maroon">20+</h3><p>Years Experience</p></div>
                    <div class="col-6 mb-3"><h3 class="text-maroon">10,000+</h3><p>Happy Customers</p></div>
                    <div class="col-6 mb-3"><h3 class="text-maroon">5,000+</h3><p>Products</p></div>
                    <div class="col-6 mb-3"><h3 class="text-maroon">100%</h3><p>Customer Satisfaction</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-title">Browse Categories</span>
                <h2>Shop by Collection</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">View All</a>
        </div>
        <div class="row gy-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-3">
                    <a href="shop.php?category=<?php echo esc($category['id']); ?>" class="category-card text-white" style="background-image:url('assets/images/category-<?php echo esc($category['id']); ?>.svg');">
                        <h5><?php echo esc($category['name']); ?></h5>
                        <p class="small">Premium <?php echo esc($category['name']); ?> collection</p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-title">Featured Selection</span>
                <h2>Top Lace Picks</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">Explore Shop</a>
        </div>
        <div class="row g-4 featured-grid">
            <?php foreach ($featured as $product): ?>
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($product['name']); ?>">
                        <div class="card-body">
                            <span class="badge bg-gold text-dark mb-2"><?php echo esc($product['category_name']); ?></span>
                            <h5 class="card-title"><?php echo esc($product['name']); ?></h5>
                            <p class="text-muted mb-2"><?php echo format_currency($product['price']); ?> <span class="text-decoration-line-through text-muted ms-2"><?php echo format_currency($product['sale_price']); ?></span></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div><i class="fa fa-star text-gold"></i> 4.8</div>
                                <a href="product.php?id=<?php echo esc($product['id']); ?>" class="btn btn-sm btn-outline-maroon">Quick View</a>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="cart.php?add=<?php echo esc($product['id']); ?>" class="btn btn-gold btn-sm">Add To Cart</a>
                                <a href="wishlist.php?add=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon btn-sm">Add To Wishlist</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row gy-4">
            <?php $advantages = ['20+ Years Experience', 'Premium Quality', 'Affordable Prices', 'Huge Variety', 'Fast Delivery', 'Trusted Service']; ?>
            <?php foreach ($advantages as $index => $value): ?>
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm">
                        <h5 class="text-maroon"><?php echo esc($value); ?></h5>
                        <p class="mb-0">Quality lace designs, dedicated customer support, and fast fulfillment for every order.</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-title">The Latest Reviews</span>
                <h2>Customer Love</h2>
            </div>
            <a href="account.php#orders" class="btn btn-outline-maroon">View Orders</a>
        </div>
        <div class="row gy-4">
            <?php $reviews = [
                ['name'=>'Ayesha Khan','text'=>'Beautiful lace quality and fast service. Perfect for my bridal collection.'],
                ['name'=>'Sara Ali','text'=>'Highly recommended for designers looking for premium trims and ribbons.'],
                ['name'=>'Zain Ahmed','text'=>'The embroidery borders are elegant and the pricing is great.'],
            ]; ?>
            <?php foreach ($reviews as $review): ?>
                <div class="col-md-4">
                    <div class="review-card">
                        <p>"<?php echo esc($review['text']); ?>"</p>
                        <strong><?php echo esc($review['name']); ?></strong>
                        <div class="text-gold mt-2"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-title">Visual Gallery</span>
                <h2>Elegant Lace Collections</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">Shop Now</a>
        </div>
        <div class="row gallery-grid">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="col-md-4 mb-4"><img src="assets/images/gallery-<?php echo $i; ?>.svg" alt="Gallery <?php echo $i; ?>"></div>
            <?php endfor; ?>
        </div>
    </div>
</section>
<section class="section bg-maroon text-white">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <small class="section-title text-gold">Special Offers</small>
                <h2>Celebrate with Exclusive Discounts</h2>
                <p>Grab bridal season offers, Eid collections, new arrivals, and bulk purchase discounts before the countdown ends.</p>
                <div class="countdown" id="countdown">
                    <div class="countdown-item"><span id="countdown-days">00</span>Days</div>
                    <div class="countdown-item"><span id="countdown-hours">00</span>Hours</div>
                    <div class="countdown-item"><span id="countdown-minutes">00</span>Minutes</div>
                    <div class="countdown-item"><span id="countdown-seconds">00</span>Seconds</div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="offer-card">
                    <h3>Wedding Season Sale</h3>
                    <p>Up to 25% off premium bridal lace sets and accessorries.</p>
                    <ul>
                        <li>Bulk order savings</li>
                        <li>Free shipping on orders over Rs 10,000</li>
                        <li>Complimentary styling advice</li>
                    </ul>
                    <a href="shop.php" class="btn btn-light btn-lg mt-3">Shop Offers</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
