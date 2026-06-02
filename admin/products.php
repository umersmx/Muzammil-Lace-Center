<?php
require_once __DIR__ . '/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        if (isset($_POST['save_product'])) {
            $name = safe_input($_POST['name']);
            $category = (int)$_POST['category_id'];
            $price = (float)$_POST['price'];
            $sale = (float)$_POST['sale_price'];
            $description = safe_input($_POST['description']);
            $short = safe_input($_POST['short_description']);
            $stock = (int)$_POST['stock'];
            $color = safe_input($_POST['color']);
            $material = safe_input($_POST['material']);
            $width = safe_input($_POST['width']);
            $status = isset($_POST['status']) ? 1 : 0;
            if (!empty($_POST['id'])) {
                $stmt = $pdo->prepare('UPDATE products SET name = ?, category_id = ?, price = ?, sale_price = ?, description = ?, short_description = ?, stock = ?, color = ?, material = ?, width = ?, status = ? WHERE id = ?');
                $stmt->execute([$name, $category, $price, $sale, $description, $short, $stock, $color, $material, $width, $status, (int)$_POST['id']]);
                $success = 'Product updated.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO products (name, category_id, price, sale_price, description, short_description, stock, color, material, width, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$name, $category, $price, $sale, $description, $short, $stock, $color, $material, $width, $status]);
                $productId = $pdo->lastInsertId();
                for ($i = 1; $i <= 3; $i++) {
                    if (!empty($_FILES['image_' . $i]['tmp_name'])) {
                        $uploadName = 'product-' . $productId . '-' . $i . '-' . time() . '.' . pathinfo($_FILES['image_' . $i]['name'], PATHINFO_EXTENSION);
                        move_uploaded_file($_FILES['image_' . $i]['tmp_name'], __DIR__ . '/../uploads/' . $uploadName);
                        $stmtImg = $pdo->prepare('INSERT INTO product_images (product_id, file_name, `order`) VALUES (?, ?, ?)');
                        $stmtImg->execute([$productId, $uploadName, $i]);
                    }
                }
                $success = 'Product added.';
            }
        }
        if (isset($_POST['delete_product'])) {
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([(int)$_POST['delete_product']]);
            $success = 'Product removed.';
        }
    }
}
$products = $pdo->query('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC')->fetchAll();
$categories = get_categories();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center"><h2>Products</h2><button class="btn btn-gold" data-bs-toggle="collapse" data-bs-target="#productForm">Add Product</button></div>
    </div>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo esc($success); ?></div><?php endif; ?>
    <div class="collapse mb-4" id="productForm">
        <div class="card p-4 shadow-sm">
            <form method="post" enctype="multipart/form-data">
                <?php echo csrf_input_field(); ?>
                <input type="hidden" name="id" value="">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Category</label><select name="category_id" class="form-select" required><?php foreach ($categories as $cat): ?><option value="<?php echo esc($cat['id']); ?>"><?php echo esc($cat['name']); ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-2"><label class="form-label">Price</label><input name="price" class="form-control" required></div>
                    <div class="col-md-2"><label class="form-label">Sale Price</label><input name="sale_price" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Stock</label><input name="stock" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Color</label><input name="color" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Material</label><input name="material" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Width</label><input name="width" class="form-control"></div>
                    <div class="col-md-8"><label class="form-label">Short Description</label><input name="short_description" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-control"></textarea></div>
                    <div class="col-md-4"><label class="form-label">Image 1</label><input type="file" name="image_1" accept="image/*" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Image 2</label><input type="file" name="image_2" accept="image/*" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">Image 3</label><input type="file" name="image_3" accept="image/*" class="form-control"></div>
                    <div class="col-12 form-check"><input type="checkbox" name="status" class="form-check-input" id="statusCheck" checked><label class="form-check-label" for="statusCheck">Active</label></div>
                    <div class="col-12"><button class="btn btn-gold" name="save_product">Save Product</button></div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Category</th><th>Price</th><th>Sale</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo esc($product['name']); ?></td>
                        <td><?php echo esc($product['category_name']); ?></td>
                        <td><?php echo format_currency($product['price']); ?></td>
                        <td><?php echo format_currency($product['sale_price']); ?></td>
                        <td><?php echo esc($product['stock']); ?></td>
                        <td><?php echo $product['status'] ? 'Active' : 'Draft'; ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <?php echo csrf_input_field(); ?>
                                <button class="btn btn-sm btn-danger" name="delete_product" value="<?php echo esc($product['id']); ?>">Delete</button>
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
