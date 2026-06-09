<?php
require_once __DIR__ . '/includes/header.php';
$status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission.';
    } else {
        $orderId = (int)($_POST['order_id'] ?? 0);
        if ($orderId > 0) {
            $stmt = $pdo->prepare('SELECT status FROM orders WHERE id = ?');
            $stmt->execute([$orderId]);
            $status = $stmt->fetchColumn();
            if (!$status) {
                $error = 'Order number not found. Please check your order ID and try again.';
            }
        }
    }
}
?>
<section class="auth-section">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll justify-content-center mb-5">
            <li><a href="index.php">Home</a></li>
            <li>Track Order</li>
        </ul>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white animate-on-scroll text-center">
                    <div class="mx-auto mb-4" style="width:72px;height:72px;background:var(--bg-warm);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-truck-fast text-maroon fa-2x"></i>
                    </div>
                    <h2 class="mb-3" style="font-family:var(--font-heading);">Track Your Order</h2>
                    <p class="text-muted mb-4 px-md-4">Enter your order number below to view the latest fulfillment and shipping status of your luxury lace order.</p>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center justify-content-center"><i class="fa fa-exclamation-circle me-2"></i> <?php echo esc($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($status): ?>
                        <div class="alert alert-info border-0 bg-light p-4 mb-4">
                            <h5 class="mb-2 text-dark">Order #<?php echo esc($orderId); ?></h5>
                            <p class="mb-0">Current Status: 
                                <?php 
                                $statusClass = 'bg-secondary';
                                if ($status === 'Processing') $statusClass = 'bg-primary';
                                if ($status === 'Shipped') $statusClass = 'bg-info text-dark';
                                if ($status === 'Delivered') $statusClass = 'bg-success';
                                ?>
                                <span class="badge <?php echo $statusClass; ?> rounded-pill fw-normal px-3 ms-2" style="font-size:0.9rem;"><?php echo esc($status); ?></span>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="text-start">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-4">
                            <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Order Number</label>
                            <input type="number" name="order_id" class="form-control form-control-lg text-center fw-bold" required placeholder="e.g. 1045" min="1" value="<?php echo isset($_POST['order_id']) ? esc($_POST['order_id']) : ''; ?>">
                        </div>
                        <button class="btn btn-gold btn-lg shadow-gold w-100"><i class="fa fa-search me-2"></i>Track Order</button>
                    </form>
                    
                    <div class="mt-4 pt-4 border-top">
                        <p class="small text-muted mb-0">Can't find your order number? Check the confirmation email sent to you after checkout, or <a href="contact.php" class="text-maroon">contact support</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
