<?php
require_once __DIR__ . '/includes/header.php';
$featured = get_featured_products(8);
$categories = get_categories();
?>

<section class="hero-section text-white">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6 hero-content">
                <span class="section-title text-gold-light">
                    <span class="lang-en">✦ Premium Textile & Lace Store ✦</span>
                    <span class="lang-ur" style="display:none;">✦ پریمیم ٹیکسٹائل اور لیس اسٹور ✦</span>
                </span>
                <h1 class="hero-title">
                    <span class="lang-en">Pakistan's Trusted Lace Center <span class="text-gold-gradient">Since 2004</span></span>
                    <span class="lang-ur" style="display:none;">پاکستان کا قابلِ اعتماد لیس سینٹر <span class="text-gold-gradient">2004 سے</span></span>
                </h1>
                <p class="hero-desc">
                    <span class="lang-en">Explore our curated collection of premium quality bridal laces, intricate embroidery borders, decorative ribbons, and luxury garment embellishments crafted for master designers.</span>
                    <span class="lang-ur" style="display:none;">ماسٹر ڈیزائنرز کے لیے تیار کردہ پریمیم کوالٹی کے عروسی لیس، پیچیدہ کڑھائی کی سرحدیں، آرائشی ربن اور پرتعیش ملبوسات کے زیورات کے ہمارے تیار کردہ مجموعہ کو دیکھیں۔</span>
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="shop.php" class="btn btn-gold btn-lg">
                        <span class="lang-en"><i class="fa fa-shopping-bag me-2"></i>Shop Collection</span>
                        <span class="lang-ur" style="display:none;"><i class="fa fa-shopping-bag me-2"></i>شاپ کلیکشن</span>
                    </a>
                    <a href="https://wa.me/923001234567" class="btn btn-outline-gold btn-lg">
                        <span class="lang-en"><i class="fab fa-whatsapp me-2"></i>WhatsApp Order</span>
                        <span class="lang-ur" style="display:none;"><i class="fab fa-whatsapp me-2"></i>واٹس ایپ آرڈر</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-showcase-wrapper">
                    <div class="hero-showcase-card">
                        <img src="assets/images/hero-lace.png" alt="Luxury Bridal Lace Showcase" class="hero-showcase-img">
                        <div class="hero-showcase-badge">
                            <span>✦ PREMIUM SELECTION ✦</span>
                        </div>
                    </div>
                    <div class="hero-showcase-frame-1"></div>
                    <div class="hero-showcase-frame-2"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section" style="background:var(--surface);">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <img src="assets/images/hero-sample.svg" alt="Luxury Lace" class="rounded-4 shadow-lg" loading="lazy">
            </div>
            <div class="col-lg-6">
                <span class="section-title">Our Story</span>
                <h2>Luxury Lace & Fashion Accessories for Every Designer</h2>
                <p>Muzammil Lace Center has proudly served customers for over 20 years from Mian Channu. We specialize in premium lace collections, bridal accessories, embroidery borders, decorative ribbons, garment trims, sewing supplies, and fashion embellishments.</p>
                <div class="row text-center mt-4">
                    <div class="col-6 mb-3"><h3 class="counter-value" data-target="20">0</h3><p class="text-muted mb-0">Years Experience</p></div>
                    <div class="col-6 mb-3"><h3 class="counter-value" data-target="10000">0</h3><p class="text-muted mb-0">Happy Customers</p></div>
                    <div class="col-6 mb-3"><h3 class="counter-value" data-target="5000">0</h3><p class="text-muted mb-0">Products</p></div>
                    <div class="col-6 mb-3"><h3 class="counter-value" data-target="100">0</h3><p class="text-muted mb-0" style="font-size:0.9rem;">Customer Satisfaction</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section" style="background:var(--bg-warm);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-title">Browse Categories</span>
                <h2>Shop by Collection</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">View All <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row gy-4">
            <?php foreach ($categories as $i => $category): ?>
                <div class="col-md-3 animate-on-scroll stagger-<?php echo $i + 1; ?>">
                    <a href="shop.php?category=<?php echo esc($category['id']); ?>" class="category-card text-white" style="background-image:url('assets/images/category-<?php echo esc($category['id']); ?>.svg');">
                        <h5><?php echo esc($category['name']); ?></h5>
                        <p class="small mb-0" style="position:relative;z-index:1;opacity:0.8;">Premium <?php echo esc($category['name']); ?> collection</p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section" style="background:var(--bg);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-title">Featured Selection</span>
                <h2>Top Lace Picks</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">Explore Shop <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4 featured-grid">
            <?php foreach ($featured as $i => $product): ?>
                <div class="col-md-3 animate-on-scroll stagger-<?php echo $i + 1; ?>">
                    <div class="card product-card">
                        <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($product['name']); ?>" loading="lazy">
                        <div class="card-body">
                            <span class="badge bg-gold text-dark mb-2"><?php echo esc($product['category_name']); ?></span>
                            <h5 class="card-title"><?php echo esc($product['name']); ?></h5>
                            <p class="text-muted mb-2"><?php echo format_currency($product['sale_price']); ?> <span class="text-decoration-line-through text-muted ms-2" style="font-size:0.85em;"><?php echo format_currency($product['price']); ?></span></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-gold"><i class="fa fa-star"></i> <?php echo number_format($product['rating'] ?? 4.8, 1); ?></div>
                                <a href="product.php?id=<?php echo esc($product['id']); ?>" class="btn btn-sm btn-outline-maroon">Quick View</a>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="cart.php?add=<?php echo esc($product['id']); ?>" class="btn btn-gold btn-sm ajax-add-cart"><i class="fa fa-shopping-bag me-1"></i>Add To Cart</a>
                                <a href="wishlist.php?add=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon btn-sm ajax-add-wishlist"><i class="fa fa-heart me-1"></i>Add To Wishlist</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section" style="background:var(--surface);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-title" style="padding-left:0;">Why Choose Us</span>
            <h2>Our Advantages</h2>
        </div>
        <div class="row gy-4">
            <?php
            $advantages = [
                ['icon' => 'fa-award', 'title' => '20+ Years Experience', 'desc' => 'Two decades of trusted textile expertise serving designers nationwide.'],
                ['icon' => 'fa-gem', 'title' => 'Premium Quality', 'desc' => 'Only the finest imported and local lace materials in our collections.'],
                ['icon' => 'fa-tags', 'title' => 'Affordable Prices', 'desc' => 'Competitive wholesale and retail pricing with no hidden fees.'],
                ['icon' => 'fa-th-large', 'title' => 'Huge Variety', 'desc' => 'Over 5,000 products across dozens of lace and trim categories.'],
                ['icon' => 'fa-shipping-fast', 'title' => 'Fast Delivery', 'desc' => 'Efficient dispatch across Pakistan with express shipping options.'],
                ['icon' => 'fa-headset', 'title' => 'Trusted Service', 'desc' => 'Dedicated customer support from order placement to delivery.'],
            ];
            foreach ($advantages as $i => $adv): ?>
                <div class="col-md-4 animate-on-scroll stagger-<?php echo $i + 1; ?>">
                    <div class="card p-4 border-0 text-center" style="background:var(--bg-warm);">
                        <div class="mb-3"><i class="fa <?php echo $adv['icon']; ?> fa-2x text-maroon"></i></div>
                        <h5 style="font-family:var(--font-heading);"><?php echo esc($adv['title']); ?></h5>
                        <p class="mb-0" style="font-size:0.9rem;"><?php echo esc($adv['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section" style="background:var(--bg);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-title">The Latest Reviews</span>
                <h2>Customer Love</h2>
            </div>
            <a href="account.php#orders" class="btn btn-outline-maroon">View Orders <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row gy-4">
            <?php $reviews = [
                ['name'=>'Ayesha Khan','text'=>'Beautiful lace quality and fast service. Perfect for my bridal collection. I will definitely order again.'],
                ['name'=>'Sara Ali','text'=>'Highly recommended for designers looking for premium trims and ribbons. Outstanding packaging and delivery.'],
                ['name'=>'Zain Ahmed','text'=>'The embroidery borders are elegant and the pricing is great. Muzammil Lace Center never disappoints.'],
            ]; ?>
            <?php foreach ($reviews as $i => $review): ?>
                <div class="col-md-4 animate-on-scroll stagger-<?php echo $i + 1; ?>">
                    <div class="review-card">
                        <p style="font-style:italic;position:relative;z-index:1;">"<?php echo esc($review['text']); ?>"</p>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:0.9rem;"><?php echo strtoupper(substr($review['name'], 0, 1)); ?></div>
                            <div>
                                <strong style="color:var(--text);"><?php echo esc($review['name']); ?></strong>
                                <div class="text-gold" style="font-size:0.85rem;"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section" style="background:var(--surface);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-title">Visual Gallery</span>
                <h2>Elegant Lace Collections</h2>
            </div>
            <a href="shop.php" class="btn btn-outline-maroon">Shop Now <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row gallery-grid">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="col-md-4 mb-4 animate-on-scroll stagger-<?php echo $i; ?>"><img src="assets/images/gallery-<?php echo $i; ?>.svg" alt="Gallery <?php echo $i; ?>" loading="lazy"></div>
            <?php endfor; ?>
        </div>
    </div>
</section>
<section class="section bg-maroon text-white">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <span class="section-title" style="color:var(--secondary-light);">Special Offers</span>
                <h2 style="color:#fff;">Celebrate with Exclusive Discounts</h2>
                <p style="color:rgba(255,255,255,0.8);">Grab bridal season offers, Eid collections, new arrivals, and bulk purchase discounts before the countdown ends.</p>
                <div class="countdown" id="countdown">
                    <div class="countdown-item"><span id="countdown-days">00</span>Days</div>
                    <div class="countdown-item"><span id="countdown-hours">00</span>Hours</div>
                    <div class="countdown-item"><span id="countdown-minutes">00</span>Minutes</div>
                    <div class="countdown-item"><span id="countdown-seconds">00</span>Seconds</div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="offer-card">
                    <h3 style="font-family:var(--font-heading);color:#fff;">Wedding Season Sale</h3>
                    <p>Up to 25% off premium bridal lace sets and accessories.</p>
                    <ul style="list-style:none;padding:0;">
                        <li class="mb-2"><i class="fa fa-check-circle me-2" style="color:var(--secondary-light);"></i>Bulk order savings</li>
                        <li class="mb-2"><i class="fa fa-check-circle me-2" style="color:var(--secondary-light);"></i>Free shipping on orders over Rs 10,000</li>
                        <li class="mb-2"><i class="fa fa-check-circle me-2" style="color:var(--secondary-light);"></i>Complimentary styling advice</li>
                    </ul>
                    <a href="shop.php" class="btn btn-gold btn-lg mt-3"><i class="fa fa-shopping-bag me-2"></i>Shop Offers</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
