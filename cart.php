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
<section class="section">
    <div class="container">
        <h2>Shopping Cart</h2>
        <?php if (empty($products)): ?>
            <div class="alert alert-info">Your cart is empty. <a href="shop.php">Start shopping now.</a></div>
        <?php else: ?>
            <form method="post">
                <div class="table-responsive">
                    <table class="table align-middle bg-white rounded-4 shadow-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" alt="<?php echo esc($product['name']); ?>" width="80" class="rounded-2">
                                            <div>
                                                <strong><?php echo esc($product['name']); ?></strong><br>
                                                <small class="text-muted"><?php echo esc($product['material']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo format_currency($product['sale_price']); ?></td>
                                    <td><input type="number" name="quantity[<?php echo esc($product['id']); ?>]" value="<?php echo esc($_SESSION['cart'][$product['id']]['quantity']); ?>" min="1" class="form-control w-25"></td>
                                    <td><?php echo format_currency($product['sale_price'] * $_SESSION['cart'][$product['id']]['quantity']); ?></td>
                                    <td>
                                        <button type="submit" name="remove" value="<?php echo esc($product['id']); ?>" class="btn btn-sm btn-outline-maroon">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between flex-column flex-md-row gap-3 mt-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm flex-grow-1">
                        <h5>Apply Coupon</h5>
                        <div class="input-group mt-3">
                            <input type="text" class="form-control" name="coupon_code" placeholder="Enter coupon code" value="<?php echo esc($_POST['coupon_code'] ?? ''); ?>">
                            <button class="btn btn-gold" name="apply_coupon" type="submit">Apply</button>
                        </div>
                        <?php if (!empty($couponError)): ?><p class="text-danger mt-2"><?php echo esc($couponError); ?></p><?php endif; ?>
                    </div>
                    <div class="p-4 bg-white rounded-4 shadow-sm" style="min-width: 320px;">
                        <h5>Order Summary</h5>
                        <ul class="list-unstyled mt-3">
                            <li class="d-flex justify-content-between"><span>Subtotal</span><strong><?php echo format_currency($subtotal); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Coupon</span><strong>-<?php echo format_currency($couponDiscount); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Shipping</span><strong><?php echo format_currency($shipping); ?></strong></li>
                            <li class="d-flex justify-content-between"><span>Tax (10%)</span><strong><?php echo format_currency($tax); ?></strong></li>
                            <li class="d-flex justify-content-between border-top pt-3"><span>Total</span><strong><?php echo format_currency($total); ?></strong></li>
                        </ul>
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" name="update_cart" class="btn btn-outline-maroon">Update Cart</button>
                            <a href="checkout.php" class="btn btn-gold">Proceed To Checkout</a>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
