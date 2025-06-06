<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

$total_products = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$approved = mysqli_query($conn, "SELECT COUNT(*) AS approved FROM products WHERE status='approved'");
$pending = mysqli_query($conn, "SELECT COUNT(*) AS pending FROM products WHERE status='pending'");
$rejected = mysqli_query($conn, "SELECT COUNT(*) AS rejected FROM products WHERE status='rejected'");

$total = mysqli_fetch_assoc($total_products)['total'];
$approved = mysqli_fetch_assoc($approved)['approved'];
$pending = mysqli_fetch_assoc($pending)['pending'];
$rejected = mysqli_fetch_assoc($rejected)['rejected'];
?>

<div class="container mt-5">
    <h2>Platform Report</h2>
    <ul class="list-group">
        <li class="list-group-item">Total Products: <?= $total ?></li>
        <li class="list-group-item text-success">Approved: <?= $approved ?></li>
        <li class="list-group-item text-warning">Pending: <?= $pending ?></li>
        <li class="list-group-item text-danger">Rejected: <?= $rejected ?></li>
    </ul>
</div>

<?php include '../includes/footer.php'; ?>
