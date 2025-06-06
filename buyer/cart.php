<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch user ID
$email = $_SESSION['email'];
$user_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_stmt->bind_result($user_id);
$user_stmt->fetch();
$user_stmt->close();

// Fetch cart items
$cart_sql = "SELECT c.quantity, p.name, p.price, p.image 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
?>

<main>
    <div class="container mt-5">
        <h2>Your Cart</h2>
        <?php if ($cart_result->num_rows > 0): ?>
            <div class="row">
                <?php 
                $total_price = 0; 
                while ($row = $cart_result->fetch_assoc()): 
                    $total_price += $row['price'] * $row['quantity'];
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text">Price: R<?= number_format($row['price'], 2) ?></p>
                                <p class="card-text">Quantity: <?= $row['quantity'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="text-end mt-4">
                <h4>Total Price: R<?= number_format($total_price, 2) ?></h4>
                <form action="payment.php" method="POST">
                    <input type="hidden" name="total_price" value="<?= $total_price ?>">
                    <div class="mb-3">
                        <label for="delivery_method" class="form-label">Delivery Method</label>
                        <p class="text-muted">
                            Shipping costs are calculated based on the total weight of the items in your cart. 
                            Delivery costs are R5 per kilogram.
                        </p>
                        <select name="delivery_method" id="delivery_method" class="form-select">
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Proceed with Payment</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-center mt-4">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</main>

<?php
include '../includes/footer.php';
?>