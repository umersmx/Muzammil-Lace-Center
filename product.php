<?php
require_once __DIR__ . '/includes/header.php';
global $pdo;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.status = 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    echo '<section class="section bg-light"><div class="container"><div class="card p-5 text-center border-0 shadow-sm"><i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i><h3 style="font-family:var(--font-heading);">Product not found</h3><p class="text-muted">The luxury item you are looking for does not exist or is no longer available.</p><a href="shop.php" class="btn btn-gold mt-3">Return to Shop</a></div></div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$images = get_product_images($id);
$relatedStmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 4');
$relatedStmt->execute([$product['category_id'], $id]);
$related = $relatedStmt->fetchAll();
?>
<section class="section bg-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="shop.php?category=<?php echo esc($product['category_id']); ?>"><?php echo esc($product['category_name']); ?></a></li>
            <li><?php echo esc($product['name']); ?></li>
        </ul>

        <div class="row gy-5">
            <div class="col-lg-6 animate-on-scroll">
                <div class="product-hero-image bg-white p-2 border">
                    <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" id="mainProductImage" alt="<?php echo esc($product['name']); ?>" class="w-100 rounded-4">
                </div>
                <?php if ($images): ?>
                <div class="product-thumbnails d-flex gap-3 mt-4 overflow-auto pb-2">
                    <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="active" style="width: 80px; height: 80px; object-fit: cover;" onclick="document.getElementById('mainProductImage').src=this.src; document.querySelectorAll('.product-thumbnails img').forEach(i=>i.classList.remove('active')); this.classList.add('active');">
                    <?php foreach ($images as $img): ?>
                        <img src="uploads/<?php echo esc($img['file_name']); ?>" style="width: 80px; height: 80px; object-fit: cover;" onclick="document.getElementById('mainProductImage').src=this.src; document.querySelectorAll('.product-thumbnails img').forEach(i=>i.classList.remove('active')); this.classList.add('active');">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 animate-on-scroll stagger-2">
                <div class="ps-lg-4">
                    <span class="badge bg-gold text-dark mb-3 px-3 py-2 text-uppercase" style="letter-spacing:0.05em;"><?php echo esc($product['category_name']); ?></span>
                    <h1 class="mb-2" style="font-size:clamp(2rem, 3.5vw, 2.8rem);"><?php echo esc($product['name']); ?></h1>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="text-gold"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i></div>
                        <span class="text-muted" style="font-size:0.9rem;"><?php echo esc($product['rating']); ?> / 5 (<?php echo esc($product['reviews_count'] ?? '24'); ?> Reviews)</span>
                        <span class="text-muted" style="font-size:0.9rem;">| SKU: PLC-<?php echo esc($product['id']); ?></span>
                    </div>

                    <div class="product-price mb-4">
                        <?php echo format_currency($product['sale_price']); ?> 
                        <?php if($product['sale_price'] < $product['price']): ?>
                            <span class="original-price"><?php echo format_currency($product['price']); ?></span>
                            <span class="discount-badge">Save <?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%</span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-muted fs-6 mb-4" style="line-height:1.8;"><?php echo esc($product['short_description']); ?></p>
                    
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="d-inline-block rounded-circle <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>" style="width:8px;height:8px;"></span>
                        <span class="fw-medium <?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?>"><?php echo $product['stock'] > 0 ? 'In Stock & Ready to Ship' : 'Currently Out of Stock'; ?></span>
                    </div>

                    <div class="card p-4 border-0 shadow-sm bg-white mb-4">
                        <form method="get" action="cart.php" class="row g-3 align-items-end">
                            <div class="col-sm-4">
                                <label class="form-label text-uppercase text-muted" style="font-size:0.75rem;letter-spacing:0.05em;">Quantity</label>
                                <div class="qty-selector w-100 justify-content-between">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo esc($product['stock']); ?>">
                                    <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="add" value="<?php echo esc($product['id']); ?>">
                                <button class="btn btn-gold btn-lg w-100 shadow-gold" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>><i class="fa fa-shopping-bag me-2"></i>Add To Cart</button>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="https://wa.me/923001234567?text=I%20want%20to%20order%20<?php echo urlencode($product['name']); ?>%20(SKU:%20PLC-<?php echo esc($product['id']); ?>)" target="_blank" class="btn btn-outline-success px-4" style="border-radius:var(--radius-pill);"><i class="fab fa-whatsapp me-2"></i> WhatsApp Order</a>
                        <a href="wishlist.php?add=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon px-4 ajax-add-wishlist"><i class="fa fa-heart me-2"></i>Add To Wishlist</a>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex gap-4 text-muted" style="font-size:0.85rem;">
                            <div><i class="fa fa-shield-alt text-gold me-1"></i> Quality Guaranteed</div>
                            <div><i class="fa fa-undo text-gold me-1"></i> 7-Day Returns</div>
                            <div><i class="fa fa-shipping-fast text-gold me-1"></i> Fast Dispatch</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-5 mt-5 animate-on-scroll">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm bg-white overflow-hidden">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h3 class="mb-0" style="font-family:var(--font-heading);">Product Details</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <p style="line-height:1.9;color:var(--text-secondary);"><?php echo nl2br(esc($product['description'])); ?></p>
                        
                        <h4 class="mt-5 mb-4" style="font-family:var(--font-heading);">Specifications</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered spec-table mb-0">
                                <tbody>
                                    <tr><th>Material</th><td><?php echo esc($product['material']); ?></td></tr>
                                    <tr><th>Color</th><td><?php echo esc($product['color']); ?></td></tr>
                                    <tr><th>Width/Size</th><td><?php echo esc($product['width']); ?></td></tr>
                                    <tr><th>Usage</th><td>Bridal couture, evening gowns, decorative borders, elegant embellishments</td></tr>
                                    <tr><th>Care Instructions</th><td>Dry clean recommended for intricate beadwork and luxury fabrics</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 stagger-2">
                <div class="card border-0 shadow-sm bg-white p-4">
                    <h4 style="font-family:var(--font-heading);"><i class="fa fa-star text-gold me-2"></i>Customer Reviews</h4>
                    <div class="d-flex align-items-center gap-3 my-4">
                        <h1 class="mb-0 text-maroon" style="font-family:var(--font-heading);"><?php echo number_format($product['rating'] ?? 4.8, 1); ?></h1>
                        <div>
                            <div class="text-gold"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i></div>
                            <span class="text-muted small">Based on <?php echo esc($product['reviews_count'] ?? '24'); ?> reviews</span>
                        </div>
                    </div>
                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong class="text-dark">Sana F.</strong>
                            <div class="text-gold small"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        </div>
                        <p class="text-muted small mb-0">"The quality of this lace is absolutely stunning. It elevated my bridal design perfectly. Fast delivery too!"</p>
                    </div>
                    <div class="review-item">
                        <div class="d-flex justify-content-between mb-1">
                            <strong class="text-dark">Zara Boutique</strong>
                            <div class="text-gold small"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i></div>
                        </div>
                        <p class="text-muted small mb-0">"Very elegant pattern and sturdy material. Will be ordering more for my upcoming collection."</p>
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-outline-dark" style="border-radius:var(--radius-pill);">Write a Review</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($related): ?>
        <div class="row mt-5 pt-5 border-top animate-on-scroll">
            <div class="col-12 mb-4 d-flex justify-content-between align-items-end">
                <h3 style="font-family:var(--font-heading);">You May Also Like</h3>
                <a href="shop.php?category=<?php echo esc($product['category_id']); ?>" class="btn btn-link text-maroon p-0">View All</a>
            </div>
            <?php foreach ($related as $i => $item): ?>
                <div class="col-md-3 stagger-<?php echo $i + 1; ?>">
                    <div class="card product-card h-100">
                        <img src="assets/images/product-<?php echo esc($item['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($item['name']); ?>" loading="lazy">
                        <div class="card-body">
                            <h6 class="card-title text-truncate"><?php echo esc($item['name']); ?></h6>
                            <p class="text-muted mb-3"><?php echo format_currency($item['sale_price']); ?></p>
                            <a href="product.php?id=<?php echo esc($item['id']); ?>" class="btn btn-outline-maroon btn-sm w-100 stretched-link">Quick View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
