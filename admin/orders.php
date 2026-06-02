<?php
require_once __DIR__ . '/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([safe_input($_POST['status']), (int)$_POST['order_id']]);
        $success = 'Order status updated.';
    }
}
$orders = $pdo->query('SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC')->fetchAll();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12"><h2>Order Management</h2></div>
    </div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light"><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Placed</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo esc($order['id']); ?></td>
                            <td><?php echo esc($order['customer_name']); ?></td>
                            <td><?php echo format_currency($order['total_amount']); ?></td>
                            <td><?php echo esc($order['status']); ?></td>
                            <td><?php echo esc($order['created_at']); ?></td>
                            <td>
                                <form method="post" class="d-flex gap-2 align-items-center">
                                    <?php echo csrf_input_field(); ?>
                                    <input type="hidden" name="order_id" value="<?php echo esc($order['id']); ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <?php foreach (['Processing','Packed','Shipped','Delivered'] as $status): ?>
                                            <option value="<?php echo esc($status); ?>" <?php echo $order['status']=== $status ? 'selected' : ''; ?>><?php echo esc($status); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-gold" name="update_status">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
