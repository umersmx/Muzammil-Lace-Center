<?php
require_once __DIR__ . '/includes/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ? AND status = 1');
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) {
    http_response_code(404);
    echo '<section class="section"><div class="container"><div class="alert alert-danger">Blog post not found.</div></div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>
<section class="section bg-white">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-8 mx-auto">
                <img src="assets/images/blog-<?php echo esc($post['id']); ?>.svg" class="w-100 rounded-4 shadow-sm mb-4" alt="<?php echo esc($post['title']); ?>">
                <span class="section-title text-gold">Blog</span>
                <h1><?php echo esc($post['title']); ?></h1>
                <p class="text-muted">Published on <?php echo date('F d, Y', strtotime($post['published_at'])); ?> | <?php echo esc($post['author']); ?></p>
                <div class="content">
                    <?php echo nl2br(esc($post['content'])); ?>
                </div>
                <div class="mt-4">
                    <a href="blog.php" class="btn btn-outline-maroon">Back to Blog</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
