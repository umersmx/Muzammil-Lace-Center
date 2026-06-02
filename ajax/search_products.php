<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
$q = trim($_GET['q'] ?? '');
$params = [];
$sql = 'SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 1';
if ($q !== '') {
    $sql .= ' AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
    $term = "%$q%";
    $params = [$term, $term, $term];
}
$sql .= ' ORDER BY p.created_at DESC LIMIT 24';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
if (!$products) {
    echo '<div class="col-12"><div class="alert alert-warning">No products match your search.</div></div>';
    exit;
}
foreach ($products as $product): ?>
    <div class="col-md-4">
        <div class="card product-card">
            <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($product['name']); ?>">
            <div class="card-body">
                <span class="badge bg-gold text-dark mb-2"><?php echo esc($product['category_name']); ?></span>
                <h5 class="card-title"><?php echo esc($product['name']); ?></h5>
                <p class="text-muted mb-2"><?php echo format_currency($product['sale_price']); ?> <span class="text-decoration-line-through text-muted ms-2"><?php echo format_currency($product['price']); ?></span></p>
                <a href="product.php?id=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon btn-sm">View</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
