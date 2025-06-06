<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

$buyer_email = $_SESSION['buyer_email'] ?? $_SESSION['email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($buyer_email) {
        mysqli_query($conn, "INSERT INTO orders (buyer_email, product_id, quantity) 
                             VALUES ('$buyer_email', $product_id, $quantity)");
        $message = "Order placed successfully.";
    } else {
        header("Location: ../login.php");
        exit;
    }
}

$result = mysqli_query($conn, "
    SELECT o.*, p.name, p.price 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.buyer_email = '$buyer_email'
");
?>

<div class="container mt-5">
    <h2>Your Orders</h2>
    <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th><th>Qty</th><th>Total</th><th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $order['name'] ?></td>
                <td><?= $order['quantity'] ?></td>
                <td>R<?= number_format($order['quantity'] * $order['price'], 2) ?></td>
                <td><?= $order['order_date'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
