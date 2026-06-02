<?php
require_once __DIR__ . '/includes/header.php';
if (!is_logged_in()) {
    redirect('login.php?redirect=checkout.php');
}
$user = current_user();
$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    redirect('cart.php');
}
$ids = array_keys($cartItems);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();
$subtotal = 0;
foreach ($products as $product) {
    $qty = $cartItems[$product['id']]['quantity'];
    $subtotal += $product['sale_price'] * $qty;
}
$shipping = 200;
$taxRate = 0.10;
$couponDiscount = 0;
if (!empty($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];
    $couponDiscount = min($subtotal, $coupon['discount_type'] === 'fixed' ? $coupon['discount_amount'] : $subtotal * ($coupon['discount_amount'] / 100));
}
$tax = round(($subtotal - $couponDiscount + $shipping) * $taxRate);
$total = round($subtotal - $couponDiscount + $shipping + $tax);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission.';
    } else {
        $fullName = safe_input($_POST['full_name']);
        $phone = safe_input($_POST['phone']);
        $email = safe_input($_POST['email']);
        $address = safe_input($_POST['address']);
        $city = safe_input($_POST['city']);
        $postalCode = safe_input($_POST['postal_code']);
        $paymentMethod = safe_input($_POST['payment_method']);
        if ($fullName && $phone && $address && $city && $postalCode && $paymentMethod) {
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, fullname, phone, email, address, city, postal_code, payment_method, coupon_code, discount_amount, shipping_charge, tax_amount, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([
                $_SESSION['user_id'], $fullName, $phone, $email, $address, $city, $postalCode, $paymentMethod,
                $_SESSION['coupon']['code'] ?? null,
                $couponDiscount,
                $shipping,
                $tax,
                $total,
                'Processing'
            ]);
            $orderId = $pdo->lastInsertId();
            foreach ($products as $product) {
                $qty = $cartItems[$product['id']]['quantity'];
                $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$orderId, $product['id'], $qty, $product['sale_price'], $product['sale_price'] * $qty]);
            }
            unset($_SESSION['cart'], $_SESSION['coupon']);
            redirect('account.php?order_success=' . $orderId);
        } else {
            $error = 'Please complete all required fields.';
        }
    }
}
?>
<section class="section">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h2>Checkout</h2>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="full_name" class="form-control" required value="<?php echo esc($user['name']); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Phone Number</label><input type="tel" name="phone" class="form-control" required value="<?php echo esc($user['phone']); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?php echo esc($user['email']); ?>"></div>
                            <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" required value="<?php echo esc($user['city'] ?? ''); ?>"></div>
                            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" required><?php echo esc($user['address'] ?? ''); ?></textarea></div>
                            <div class="col-md-6"><label class="form-label">Postal Code</label><input type="text" name="postal_code" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Easypaisa">Easypaisa</option>
                                    <option value="JazzCash">JazzCash</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-gold btn-lg mt-4">Place Order</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h4>Order Summary</h4>
                    <ul class="list-unstyled mt-3">
                        <li class="d-flex justify-content-between"><span>Subtotal</span><strong><?php echo format_currency($subtotal); ?></strong></li>
                        <li class="d-flex justify-content-between"><span>Discount</span><strong>-<?php echo format_currency($couponDiscount); ?></strong></li>
                        <li class="d-flex justify-content-between"><span>Shipping</span><strong><?php echo format_currency($shipping); ?></strong></li>
                        <li class="d-flex justify-content-between"><span>Tax</span><strong><?php echo format_currency($tax); ?></strong></li>
                        <li class="d-flex justify-content-between border-top pt-3"><span>Total</span><strong><?php echo format_currency($total); ?></strong></li>
                    </ul>
                    <p class="text-muted mt-3">Your order will be processed after confirmation. You will receive a tracking number once shipped.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
