<?php
require_once __DIR__ . '/includes/header.php';
$stmt = $pdo->query('SELECT * FROM blog_posts WHERE status = 1 ORDER BY published_at DESC');
$posts = $stmt->fetchAll();
?>
<section class="section bg-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <ul class="breadcrumb-premium animate-on-scroll">
            <li><a href="index.php">Home</a></li>
            <li>Blog</li>
        </ul>

        <div class="d-flex justify-content-between align-items-end mb-5 animate-on-scroll">
            <div>
                <span class="section-title text-gold">Fashion Journal</span>
                <h2 style="font-family:var(--font-heading);">Style Ideas & Inspiration</h2>
            </div>
        </div>

        <?php if (empty($posts)): ?>
            <div class="card p-5 text-center animate-on-scroll border-0 shadow-sm bg-white">
                <i class="fa fa-newspaper fa-4x text-muted mb-4 opacity-50"></i>
                <h4 style="font-family:var(--font-heading);">No articles found</h4>
                <p class="text-muted mb-0">We are currently working on new fashion content. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $i => $post): ?>
                    <div class="col-md-4 animate-on-scroll stagger-<?php echo ($i % 3) + 1; ?>">
                        <div class="card product-card h-100 rounded-4 border-0">
                            <img src="assets/images/blog-<?php echo esc($post['id']); ?>.svg" class="card-img-top" alt="<?php echo esc($post['title']); ?>" loading="lazy">
                            <div class="card-body d-flex flex-column p-4">
                                <small class="text-gold fw-bold text-uppercase mb-2" style="letter-spacing:0.05em;font-size:0.75rem;"><i class="fa fa-calendar-alt me-1"></i> <?php echo date('F d, Y', strtotime($post['published_at'])); ?></small>
                                <h5 class="card-title mb-3" style="font-family:var(--font-heading);line-height:1.4;"><?php echo esc($post['title']); ?></h5>
                                <p class="text-muted" style="font-size:0.95rem;line-height:1.7;"><?php echo esc(substr($post['excerpt'], 0, 120)); ?>...</p>
                                <div class="mt-auto pt-3">
                                    <a href="post.php?id=<?php echo esc($post['id']); ?>" class="btn btn-outline-maroon w-100 stretched-link" style="border-radius:var(--radius-pill);">Read Article</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
