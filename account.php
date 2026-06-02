<?php
require_once __DIR__ . '/includes/header.php';
if (!is_logged_in()) {
    redirect('login.php?redirect=account.php');
}
$user = current_user();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
$stmt = $pdo->prepare('SELECT w.id, p.name, p.sale_price FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$wishlistItems = $stmt->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $name = safe_input($_POST['name']);
        $phone = safe_input($_POST['phone']);
        $address = safe_input($_POST['address']);
        $city = safe_input($_POST['city']);
        $postal_code = safe_input($_POST['postal_code']);
        $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, address = ?, city = ?, postal_code = ? WHERE id = ?');
        $stmt->execute([$name, $phone, $address, $city, $postal_code, $_SESSION['user_id']]);
        $success = 'Profile updated successfully.';
        $user = current_user();
    }
}
?>
<section class="section bg-light">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 p-4">
                    <h4>My Profile</h4>
                    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="<?php echo esc($user['name']); ?>" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="<?php echo esc($user['email']); ?>" disabled></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="tel" name="phone" class="form-control" value="<?php echo esc($user['phone']); ?>" required></div>
                        <div class="mb-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="<?php echo esc($user['city'] ?? ''); ?>"></div>
                        <div class="mb-3"><label class="form-label">Address</label><textarea name="address" class="form-control"><?php echo esc($user['address'] ?? ''); ?></textarea></div>
                        <div class="mb-3"><label class="form-label">Postal Code</label><input type="text" name="postal_code" class="form-control" value="<?php echo esc($user['postal_code'] ?? ''); ?>"></div>
                        <button class="btn btn-gold w-100" name="update_profile">Save Profile</button>
                    </form>
                </div>
                <div class="card shadow-sm rounded-4 p-4 mt-4">
                    <h5>Quick Actions</h5>
                    <a href="cart.php" class="btn btn-outline-maroon w-100 mb-2">View Cart</a>
                    <a href="wishlist.php" class="btn btn-outline-maroon w-100 mb-2">My Wishlist</a>
                    <a href="logout.php" class="btn btn-gold w-100">Sign Out</a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm rounded-4 p-4 mb-4">
                    <h4>Order History</h4>
                    <?php if (empty($orders)): ?><p class="text-muted">No orders placed yet.</p><?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead><tr><th>Order #</th><th>Status</th><th>Date</th><th>Total</th></tr></thead>
                                <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo esc($order['id']); ?></td>
                                        <td><?php echo esc($order['status']); ?></td>
                                        <td><?php echo esc($order['created_at']); ?></td>
                                        <td><?php echo format_currency($order['total_amount']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card shadow-sm rounded-4 p-4">
                    <h4>Wishlist</h4>
                    <?php if (empty($wishlistItems)): ?><p class="text-muted">Your wishlist is empty.</p><?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($wishlistItems as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo esc($item['name']); ?>
                                    <span><?php echo format_currency($item['sale_price']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
