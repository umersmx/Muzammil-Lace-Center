<?php
require_once __DIR__ . '/includes/header.php';
global $pdo;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.status = 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    echo '<section class="section"><div class="container"><div class="alert alert-danger">Product not found.</div></div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$images = get_product_images($id);
$relatedStmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 4');
$relatedStmt->execute([$product['category_id'], $id]);
$related = $relatedStmt->fetchAll();
?>
<section class="section bg-light">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6">
                <div class="shadow-sm rounded-4 overflow-hidden bg-white">
                    <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" alt="<?php echo esc($product['name']); ?>" class="w-100">
                </div>
                <div class="d-flex gap-3 mt-3">
                    <?php foreach ($images as $img): ?>
                        <img src="uploads/<?php echo esc($img['file_name']); ?>" alt="<?php echo esc($product['name']); ?>" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge bg-gold text-dark mb-2"><?php echo esc($product['category_name']); ?></span>
                <h1><?php echo esc($product['name']); ?></h1>
                <p class="text-muted">SKU: PLC-<?php echo esc($product['id']); ?> | Availability: <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
                <div class="mb-3"><h3 class="text-maroon"><?php echo format_currency($product['sale_price']); ?></h3> <span class="text-decoration-line-through text-muted"><?php echo format_currency($product['price']); ?></span></div>
                <div class="mb-4"><strong>Rating:</strong> <?php echo esc($product['rating']); ?> / 5</div>
                <p><?php echo esc($product['short_description']); ?></p>
                <form method="get" action="cart.php" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Qty</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" name="add" value="<?php echo esc($product['id']); ?>">
                        <button class="btn btn-gold w-100">Add To Cart</button>
                    </div>
                </form>
                <div class="mt-4">
                    <a href="https://wa.me/923001234567?text=I%20want%20to%20order%20<?php echo urlencode($product['name']); ?>" class="btn btn-outline-maroon me-2"><i class="fa fa-whatsapp"></i> WhatsApp Order</a>
                    <a href="wishlist.php?add=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon">Add To Wishlist</a>
                </div>
            </div>
        </div>
        <div class="row gy-4 mt-5">
            <div class="col-lg-8">
                <h3>Description</h3>
                <p><?php echo nl2br(esc($product['description'])); ?></p>
                <h4>Specifications</h4>
                <ul>
                    <li>Material: <?php echo esc($product['material']); ?></li>
                    <li>Color: <?php echo esc($product['color']); ?></li>
                    <li>Width: <?php echo esc($product['width']); ?></li>
                    <li>Suitable for bridal and couture embellishments</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 shadow-sm">
                    <h5>Customer Reviews</h5>
                    <p class="text-muted">Based on <?php echo esc($product['reviews_count']); ?> reviews</p>
                    <div><i class="fa fa-star text-gold"></i> 4.8 average rating</div>
                </div>
            </div>
        </div>
        <?php if ($related): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3>Related Products</h3>
            </div>
            <?php foreach ($related as $item): ?>
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="assets/images/product-<?php echo esc($item['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($item['name']); ?>">
                        <div class="card-body">
                            <h6><?php echo esc($item['name']); ?></h6>
                            <p class="text-muted"><?php echo format_currency($item['sale_price']); ?></p>
                            <a href="product.php?id=<?php echo esc($item['id']); ?>" class="btn btn-outline-maroon btn-sm">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
