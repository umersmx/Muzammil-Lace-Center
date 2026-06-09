<?php
require_once __DIR__ . '/includes/header.php';
if (is_logged_in()) {
    redirect('account.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid submission.';
    } else {
        $name = safe_input($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $phone = safe_input($_POST['phone']);
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if ($name && $email && $phone && $password && $password === $confirm) {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email is already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())');
                $stmt->execute([$name, $email, $phone, $hash]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
                redirect('account.php');
            }
        } else {
            $error = 'Please complete all fields and confirm your password.';
        }
    }
}
?>
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="auth-card animate-on-scroll stagger-1">
                    <div class="row g-0">
                        <div class="col-md-5 auth-decorative d-none d-md-flex">
                            <h3 class="mb-3 text-center">Join Our Community</h3>
                            <p class="text-center px-4">Create an account to track your orders, save items to your luxury wishlist, and checkout faster.</p>
                            <div class="mt-5 text-start w-100 px-4">
                                <div class="d-flex align-items-center mb-3"><i class="fa fa-check-circle text-gold me-3 fa-lg"></i> <span style="color:rgba(255,255,255,0.9);">Fast checkout process</span></div>
                                <div class="d-flex align-items-center mb-3"><i class="fa fa-check-circle text-gold me-3 fa-lg"></i> <span style="color:rgba(255,255,255,0.9);">Exclusive seasonal offers</span></div>
                                <div class="d-flex align-items-center mb-3"><i class="fa fa-check-circle text-gold me-3 fa-lg"></i> <span style="color:rgba(255,255,255,0.9);">Track order history</span></div>
                            </div>
                        </div>
                        <div class="col-md-7 p-4 p-md-5 bg-white">
                            <h2 class="mb-4" style="font-family:var(--font-heading);color:var(--primary);">Create Account</h2>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center"><i class="fa fa-exclamation-circle me-2"></i> <?php echo esc($error); ?></div>
                            <?php endif; ?>
                            <form method="post">
                                <?php echo csrf_input_field(); ?>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Full Name</label>
                                        <input type="text" name="name" class="form-control" required placeholder="John Doe">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Email Address</label>
                                        <input type="email" name="email" class="form-control" required placeholder="you@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" required placeholder="+92 300 0000000">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Password</label>
                                        <input type="password" name="password" class="form-control" required placeholder="Create password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.8rem;">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm password">
                                    </div>
                                </div>
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label text-muted small" for="terms">I agree to the <a href="#" class="text-maroon">Terms of Service</a> and <a href="#" class="text-maroon">Privacy Policy</a>.</label>
                                </div>
                                <div class="d-grid mt-4">
                                    <button class="btn btn-gold btn-lg shadow-gold">Create Account <i class="fa fa-user-plus ms-2"></i></button>
                                </div>
                            </form>
                            <div class="text-center mt-5">
                                <p class="text-muted mb-0">Already registered? <a href="login.php" class="text-maroon fw-bold ms-1">Login here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
