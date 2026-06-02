<?php
require_once __DIR__ . '/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        if (isset($_POST['save_post'])) {
            $title = safe_input($_POST['title']);
            $excerpt = safe_input($_POST['excerpt']);
            $content = safe_input($_POST['content']);
            $author = safe_input($_POST['author']);
            $status = isset($_POST['status']) ? 1 : 0;
            if ($title && $content) {
                $stmt = $pdo->prepare('INSERT INTO blog_posts (title, excerpt, content, author, status, published_at, created_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
                $stmt->execute([$title, $excerpt, $content, $author, $status]);
                $success = 'Blog post added.';
            }
        }
        if (isset($_POST['delete_post'])) {
            $stmt = $pdo->prepare('DELETE FROM blog_posts WHERE id = ?');
            $stmt->execute([(int)$_POST['delete_post']]);
            $success = 'Blog post deleted.';
        }
    }
}
$posts = $pdo->query('SELECT * FROM blog_posts ORDER BY published_at DESC')->fetchAll();
?>
<div class="container-fluid">
    <div class="row mb-4"><div class="col-12 d-flex justify-content-between align-items-center"><h2>Blog Management</h2><button class="btn btn-gold" data-bs-toggle="collapse" data-bs-target="#blogForm">Add Post</button></div></div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
    <div class="collapse mb-4" id="blogForm"><div class="card p-4 shadow-sm"><form method="post"><input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>"><div class="row g-3"><div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" required></div><div class="col-md-6"><label class="form-label">Author</label><input name="author" class="form-control" value="Muzammil Lace Center"></div><div class="col-12"><label class="form-label">Excerpt</label><textarea name="excerpt" class="form-control" rows="2"></textarea></div><div class="col-12"><label class="form-label">Content</label><textarea name="content" class="form-control" rows="5" required></textarea></div><div class="col-md-3 form-check"><input class="form-check-input" type="checkbox" id="statusCheck" name="status" checked><label class="form-check-label" for="statusCheck">Publish</label></div><div class="col-md-9"><button class="btn btn-gold" name="save_post">Save Post</button></div></div></form></div></div>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead class="table-light"><tr><th>Title</th><th>Published</th><th>Status</th><th></th></tr></thead><tbody><?php foreach ($posts as $post): ?><tr><td><?php echo esc($post['title']); ?></td><td><?php echo esc($post['published_at']); ?></td><td><?php echo $post['status'] ? 'Published' : 'Draft'; ?></td><td><form method="post" class="d-inline"><?php echo csrf_input_field(); ?><button class="btn btn-sm btn-danger" name="delete_post" value="<?php echo esc($post['id']); ?>">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
