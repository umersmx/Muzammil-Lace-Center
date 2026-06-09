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
<section class="section bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li><a href="cart.php">Shopping Cart</a></li>
            <li>Checkout</li>
        </ul>

        <div class="row gy-4">
            <div class="col-lg-7 animate-on-scroll">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">
                    <h2 class="mb-4" style="font-family:var(--font-heading);">Shipping & Billing</h2>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center"><i class="fa fa-exclamation-circle me-2"></i> <?php echo esc($error); ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        
                        <h5 class="mb-3 text-maroon" style="font-family:var(--font-heading);font-size:1.1rem;">1. Contact Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Full Name</label>
                                <input type="text" name="full_name" class="form-control" required value="<?php echo esc($user['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Email Address</label>
                                <input type="email" name="email" class="form-control" required value="<?php echo esc($user['email']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" required value="<?php echo esc($user['phone']); ?>">
                            </div>
                        </div>

                        <h5 class="mb-3 text-maroon" style="font-family:var(--font-heading);font-size:1.1rem;">2. Delivery Address</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Street Address</label>
                                <textarea name="address" class="form-control" rows="2" required placeholder="House number, street name, apartment, suite, etc."><?php echo esc($user['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">City</label>
                                <input type="text" name="city" class="form-control" required value="<?php echo esc($user['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Postal/Zip Code</label>
                                <input type="text" name="postal_code" class="form-control" required value="<?php echo esc($user['postal_code'] ?? ''); ?>">
                            </div>
                        </div>

                        <h5 class="mb-3 text-maroon" style="font-family:var(--font-heading);font-size:1.1rem;">3. Payment Method</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-12">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="Cash on Delivery" id="cod" checked>
                                    <label class="form-check-label mb-0 fw-medium" for="cod">Cash on Delivery (COD)</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="Bank Transfer" id="bank">
                                    <label class="form-check-label mb-0 fw-medium" for="bank">Direct Bank Transfer</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="Easypaisa" id="easypaisa">
                                    <label class="form-check-label mb-0 fw-medium" for="easypaisa">Easypaisa</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="JazzCash" id="jazzcash">
                                    <label class="form-check-label mb-0 fw-medium" for="jazzcash">JazzCash</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 border-top pt-4">
                            <button class="btn btn-gold btn-lg shadow-gold flex-grow-1"><i class="fa fa-lock me-2"></i>Place Order Securely</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5 animate-on-scroll stagger-2">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 position-sticky" style="top:100px;">
                    <h4 style="font-family:var(--font-heading);" class="mb-4">Order Summary</h4>
                    
                    <div class="cart-items-preview mb-4 overflow-auto" style="max-height: 250px; padding-right:10px;">
                        <?php foreach ($products as $product): ?>
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-light">
                                <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="rounded-2" width="60" alt="<?php echo esc($product['name']); ?>">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate" style="font-size:0.9rem;max-width:180px;"><?php echo esc($product['name']); ?></h6>
                                    <small class="text-muted">Qty: <?php echo esc($cartItems[$product['id']]['quantity']); ?></small>
                                </div>
                                <div class="fw-medium text-end" style="font-size:0.95rem;">
                                    <?php echo format_currency($product['sale_price'] * $cartItems[$product['id']]['quantity']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <ul class="list-unstyled mt-3 mb-0">
                        <li class="d-flex justify-content-between mb-2 text-muted"><span>Subtotal</span><strong class="text-dark"><?php echo format_currency($subtotal); ?></strong></li>
                        <?php if ($couponDiscount > 0): ?>
                            <li class="d-flex justify-content-between mb-2 text-success"><span>Coupon Discount</span><strong>-<?php echo format_currency($couponDiscount); ?></strong></li>
                        <?php endif; ?>
                        <li class="d-flex justify-content-between mb-2 text-muted"><span>Shipping</span><strong class="text-dark"><?php echo format_currency($shipping); ?></strong></li>
                        <li class="d-flex justify-content-between mb-2 text-muted"><span>Estimated Tax</span><strong class="text-dark"><?php echo format_currency($tax); ?></strong></li>
                        <li class="d-flex justify-content-between border-top pt-3 mt-3">
                            <span class="fs-5 fw-bold text-dark">Total</span>
                            <strong class="fs-4 text-primary" style="color:var(--primary)!important;"><?php echo format_currency($total); ?></strong>
                        </li>
                    </ul>

                    <div class="alert alert-info mt-4 mb-0 border-0 d-flex align-items-start gap-3">
                        <i class="fa fa-info-circle mt-1 text-gold"></i>
                        <p class="small mb-0 text-muted">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
