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
<section class="section bg-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li>Contact Us</li>
        </ul>

        <div class="text-center mb-5 animate-on-scroll">
            <span class="section-title text-gold">Get In Touch</span>
            <h2 style="font-family:var(--font-heading);">We'd Love to Hear From You</h2>
        </div>

        <div class="row gy-5">
            <div class="col-lg-5 animate-on-scroll">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                    <h3 class="mb-4" style="font-family:var(--font-heading);">Contact Information</h3>
                    <p class="text-muted mb-5">Reach out for custom lace orders, wholesale inquiries, bridal styling support, or any questions about our luxury collections.</p>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-map-marker-alt text-maroon fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-family:var(--font-heading);">Boutique Location</h6>
                            <p class="text-muted mb-0">Allama Iqbal Road, Street 4<br>Mian Channu, Punjab, Pakistan</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-phone text-maroon fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-family:var(--font-heading);">Phone & WhatsApp</h6>
                            <p class="text-muted mb-0"><a href="tel:+923001234567" class="text-muted text-decoration-none hover-primary">+92 300 1234567</a></p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-envelope text-maroon fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-family:var(--font-heading);">Email Address</h6>
                            <p class="text-muted mb-0"><a href="mailto:info@muzammillacecenter.com" class="text-muted text-decoration-none hover-primary">info@muzammillacecenter.com</a></p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width:48px;height:48px;min-width:48px;">
                            <i class="fa fa-clock text-maroon fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-family:var(--font-heading);">Business Hours</h6>
                            <p class="text-muted mb-0">Monday – Saturday<br>9:00 AM - 8:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 animate-on-scroll stagger-2">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                    <h3 class="mb-4" style="font-family:var(--font-heading);">Send a Message</h3>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4"><i class="fa fa-check-circle me-2 fs-5"></i> <?php echo esc($success); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4"><i class="fa fa-exclamation-circle me-2 fs-5"></i> <?php echo esc($error); ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <?php echo csrf_input_field(); ?>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0" required placeholder="John Doe">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Email Address</label>
                                <input type="email" name="email" class="form-control bg-light border-0" required placeholder="you@example.com">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Subject</label>
                                <input type="text" name="subject" class="form-control bg-light border-0" required placeholder="How can we help you?">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-uppercase text-muted" style="letter-spacing:0.05em;font-size:0.75rem;">Message</label>
                                <textarea name="message" rows="6" class="form-control bg-light border-0" required placeholder="Write your message here..."></textarea>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button class="btn btn-gold btn-lg shadow-gold w-100"><i class="fa fa-paper-plane me-2"></i>Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-5 animate-on-scroll">
            <div class="col-12">
                <iframe class="w-100 rounded-4 shadow-sm border-0" height="450" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3320.0000000000005!2d73.44831431560382!3d30.0375763185588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391145d4d4f0dc01%3A0x0000000000000000!2sMian%20Channu%2C%20Pakistan!5e0!3m2!1sen!2sus!4v0000000000000" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" style="filter: grayscale(20%) contrast(1.1);"></iframe>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
