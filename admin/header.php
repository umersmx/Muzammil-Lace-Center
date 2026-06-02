<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
if (!isset($_SESSION)) {
    session_start();
}
$adminPage = basename($_SERVER['PHP_SELF']);
if ($adminPage !== 'login.php' && empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Muzammil Lace Center</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.admin-sidebar { min-height:100vh; background:#800020; color:#fff; }.admin-sidebar a { color:#fff; }.admin-sidebar a.active, .admin-sidebar a:hover { color:#d4af37; }</style>
</head>
<body>
<div class="d-flex">
    <?php if ($adminPage !== 'login.php'): ?>
    <aside class="admin-sidebar p-4">
        <h3 class="text-gold">Admin</h3>
        <nav class="nav flex-column mt-4">
            <a class="nav-link <?php echo $adminPage==='dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
            <a class="nav-link <?php echo $adminPage==='products.php' ? 'active' : ''; ?>" href="products.php">Products</a>
            <a class="nav-link <?php echo $adminPage==='categories.php' ? 'active' : ''; ?>" href="categories.php">Categories</a>
            <a class="nav-link <?php echo $adminPage==='orders.php' ? 'active' : ''; ?>" href="orders.php">Orders</a>
            <a class="nav-link <?php echo $adminPage==='customers.php' ? 'active' : ''; ?>" href="customers.php">Customers</a>
            <a class="nav-link <?php echo $adminPage==='blog.php' ? 'active' : ''; ?>" href="blog.php">Blog Posts</a>
            <a class="nav-link <?php echo $adminPage==='reviews.php' ? 'active' : ''; ?>" href="reviews.php">Reviews</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </nav>
    </aside>
    <main class="flex-grow-1 p-4">
    <?php else: ?>
    <main class="flex-grow-1 p-4 bg-light min-vh-100">
    <?php endif; ?>
