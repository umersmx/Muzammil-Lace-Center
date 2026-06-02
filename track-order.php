<?php
require_once __DIR__ . '/includes/header.php';
$status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int)($_POST['order_id'] ?? 0);
    if ($orderId > 0) {
        $stmt = $pdo->prepare('SELECT status FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        $status = $stmt->fetchColumn();
        if (!$status) {
            $error = 'Order number not found. Please check and try again.';
        }
    }
}
?>
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm rounded-4 p-4">
                    <h2>Track Your Order</h2>
                    <p>Enter your order number to view the latest fulfillment status.</p>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <?php if ($status): ?><div class="alert alert-success">Order status: <strong><?php echo esc($status); ?></strong></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-3"><label class="form-label">Order Number</label><input type="number" name="order_id" class="form-control" required></div>
                        <button class="btn btn-gold">Track Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
