<?php
require_once __DIR__ . '/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        if (isset($_POST['save_category'])) {
            $name = safe_input($_POST['name']);
            if ($name) {
                $stmt = $pdo->prepare('INSERT INTO categories (name, created_at) VALUES (?, NOW())');
                $stmt->execute([$name]);
                $success = 'Category added.';
            }
        }
        if (isset($_POST['delete_category'])) {
            $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->execute([(int)$_POST['delete_category']]);
            $success = 'Category deleted.';
        }
    }
}
$categories = get_categories();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center"><h2>Category Management</h2></div>
    </div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
    <div class="card shadow-sm p-4 mb-4">
        <form method="post" class="row g-3 align-items-end">
            <?php echo csrf_input_field(); ?>
            <div class="col-md-8"><label class="form-label">Category Name</label><input name="name" class="form-control" required></div>
            <div class="col-md-4"><button class="btn btn-gold w-100" name="save_category">Add Category</button></div>
        </form>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Created</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo esc($category['name']); ?></td>
                            <td><?php echo esc($category['created_at']); ?></td>
                            <td>
                                <form method="post" class="d-inline">
                                    <?php echo csrf_input_field(); ?>
                                    <button class="btn btn-sm btn-danger" name="delete_category" value="<?php echo esc($category['id']); ?>">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
