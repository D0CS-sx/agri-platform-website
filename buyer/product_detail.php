<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Get product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    die("Invalid product ID.");
}

// Fetch product details
$product_sql = "SELECT p.*, u.name AS seller_name, u.email AS seller_email 
                FROM products p 
                JOIN users u ON p.seller_id = u.id 
                WHERE p.id = ? AND p.status = 'approved'";
$product_stmt = $conn->prepare($product_sql);

if (!$product_stmt) {
    die("Query Error: " . $conn->error);
}

$product_stmt->bind_param('i', $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows === 0) {
    die("Product not found.");
}

$product = $product_result->fetch_assoc();
?>

<div class="container mt-5">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($product['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php else: ?>
                <img src="../assets/img/default.jpg" class="img-fluid" alt="No Image">
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h4>Description</h4>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <h4>Details</h4>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
            <p><strong>Price:</strong> R<?= number_format($product['price'], 2) ?></p>
            <p><strong>Seller:</strong> <?= htmlspecialchars($product['seller_name']) ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($product['seller_email']) ?></p>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button class="btn btn-success">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<?php
$product_stmt->close();
include '../includes/footer.php';
?>