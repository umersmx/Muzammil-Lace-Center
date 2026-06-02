<?php
require_once __DIR__ . '/header.php';
$customers = $pdo->query('SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC')->fetchAll();
?>
<div class="container-fluid">
    <div class="row mb-4"><div class="col-12"><h2>Customers</h2></div></div>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr></thead><tbody><?php foreach ($customers as $customer): ?><tr><td><?php echo esc($customer['name']); ?></td><td><?php echo esc($customer['email']); ?></td><td><?php echo esc($customer['phone']); ?></td><td><?php echo esc($customer['created_at']); ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
