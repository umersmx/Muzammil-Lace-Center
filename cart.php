<?php
require_once __DIR__ . '/includes/functions.php';
$products = [];
$subtotal = 0;
$shipping = 200;
$taxRate = 0.10;
$couponDiscount = 0;
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_GET['ajax']) && isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    $quantity = max(1, (int)($_GET['quantity'] ?? 1));
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'count' => cart_count(), 'message' => 'Added to cart']);
    exit;
}
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    $quantity = max(1, (int)($_GET['quantity'] ?? 1));
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
    redirect('cart.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $productId => $qty) {
            $productId = (int)$productId;
            $qty = max(1, (int)$qty);
            if ($qty < 1) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = $qty;
            }
        }
    }
    if (isset($_POST['remove'])) {
        $removeId = (int)$_POST['remove'];
        unset($_SESSION['cart'][$removeId]);
    }
    if (isset($_POST['apply_coupon'])) {
        $code = trim($_POST['coupon_code']);
        if ($code !== '') {
            $stmt = $pdo->prepare('SELECT * FROM coupons WHERE code = ? AND expiry_date >= CURRENT_DATE() AND status = 1');
            $stmt->execute([$code]);
            $coupon = $stmt->fetch();
            if ($coupon) {
                $_SESSION['coupon'] = $coupon;
            } else {
                $couponError = 'Invalid or expired coupon code.';
            }
        }
    }
}
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']]['quantity'];
        $line = $product['sale_price'] * $qty;
        $subtotal += $line;
    }
}
if (!empty($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];
    $couponDiscount = min($subtotal, ($coupon['discount_type'] === 'fixed' ? $coupon['discount_amount'] : $subtotal * ($coupon['discount_amount'] / 100)));
}
$tax = ($subtotal - $couponDiscount + $shipping) * $taxRate;
$total = $subtotal - $couponDiscount + $shipping + $tax;
require_once __DIR__ . '/includes/header.php';
?>
<section class="section bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li>Shopping Cart</li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-4 animate-on-scroll">
            <h2 style="font-family:var(--font-heading);">Shopping Cart</h2>
            <?php if (!empty($products)): ?>
                <span class="text-muted"><?php echo count($products); ?> items</span>
            <?php endif; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="card p-5 text-center animate-on-scroll border-0 shadow-sm">
                <i class="fa fa-shopping-bag fa-4x text-gold mb-4 opacity-50"></i>
                <h4 style="font-family:var(--font-heading);">Your cart is empty</h4>
                <p class="text-muted mb-4">Looks like you haven't added any luxury items to your cart yet.</p>
                <div>
                    <a href="shop.php" class="btn btn-gold btn-lg"><i class="fa fa-arrow-left me-2"></i>Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <form method="post">
                <div class="row gy-4">
                    <div class="col-lg-8 animate-on-scroll">
                        <div class="table-responsive">
                            <table class="table align-middle cart-table bg-white">
                                <thead>
                                    <tr>
                                        <th>Product Details</th>
                                        <th>Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <a href="product.php?id=<?php echo esc($product['id']); ?>">
                                                        <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" alt="<?php echo esc($product['name']); ?>" width="90" class="img-thumbnail" loading="lazy">
                                                    </a>
                                                    <div>
                                                        <a href="product.php?id=<?php echo esc($product['id']); ?>"><strong style="color:var(--text);font-family:var(--font-heading);font-size:1.05rem;"><?php echo esc($product['name']); ?></strong></a><br>
                                                        <small class="text-muted text-uppercase" style="letter-spacing:0.05em;font-size:0.75rem;"><?php echo esc($product['material']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo format_currency($product['sale_price']); ?></td>
                                            <td class="text-center">
                                                <div class="qty-selector mx-auto">
                                                    <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                                    <input type="number" name="quantity[<?php echo esc($product['id']); ?>]" value="<?php echo esc($_SESSION['cart'][$product['id']]['quantity']); ?>" min="1">
                                                    <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold" style="color:var(--primary);"><?php echo format_currency($product['sale_price'] * $_SESSION['cart'][$product['id']]['quantity']); ?></td>
                                            <td class="text-end">
                                                <button type="submit" name="remove" value="<?php echo esc($product['id']); ?>" class="btn btn-sm btn-link text-danger" title="Remove Item"><i class="fa fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="shop.php" class="btn btn-outline-maroon"><i class="fa fa-arrow-left me-2"></i>Continue Shopping</a>
                            <button type="submit" name="update_cart" class="btn btn-light shadow-sm">Update Cart</button>
                        </div>
                    </div>
                    <div class="col-lg-4 animate-on-scroll stagger-2">
                        <div class="p-4 order-summary-card">
                            <h5 style="font-family:var(--font-heading);" class="mb-4">Order Summary</h5>
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between text-muted"><span>Subtotal</span><strong class="text-dark"><?php echo format_currency($subtotal); ?></strong></li>
                                <?php if ($couponDiscount > 0): ?>
                                    <li class="d-flex justify-content-between text-success"><span>Coupon Discount</span><strong>-<?php echo format_currency($couponDiscount); ?></strong></li>
                                <?php endif; ?>
                                <li class="d-flex justify-content-between text-muted"><span>Shipping</span><strong class="text-dark"><?php echo format_currency($shipping); ?></strong></li>
                                <li class="d-flex justify-content-between text-muted"><span>Estimated Tax</span><strong class="text-dark"><?php echo format_currency($tax); ?></strong></li>
                                <li class="d-flex justify-content-between border-top pt-3 mt-3 border-dark border-opacity-10">
                                    <span class="fw-bold">Total Amount</span>
                                    <strong class="fs-4 text-primary" style="color:var(--primary)!important;"><?php echo format_currency($total); ?></strong>
                                </li>
                            </ul>
                            
                            <hr class="my-4 border-dark border-opacity-10">
                            
                            <h6 class="mb-3">Gift Card or Discount Code</h6>
                            <div class="input-group mb-2 shadow-sm rounded-pill overflow-hidden">
                                <input type="text" class="form-control border-0 px-4" name="coupon_code" placeholder="Enter code" value="<?php echo esc($_POST['coupon_code'] ?? ''); ?>">
                                <button class="btn btn-dark px-4" name="apply_coupon" type="submit">Apply</button>
                            </div>
                            <?php if (!empty($couponError)): ?><p class="text-danger small mt-2 ms-2"><i class="fa fa-exclamation-circle me-1"></i><?php echo esc($couponError); ?></p><?php endif; ?>
                            
                            <div class="d-grid gap-2 mt-4 pt-2">
                                <a href="checkout.php" class="btn btn-gold btn-lg shadow-gold">Proceed to Checkout <i class="fa fa-arrow-right ms-2"></i></a>
                            </div>
                            <div class="text-center mt-3">
                                <img src="assets/images/secure-checkout.png" alt="Secure Checkout" style="height:24px;opacity:0.6;filter:grayscale(1);" onerror="this.style.display='none'">
                                <p class="small text-muted mt-2 mb-0"><i class="fa fa-lock me-1"></i> Secure encrypted checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
