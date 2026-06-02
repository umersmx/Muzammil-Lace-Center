<?php
require_once __DIR__ . '/includes/header.php';
if (!is_logged_in()) {
    redirect('login.php?redirect=wishlist.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_id'])) {
        $stmt = $pdo->prepare('DELETE FROM wishlist WHERE id = ? AND user_id = ?');
        $stmt->execute([(int)$_POST['remove_id'], $_SESSION['user_id']]);
        redirect('wishlist.php');
    }
}
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    $stmt = $pdo->prepare('INSERT IGNORE INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())');
    $stmt->execute([$_SESSION['user_id'], $productId]);
    redirect('wishlist.php');
}
$stmt = $pdo->prepare('SELECT w.id, p.id AS product_id, p.name, p.sale_price FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$wishlistItems = $stmt->fetchAll();
?>
<section class="section bg-light">
    <div class="container">
        <h2>My Wishlist</h2>
        <?php if (empty($wishlistItems)): ?>
            <div class="alert alert-info">Your wishlist is empty. Browse the shop to add favorites.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm rounded-4">
                            <img src="assets/images/product-<?php echo esc($item['product_id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($item['name']); ?>">
                            <div class="card-body">
                                <h5><?php echo esc($item['name']); ?></h5>
                                <p class="text-muted"><?php echo format_currency($item['sale_price']); ?></p>
                                <div class="d-grid gap-2">
                                    <a href="product.php?id=<?php echo esc($item['product_id']); ?>" class="btn btn-outline-maroon btn-sm">View Product</a>
                                    <form method="post" class="d-inline">
                                        <?php echo csrf_input_field(); ?>
                                        <input type="hidden" name="remove_id" value="<?php echo esc($item['id']); ?>">
                                        <button class="btn btn-gold btn-sm">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
