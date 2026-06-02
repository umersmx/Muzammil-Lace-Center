<?php
require_once __DIR__ . '/header.php';
$stats = get_admin_stats();
$totalSales = format_currency($stats['sales']);
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12"><h2>Admin Dashboard</h2></div>
    </div>
    <div class="row g-4">
        <div class="col-md-3"><div class="card p-4 shadow-sm"><h5>Total Orders</h5><p class="display-6"><?php echo esc($stats['orders']); ?></p></div></div>
        <div class="col-md-3"><div class="card p-4 shadow-sm"><h5>Total Sales</h5><p class="display-6"><?php echo esc($totalSales); ?></p></div></div>
        <div class="col-md-3"><div class="card p-4 shadow-sm"><h5>Total Products</h5><p class="display-6"><?php echo esc($stats['products']); ?></p></div></div>
        <div class="col-md-3"><div class="card p-4 shadow-sm"><h5>Customers</h5><p class="display-6"><?php echo esc($stats['customers']); ?></p></div></div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
