<?php
require_once __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Please try again.';
    } else {
        $name = safe_input($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $subject = safe_input($_POST['subject']);
        $message = safe_input($_POST['message']);
        if ($name && $email && $subject && $message) {
            $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $email, $subject, $message]);
            $success = 'Thank you. Your message has been submitted successfully.';
        } else {
            $error = 'All fields are required.';
        }
    }
}
?>
<section class="section bg-light">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-4 p-4">
                    <h2>Contact Us</h2>
                    <p>Reach out for custom lace orders, wholesale inquiries, or styling support.</p>
                    <p><strong>Address:</strong><br>Allama Iqbal Road, Street 4<br>Mian Channu, Punjab, Pakistan</p>
                    <p><strong>Phone:</strong><br><a href="tel:+923001234567">+92 300 1234567</a></p>
                    <p><strong>Email:</strong><br><a href="mailto:info@muzammillacecenter.com">info@muzammillacecenter.com</a></p>
                    <p><strong>Hours:</strong><br>Mon–Sat: 9am - 8pm</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow-sm rounded-4 p-4">
                    <h2>Send a Message</h2>
                    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
                    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo esc($error); ?></div><?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="col-md-12"><label class="form-label">Subject</label><input type="text" name="subject" class="form-control" required></div>
                            <div class="col-md-12"><label class="form-label">Message</label><textarea name="message" rows="6" class="form-control" required></textarea></div>
                            <div class="col-md-12"><button class="btn btn-gold">Submit Message</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <iframe class="w-100 rounded-4 shadow-sm" height="400" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3320.0000000000005!2d73.44831431560382!3d30.0375763185588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391145d4d4f0dc01%3A0x0000000000000000!2sMian%20Channu%2C%20Pakistan!5e0!3m2!1sen!2sus!4v0000000000000" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
