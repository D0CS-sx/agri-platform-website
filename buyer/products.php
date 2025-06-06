<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Build WHERE conditions
$conditions = "WHERE p.status = 'approved'";
$params = [];
$types = '';

if (!empty($search)) {
    $conditions .= " AND p.name LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}
if (!empty($category)) {
    $conditions .= " AND p.category = ?";
    $params[] = $category;
    $types .= 's';
}

// Count total products
$count_sql = "SELECT COUNT(*) FROM products p $conditions";
$count_stmt = $conn->prepare($count_sql);
if (!$count_stmt) {
    die("Query Error: " . $conn->error);
}
if (!empty($params)) {
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
if (!$product_stmt) {
    die("Query Error: " . $conn->error);
}

// Add limit and offset to parameters
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$product_stmt->bind_param($types, ...$params);

// Debugging: Log the query and parameters
error_log("SQL Query: $product_sql");
error_log("Parameters: " . json_encode($params));

$product_stmt->execute();
$result = $product_stmt->get_result();

$category_sql = "SELECT DISTINCT category FROM products WHERE status = 'approved'";
$category_result = $conn->query($category_sql);
?>

<div class="container mt-5">
    <h2>Browse Products</h2>

    <!-- Search and Filter -->
    <form method="GET" class="row g-3 mb-4 d-flex align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search products" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php while ($cat_row = $category_result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($cat_row['category']) ?>" <?= ($category === $cat_row['category']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat_row['category']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4 text-center"> <!-- Center the button -->
            <button class="btn btn-primary">Apply</button>
        </div>
    </form>

    <!-- Product Grid -->
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($row['image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="../assets/img/default.jpg" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
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

    <?php if ($result->num_rows === 0): ?>
        <p class="text-center mt-4">No products found. Try adjusting your search or filter criteria.</p>
    <?php endif; ?>

    <!-- Page navigation -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script src="/agri-platform/assets/js/scripts.js"></script>

<?php
$product_stmt->close();
include '../includes/footer.php';
?>
