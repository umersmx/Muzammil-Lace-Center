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
<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-4 p-4">
                    <h2 class="mb-3">Create Your Account</h2>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="tel" name="phone" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
                        <button class="btn btn-gold w-100">Register</button>
                    </form>
                    <p class="mt-3 text-center">Already registered? <a href="login.php">Login now</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
