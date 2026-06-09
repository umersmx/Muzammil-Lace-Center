<?php
require_once __DIR__ . '/functions.php';
$currentPage = basename($_SERVER['PHP_SELF']);
$categories = get_categories();
$cartCount = cart_count();
$wishCount = wishlist_count();
$currentLang = isset($_COOKIE['site_language']) ? $_COOKIE['site_language'] : 'en';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muzammil Lace Center | Premium Lace & Accessories</title>
    <meta name="description" content="Muzammil Lace Center | Premium bridal laces, embroidery borders, decorative ribbons, garment accessories & sewing materials since 2004.">
    <meta property="og:title" content="Muzammil Lace Center | Premium Lace & Accessories">
    <meta property="og:description" content="Discover luxurious lace collections, bridal accessories, fashion trims, and quality sewing materials from Pakistan's trusted lace center.">
    <meta property="og:image" content="assets/images/og-image.jpg">
    <meta property="og:type" content="website">
    <link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <link rel="icon" href="assets/images/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="topbar text-white py-2">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small><i class="fa fa-map-marker-alt me-1"></i> <span class="lang-en">Allama Iqbal Road, Street 4, Mian Channu</span><span class="lang-ur" style="display:none;">علامہ اقبال روڈ، سٹریٹ 4، میاں چنّو</span></small>
            <div class="d-flex gap-3 align-items-center">
                <a href="tel:+923001234567" class="text-white"><i class="fa fa-phone me-1"></i> +92 300 1234567</a>
                <a href="mailto:info@muzammillacecenter.com" class="text-white d-none d-md-inline"><i class="fa fa-envelope me-1"></i> <span class="lang-en">info@muzammillacecenter.com</span></a>
                <div class="d-flex gap-2 ms-2">
                    <a href="#" class="text-white" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/923001234567" class="text-white" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
                <button id="langToggle" class="btn btn-sm btn-outline-light ms-2" style="font-size:0.75rem; padding:0.2rem 0.6rem;" title="Toggle Urdu/English"><i class="fa fa-globe me-1"></i>اردو/EN</button>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white position-relative">
        <div class="container">
            <a class="navbar-brand" href="index.php"><span class="lang-en">Muzammil Lace Center</span><span class="lang-ur" style="display:none;">مزمل لیس سنٹر</span></a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link<?php echo $currentPage==='index.php' ? ' active' : ''; ?>" href="index.php"><span class="lang-en">Home</span><span class="lang-ur" style="display:none;">ہوم</span></a></li>
                    <li class="nav-item"><a class="nav-link<?php echo $currentPage==='about.php' ? ' active' : ''; ?>" href="about.php"><span class="lang-en">About</span><span class="lang-ur" style="display:none;">تعارف</span></a></li>
                    <li class="nav-item"><a class="nav-link<?php echo $currentPage==='shop.php' ? ' active' : ''; ?>" href="shop.php"><span class="lang-en">Shop</span><span class="lang-ur" style="display:none;">دکان</span></a></li>
                    <li class="nav-item"><a class="nav-link<?php echo $currentPage==='blog.php' ? ' active' : ''; ?>" href="blog.php"><span class="lang-en">Blog</span><span class="lang-ur" style="display:none;">بلاگ</span></a></li>
                    <li class="nav-item"><a class="nav-link<?php echo $currentPage==='contact.php' ? ' active' : ''; ?>" href="contact.php"><span class="lang-en">Contact</span><span class="lang-ur" style="display:none;">رابطہ</span></a></li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item"><a class="nav-link<?php echo $currentPage==='account.php' ? ' active' : ''; ?>" href="account.php"><i class="fa fa-user-circle me-1"></i><span class="lang-en">Account</span><span class="lang-ur" style="display:none;">اکاؤنٹ</span></a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link<?php echo $currentPage==='login.php' ? ' active' : ''; ?>" href="login.php"><i class="fa fa-user me-1"></i><span class="lang-en">Login</span><span class="lang-ur" style="display:none;">لاگ ان</span></a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="wishlist.php"><i class="fa fa-heart"></i> <span id="wishlist-badge" class="badge bg-gold text-dark"><?php echo $wishCount; ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="fa fa-shopping-bag"></i> <span id="cart-badge" class="badge bg-maroon text-white"><?php echo $cartCount; ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main>
