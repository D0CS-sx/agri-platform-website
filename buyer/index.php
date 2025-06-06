<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Pagination settings
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and category filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build query conditions
$conditions = "WHERE p.status = 'approved'";
$params = [];

if ($search) {
    $conditions .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}

if ($category) {
    $conditions .= " AND p.category = ?";
    $params[] = $category;
}

// Fetch total products for pagination
$count_sql = "SELECT COUNT(*) FROM products p $conditions";
$count_stmt = $conn->prepare($count_sql);
if ($params) {
    $types = str_repeat('s', count($params));
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_stmt->bind_result($total_products);
$count_stmt->fetch();
$count_stmt->close();

$total_pages = ceil($total_products / $limit);

// Fetch products
$product_sql = "SELECT p.*, u.name AS seller_name FROM products p
                JOIN users u ON p.seller_id = u.id
                $conditions
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";
$product_stmt = $conn->prepare($product_sql);
if ($params) {
    $types = str_repeat('s', count($params)) . 'ii';
    $params[] = $limit;
    $params[] = $offset;
    $product_stmt->bind_param($types, ...$params);
} else {
    $product_stmt->bind_param('ii', $limit, $offset);
}
$product_stmt->execute();
$result = $product_stmt->get_result();
?>

<h2>Available Products</h2>

<!-- Search and Category Filter -->
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control search-input" placeholder="Search products...">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-select category-select">
            <option value="">All Categories</option>
            <?php
            $cat_result = $conn->query("SELECT DISTINCT category FROM products WHERE status = 'approved'");
            while ($cat = $cat_result->fetch_assoc()):
            ?>
                <option value="<?= htmlspecialchars($cat['category']) ?>" <?= $category == $cat['category'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-4">
        <button type="submit" class="filter-button">Filter</button>
    </div>
</form>

<div class="row">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php if (!empty($row['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <img src="../assets/images/default-product.jpg" class="card-img-top" alt="Default Image" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($row['description'], 0, 80)) ?>...</p>
                    <p class="text-muted">R<?= number_format($row['price'], 2) ?></p>
                    <small>Seller: <?= htmlspecialchars($row['seller_name']) ?></small>
                    <div class="mt-auto">
                        <a href="product_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
                        <form method="POST" action="add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button class="btn btn-sm btn-success mt-2">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Pagination -->
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php
$product_stmt->close();
include '../includes/footer.php';
?>
