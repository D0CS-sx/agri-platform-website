<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_price = floatval($_POST['total_price']);
    $delivery_method = $_POST['delivery_method'] ?? 'pickup';

    // Fetch total weight of items in the cart
    $email = $_SESSION['email'];
    $user_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $user_stmt->bind_param("s", $email);
    $user_stmt->execute();
    $user_stmt->bind_result($user_id);
    $user_stmt->fetch();
    $user_stmt->close();

    $weight_query = "SELECT SUM(p.weight * c.quantity) AS total_weight 
                     FROM cart c 
                     JOIN products p ON c.product_id = p.id 
                     WHERE c.user_id = ?";
    $weight_stmt = $conn->prepare($weight_query);
    $weight_stmt->bind_param("i", $user_id);
    $weight_stmt->execute();
    $weight_stmt->bind_result($total_weight);
    $weight_stmt->fetch();
    $weight_stmt->close();

    // Delivery cost calculation
    $delivery_cost = 0;
    if ($delivery_method === 'delivery') {
        $delivery_cost = $total_weight * 5; // Example: R5 per kg
        $total_price += $delivery_cost;
    }
}
?>

<main>
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card payment-summary">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Payment Summary</h3>
                <p><strong>Total Price:</strong> R<?= number_format($total_price, 2) ?></p>
                <p><strong>Delivery Method:</strong> <?= htmlspecialchars($delivery_method) ?></p>
                <?php if ($delivery_method === 'delivery'): ?>
                    <p><strong>Total Weight:</strong> <?= number_format($total_weight, 2) ?> kg</p>
                    <p><strong>Delivery Cost:</strong> R<?= number_format($delivery_cost, 2) ?></p>
                <?php endif; ?>
                <form method="POST" action="confirm_payment.php">
                    <input type="hidden" name="total_price" value="<?= $total_price ?>">
                    <input type="hidden" name="delivery_method" value="<?= htmlspecialchars($delivery_method) ?>">
                    <button type="submit" class="btn btn-success w-100 mt-3">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>