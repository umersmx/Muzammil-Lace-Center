<?php
require_once __DIR__ . '/includes/header.php';
$stmt = $pdo->query('SELECT * FROM blog_posts WHERE status = 1 ORDER BY published_at DESC');
$posts = $stmt->fetchAll();
?>
<section class="section bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-title text-gold">Fashion Journal</span>
                <h2>Blog & Styling Ideas</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4">
                    <div class="card rounded-4 shadow-sm overflow-hidden">
                        <img src="assets/images/blog-<?php echo esc($post['id']); ?>.svg" class="card-img-top" alt="<?php echo esc($post['title']); ?>">
                        <div class="card-body">
                            <small class="text-muted"><?php echo date('F d, Y', strtotime($post['published_at'])); ?></small>
                            <h5><?php echo esc($post['title']); ?></h5>
                            <p><?php echo esc(substr($post['excerpt'], 0, 120)); ?>...</p>
                            <a href="post.php?id=<?php echo esc($post['id']); ?>" class="btn btn-outline-maroon btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
