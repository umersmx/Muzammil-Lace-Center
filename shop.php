<?php
require_once __DIR__ . '/includes/functions.php';
global $pdo;
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;
$filters = [];
$categories = get_categories();
$sql = 'SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 1';
$params = [];
if ($category_id) {
    $sql .= ' AND p.category_id = ?';
    $params[] = $category_id;
}
if ($search !== '') {
    $sql .= ' AND (p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sortSql = 'ORDER BY p.created_at DESC';
switch ($sort) {
    case 'price-low': $sortSql = 'ORDER BY p.sale_price ASC'; break;
    case 'price-high': $sortSql = 'ORDER BY p.sale_price DESC'; break;
    case 'popular': $sortSql = 'ORDER BY p.rating DESC'; break;
}
$totalStmt = $pdo->prepare(str_replace('p.*, c.name AS category_name', 'COUNT(*)', $sql));
$totalStmt->execute($params);
$totalProducts = $totalStmt->fetchColumn();
$stmt = $pdo->prepare($sql . " $sortSql LIMIT ? OFFSET ?");
foreach ($params as $i => $value) {
    $stmt->bindValue($i + 1, $value);
}
$stmt->bindValue(count($params) + 1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();
$totalPages = ceil($totalProducts / $perPage);

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $responseHtml = '';
    if (empty($products)) {
        $responseHtml = '<div class="col-12"><div class="alert alert-warning">No products match your filters. Try another search.</div></div>';
    } else {
        foreach ($products as $product) {
            $responseHtml .= '<div class="col-md-4">';
            $responseHtml .= '<div class="card product-card animate-on-scroll">';
            $responseHtml .= '<img src="assets/images/product-' . esc($product['id']) . '-1.svg" class="card-img-top" alt="' . esc($product['name']) . '">';
            $responseHtml .= '<div class="card-body">';
            $responseHtml .= '<span class="badge bg-gold text-dark mb-2">' . esc($product['category_name']) . '</span>';
            $responseHtml .= '<h5 class="card-title">' . esc($product['name']) . '</h5>';
            $responseHtml .= '<p class="text-muted mb-2">' . format_currency($product['sale_price']) . ' <span class="text-decoration-line-through text-muted ms-2">' . format_currency($product['price']) . '</span></p>';
            $responseHtml .= '<div class="d-flex justify-content-between align-items-center mb-3">';
            $responseHtml .= '<div><i class="fa fa-star text-gold"></i> ' . esc($product['rating']) . '</div>';
            $responseHtml .= '<div><a href="product.php?id=' . esc($product['id']) . '" class="btn btn-outline-maroon btn-sm">View</a></div>';
            $responseHtml .= '</div>';
            $responseHtml .= '<div class="d-grid gap-2">';
            $responseHtml .= '<a href="cart.php?add=' . esc($product['id']) . '" class="btn btn-gold btn-sm">Add To Cart</a>';
            $responseHtml .= '</div></div></div></div>';
        }
    }
    echo json_encode(['html' => $responseHtml, 'count' => (int)$totalProducts]);
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="section bg-light animate-on-scroll">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-3">
                <div class="p-4 bg-white rounded-4 shadow-sm">
                    <h4>Filters</h4>
                    <form id="filterForm" method="get" action="shop.php">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="shopSearch" name="search" value="<?php echo esc($search); ?>" placeholder="Search products...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo esc($cat['id']); ?>" <?php echo $category_id === (int)$cat['id'] ? 'selected' : ''; ?>><?php echo esc($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort</label>
                            <select class="form-select" name="sort">
                                <option value="latest" <?php echo $sort === 'latest' ? 'selected' : ''; ?>>Latest</option>
                                <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Popular</option>
                                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price Low to High</option>
                                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price High to Low</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-gold w-100">Apply</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div><h2 class="mb-0">Shop Collection</h2><small class="text-muted shop-product-count"><?php echo $totalProducts; ?> products found</small></div>
                </div>
                <div id="searchResults" class="row g-4">
                    <?php if (empty($products)): ?>
                        <div class="col-12"><div class="alert alert-warning">No products match your filters. Try another search.</div></div>
                    <?php endif; ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card product-card animate-on-scroll">
                                <img src="assets/images/product-<?php echo esc($product['id']); ?>-1.svg" class="card-img-top" alt="<?php echo esc($product['name']); ?>">
                                <div class="card-body">
                                    <span class="badge bg-gold text-dark mb-2"><?php echo esc($product['category_name']); ?></span>
                                    <h5 class="card-title"><?php echo esc($product['name']); ?></h5>
                                    <p class="text-muted mb-2"><?php echo format_currency($product['sale_price']); ?> <span class="text-decoration-line-through text-muted ms-2"><?php echo format_currency($product['price']); ?></span></p>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div><i class="fa fa-star text-gold"></i> <?php echo esc($product['rating']); ?></div>
                                        <div><a href="product.php?id=<?php echo esc($product['id']); ?>" class="btn btn-outline-maroon btn-sm">View</a></div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="cart.php?add=<?php echo esc($product['id']); ?>" class="btn btn-gold btn-sm">Add To Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="shop.php?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
