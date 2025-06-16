<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /agri-platform/admin/admin_login.php");
    exit;
}

include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user orders
$order_stmt = $conn->prepare("SELECT id, product_name, quantity, total_price, status FROM orders WHERE user_id = ?");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

// Fetch user reviews
$review_stmt = $conn->prepare("SELECT product_name, rating, comment FROM reviews WHERE user_id = ?");
$review_stmt->bind_param("i", $user_id);
$review_stmt->execute();
$review_result = $review_stmt->get_result();
?>

<main>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Dashboard</h2>

        <!-- Orders Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Your Orders</h3>
                <?php if ($order_result->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($order = $order_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?><br>
                                <strong>Quantity:</strong> <?= $order['quantity'] ?><br>
                                <strong>Total Price:</strong> R<?= number_format($order['total_price'], 2) ?><br>
                                <strong>Status:</strong> <?= htmlspecialchars($order['status']) ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Button to view reports -->
        <div class="text-center mb-4">
             <a href="/admin/reports.php" class="btn btn-primary">View Reports</a>
        </div>

        <!-- Reviews Section -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Your Reviews</h3>
                <?php if ($review_result->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($review = $review_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong>Product:</strong> <?= htmlspecialchars($review['product_name']) ?><br>
                                <strong>Rating:</strong> <?= $review['rating'] ?>/5<br>
                                <strong>Comment:</strong> <?= htmlspecialchars($review['comment']) ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No reviews found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
