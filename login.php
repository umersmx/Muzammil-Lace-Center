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
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="auth-card animate-on-scroll">
                    <div class="row g-0">
                        <div class="col-md-5 auth-decorative d-none d-md-flex">
                            <h3 class="mb-3 text-center">Welcome Back</h3>
                            <p class="text-center px-4">Sign in to access your luxury lace orders, wishlists, and exclusive offers.</p>
                            <img src="assets/images/owner.jpg" alt="Luxury" class="mt-4 rounded-circle border border-2 border-white shadow" style="width:100px;height:100px;object-fit:cover;">
                        </div>
                        <div class="col-md-7 p-4 p-md-5 bg-white">
                            <h2 class="mb-4" style="font-family:var(--font-heading);color:var(--primary);">Customer Login</h2>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center"><i class="fa fa-exclamation-circle me-2"></i> <?php echo esc($error); ?></div>
                            <?php endif; ?>
                            <form method="post">
                                <?php echo csrf_input_field(); ?>
                                <div class="mb-4">
                                    <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg" required autofocus placeholder="you@example.com">
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Password</label>
                                        <a href="#" class="text-maroon small text-decoration-none">Forgot password?</a>
                                    </div>
                                    <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
                                </div>
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label text-muted small" for="rememberMe">Remember me for 30 days</label>
                                </div>
                                <div class="d-grid mt-4">
                                    <button class="btn btn-gold btn-lg shadow-gold">Sign In <i class="fa fa-sign-in-alt ms-2"></i></button>
                                </div>
                            </form>
                            <div class="text-center mt-5">
                                <p class="text-muted mb-0">New to Muzammil Lace Center? <a href="register.php" class="text-maroon fw-bold ms-1">Create an account</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
