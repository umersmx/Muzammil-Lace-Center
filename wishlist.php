<?php
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['ajax'])) {
    if (!is_logged_in()) {
        header('Content-Type: application/json');
        echo json_encode(['redirect' => 'login.php?redirect=wishlist.php']);
        exit;
    }
    if (isset($_GET['add'])) {
        $productId = (int)$_GET['add'];
        $stmt = $pdo->prepare('INSERT IGNORE INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$_SESSION['user_id'], $productId]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'count' => wishlist_count(), 'message' => 'Added to wishlist']);
        exit;
    }
}

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
$items = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="section bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li><a href="account.php">Account</a></li>
            <li>Wishlist</li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-4 animate-on-scroll">
            <h2 style="font-family:var(--font-heading);">My Wishlist</h2>
            <span class="text-muted"><?php echo count($items); ?> items saved</span>
        </div>

        <?php if (empty($items)): ?>
            <div class="card p-5 text-center animate-on-scroll border-0 shadow-sm bg-white">
                <i class="fa fa-heart fa-4x text-gold mb-4 opacity-50"></i>
                <h4 style="font-family:var(--font-heading);">Your wishlist is empty</h4>
                <p class="text-muted mb-4">You haven't saved any luxury lace items yet. Explore our collections and save your favorites.</p>
                <div>
                    <a href="shop.php" class="btn btn-gold btn-lg"><i class="fa fa-store me-2"></i>Discover Collections</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($items as $i => $item): ?>
                    <div class="col-md-4 col-lg-3 animate-on-scroll stagger-<?php echo ($i % 4) + 1; ?>">
                        <div class="card product-card h-100 position-relative">
                            <!-- Remove Button -->
                            <form method="post" class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                <input type="hidden" name="remove_id" value="<?php echo esc($item['id']); ?>">
                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" style="width:32px;height:32px;padding:0;" title="Remove from wishlist">
                                    <i class="fa fa-times"></i>
                                </button>
                            </form>
                            
                            <img src="assets/images/product-<?php echo esc($item['product_id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($item['name']); ?>" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate mb-1" style="font-family:var(--font-heading);"><?php echo esc($item['name']); ?></h6>
                                <p class="text-maroon fw-bold mb-3"><?php echo format_currency($item['sale_price']); ?></p>
                                
                                <div class="mt-auto d-grid gap-2">
                                    <a href="cart.php?add=<?php echo esc($item['product_id']); ?>" class="btn btn-gold btn-sm ajax-add-cart"><i class="fa fa-shopping-bag me-1"></i>Add To Cart</a>
                                    <a href="product.php?id=<?php echo esc($item['product_id']); ?>" class="btn btn-outline-maroon btn-sm">View Details</a>
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
