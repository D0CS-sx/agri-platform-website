<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'seller') {
    header("Location: ../login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$seller_email = $_SESSION['email'];

// Fetch seller ID
$seller_query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$seller_email'");
$seller_data = mysqli_fetch_assoc($seller_query);
$seller_id = $seller_data['id'] ?? 0;

// Fetch products
$result = mysqli_query($conn, "SELECT * FROM products WHERE seller_id = $seller_id");
?>

<div class="container mt-5">
    <h2>Your Products</h2>
    <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
    <table class="table table-striped table-bordered">
        <thead class="table-success">
            <tr>
                <th>Name</th><th>Category</th><th>Price (ZAR)</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>R<?= number_format($row['price'], 2) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
