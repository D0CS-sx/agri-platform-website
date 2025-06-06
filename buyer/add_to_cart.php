<?php
session_start();
include '../includes/db.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];

    $product_check_stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $product_check_stmt->bind_param("i", $product_id);
    $product_check_stmt->execute();
    $product_check_stmt->store_result();
    if ($product_check_stmt->num_rows === 0) {
        die("Invalid product ID.");
    }
    $product_check_stmt->close();

    // Check if product is already in cart
    $check_stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->bind_result($cart_id, $quantity);
        $check_stmt->fetch();
        $quantity++;
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $quantity, $cart_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close();

    // Update cart count in session
    $count_stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $count_stmt->bind_param("i", $user_id);
    $count_stmt->execute();
    $count_stmt->bind_result($cart_count);
    $count_stmt->fetch();
    $_SESSION['cart_count'] = $cart_count;
    $count_stmt->close();
}

error_log("User ID: $user_id");
error_log("Product ID: $product_id");

header("Location: products.php");
exit;
?>
