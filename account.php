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
<section class="section bg-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li>My Account</li>
        </ul>

        <?php if (isset($_GET['order_success'])): ?>
            <div class="alert alert-success animate-on-scroll border-0 shadow-sm p-4 d-flex align-items-center mb-4">
                <i class="fa fa-check-circle fa-2x text-success me-3"></i>
                <div>
                    <h5 class="mb-1 text-dark" style="font-family:var(--font-heading);">Order placed successfully!</h5>
                    <p class="mb-0 text-muted">Your order #<?php echo esc($_GET['order_success']); ?> has been received and is currently being processed. Thank you for shopping with Muzammil Lace Center.</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="row gy-4">
            <div class="col-lg-4 account-sidebar animate-on-scroll">
                <div class="card p-4 p-md-5 bg-white text-center mb-4">
                    <div class="mx-auto mb-3" style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-family:var(--font-heading);">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                    <h4 style="font-family:var(--font-heading);"><?php echo esc($user['name']); ?></h4>
                    <p class="text-muted small mb-0"><?php echo esc($user['email']); ?></p>
                </div>

                <div class="card p-4 bg-white">
                    <h5 class="mb-4" style="font-family:var(--font-heading);">Update Profile</h5>
                    <?php if (!empty($success)): ?><div class="alert alert-success"><i class="fa fa-check me-2"></i><?php echo esc($success); ?></div><?php endif; ?>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle me-2"></i><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo esc($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo esc($user['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">City</label>
                            <input type="text" name="city" class="form-control" value="<?php echo esc($user['city'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Street Address</label>
                            <textarea name="address" class="form-control" rows="2"><?php echo esc($user['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control" value="<?php echo esc($user['postal_code'] ?? ''); ?>">
                        </div>
                        <button class="btn btn-gold w-100 shadow-gold" name="update_profile">Save Changes</button>
                    </form>
                </div>
                
                <div class="d-grid mt-4">
                    <a href="logout.php" class="btn btn-outline-danger" style="border-radius:var(--radius-pill);"><i class="fa fa-sign-out-alt me-2"></i>Sign Out</a>
                </div>
            </div>

            <div class="col-lg-8 animate-on-scroll stagger-2">
                <div class="card bg-white p-4 p-md-5 mb-4 border-0 shadow-sm rounded-4" id="orders">
                    <h4 class="mb-4" style="font-family:var(--font-heading);">Order History</h4>
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fa fa-box-open fa-3x mb-3 opacity-50 text-gold"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="shop.php" class="btn btn-outline-maroon mt-2">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong>#<?php echo esc($order['id']); ?></strong></td>
                                        <td class="text-muted small"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <?php 
                                            $statusClass = 'bg-secondary';
                                            if ($order['status'] === 'Processing') $statusClass = 'bg-primary';
                                            if ($order['status'] === 'Shipped') $statusClass = 'bg-info text-dark';
                                            if ($order['status'] === 'Delivered') $statusClass = 'bg-success';
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?> rounded-pill fw-normal px-3"><?php echo esc($order['status']); ?></span>
                                        </td>
                                        <td class="text-end fw-medium text-maroon"><?php echo format_currency($order['total_amount']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card bg-white p-4 p-md-5 border-0 shadow-sm rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0" style="font-family:var(--font-heading);">My Wishlist</h4>
                        <a href="wishlist.php" class="btn btn-sm btn-link text-maroon">Manage <i class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                    
                    <?php if (empty($wishlistItems)): ?>
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">Your wishlist is currently empty.</p>
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach (array_slice($wishlistItems, 0, 5) as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:40px;height:40px;background:var(--bg-warm);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;">
                                            <i class="fa fa-heart text-gold opacity-50"></i>
                                        </div>
                                        <a href="product.php?id=<?php echo esc($item['id']); ?>" class="text-dark fw-medium text-decoration-none hover-primary"><?php echo esc($item['name']); ?></a>
                                    </div>
                                    <span class="fw-bold text-maroon"><?php echo format_currency($item['sale_price']); ?></span>
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
