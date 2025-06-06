<?php
ob_start(); // Start output buffering
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /agri-platform/admin/admin_login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';

ob_end_flush(); // Flush the output buffer

// Fetch products
$product_query = "SELECT id, name, price, weight, status FROM products";
if (isset($_GET['filter_status'])) {
    $product_query .= " WHERE status = '" . $conn->real_escape_string($_GET['filter_status']) . "'";
}
$product_query .= " ORDER BY " . (isset($_GET['sort_by']) ? $conn->real_escape_string($_GET['sort_by']) : 'id');
$product_result = $conn->query($product_query);

// Fetch users
$user_query = "SELECT id, name, email, role FROM users";
if (isset($_GET['search_user'])) {
    $search_user = $conn->real_escape_string($_GET['search_user']);
    $user_query .= " WHERE name LIKE '%$search_user%' OR email LIKE '%$search_user%'";
}
$user_query .= " ORDER BY name";
$user_result = $conn->query($user_query);
?>

<main>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Dashboard</h2>

        <!-- Products Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Manage Products</h3>
                <form method="GET" class="mb-3">
                    <select name="filter_status" class="form-select">
                        <option value="">All</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                    </select>
                    <select name="sort_by" class="form-select">
                        <option value="id">ID</option>
                        <option value="name">Name</option>
                        <option value="price">Price</option>
                        <option value="weight">Weight</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
                <ul class="list-group">
                    <?php while ($product = $product_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <strong>Name:</strong> <?= htmlspecialchars($product['name']) ?><br>
                            <strong>Price:</strong> R<?= number_format($product['price'], 2) ?><br>
                            <strong>Weight:</strong> <?= htmlspecialchars($product['weight']) ?> kg<br>
                            <strong>Status:</strong> <?= htmlspecialchars($product['status']) ?><br>
                            <?php if ($product['status'] === 'pending'): ?>
                                <form method="POST" action="approve_product.php" class="mt-2">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <!-- Users Section -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Manage Users</h3>
                <form method="GET" class="mb-3 d-flex">
                    <input type="text" name="search_user" class="form-control me-2" placeholder="Search users by name or email">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <ul class="list-group">
                    <?php while ($user = $user_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?><br>
                            <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?><br>
                            <strong>Role:</strong> <?= htmlspecialchars($user['role']) ?><br>
                            <form method="POST" action="remove_user.php" class="mt-2">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
