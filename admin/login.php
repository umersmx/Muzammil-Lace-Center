<?php
require_once __DIR__ . '/header.php';
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if ($email && $password) {
            $stmt = $pdo->prepare('SELECT * FROM admin WHERE email = ?');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header('Location: dashboard.php');
                exit;
            }
            $error = 'Invalid email or password.';
        } else {
            $error = 'Please enter credentials.';
        }
    }
}
?>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 380px;">
        <h3 class="mb-3">Admin Login</h3>
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
        <form method="post">
            <?php echo csrf_input_field(); ?>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <button class="btn btn-gold w-100">Login</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
