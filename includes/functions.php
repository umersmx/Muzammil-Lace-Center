<?php
require_once __DIR__ . '/db.php';

function esc($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function current_user() {
    global $pdo;
    if (!is_logged_in()) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function get_categories() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

function get_featured_products($limit = 8) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 1 ORDER BY p.created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_product_images($product_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY `order` ASC');
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

function cart_items() {
    return $_SESSION['cart'] ?? [];
}

function cart_count() {
    $items = cart_items();
    return array_sum(array_column($items, 'quantity'));
}

function wishlist_count() {
    global $pdo;
    if (!is_logged_in()) {
        return 0;
    }
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetchColumn();
}

function get_active_order_status($status) {
    $states = ['Processing', 'Packed', 'Shipped', 'Delivered'];
    return in_array($status, $states) ? $status : 'Processing';
}

function format_currency($amount) {
    return 'Rs ' . number_format($amount, 0, '.', ',');
}

function get_admin_stats() {
    global $pdo;
    $stats = [];
    $stats['orders'] = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    $stats['sales'] = $pdo->query('SELECT IFNULL(SUM(total_amount),0) FROM orders')->fetchColumn();
    $stats['products'] = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $stats['customers'] = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    return $stats;
}
