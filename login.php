<?php
require_once __DIR__ . '/includes/header.php';
if (is_logged_in()) {
    redirect('account.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid login attempt.';
    } else {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if ($email && $password) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $redirect = !empty($_GET['redirect']) ? $_GET['redirect'] : 'account.php';
                redirect($redirect);
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Please enter your email and password.';
        }
    }
}
?>
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-4 p-4">
                    <h2 class="mb-3">Customer Login</h2>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                        <button class="btn btn-gold w-100">Login</button>
                    </form>
                    <p class="mt-3 text-center">New customer? <a href="register.php">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
