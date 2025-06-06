<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'seller') {
    header("Location: ../login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$seller_email = $_SESSION['email'];
$seller_query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$seller_email'");
$seller_data = mysqli_fetch_assoc($seller_query);
$seller_id = $seller_data['id'] ?? 0;

// Join orders with products to get buyer and product info
$query = "
    SELECT o.id AS order_id, o.quantity, o.total_price, o.status, o.order_date,
           p.name AS product_name, u.email AS buyer_email
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users u ON o.buyer_id = u.id
    WHERE p.seller_id = $seller_id
    ORDER BY o.order_date DESC
";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <h2>Your Orders</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>Order #</th>
                <th>Product</th>
                <th>Buyer</th>
                <th>Quantity</th>
                <th>Total (ZAR)</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?= $order['order_id'] ?></td>
                <td><?= htmlspecialchars($order['product_name']) ?></td>
                <td><?= htmlspecialchars($order['buyer_email']) ?></td>
                <td><?= $order['quantity'] ?></td>
                <td>R<?= number_format($order['total_price'], 2) ?></td>
                <td><?= ucfirst($order['status']) ?></td>
                <td><?= date("d M Y", strtotime($order['order_date'])) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
