<?php
require_once __DIR__ . '/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        if (isset($_POST['approve'])) {
            $stmt = $pdo->prepare('UPDATE reviews SET status = 1 WHERE id = ?');
            $stmt->execute([(int)$_POST['approve']]);
            $success = 'Review approved.';
        }
        if (isset($_POST['delete_review'])) {
            $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = ?');
            $stmt->execute([(int)$_POST['delete_review']]);
            $success = 'Review deleted.';
        }
    }
}
$reviews = $pdo->query('SELECT r.*, u.name AS customer_name, p.name AS product_name FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC')->fetchAll();
?>
<div class="container-fluid">
    <div class="row mb-4"><div class="col-12"><h2>Review Management</h2></div></div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Customer</th><th>Product</th><th>Rating</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach ($reviews as $review): ?><tr><td><?php echo esc($review['customer_name']); ?></td><td><?php echo esc($review['product_name']); ?></td><td><?php echo esc($review['rating']); ?></td><td><?php echo esc($review['message']); ?></td><td><?php echo $review['status'] ? 'Approved' : 'Pending'; ?></td><td><form method="post" class="d-inline"><?php echo csrf_input_field(); if (!$review['status']): ?><button class="btn btn-sm btn-success" name="approve" value="<?php echo esc($review['id']); ?>">Approve</button><?php endif; ?><button class="btn btn-sm btn-danger" name="delete_review" value="<?php echo esc($review['id']); ?>">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
