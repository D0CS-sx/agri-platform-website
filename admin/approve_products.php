<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Approve action
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE products SET status='approved' WHERE id=$id");
}

// Reject action
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    mysqli_query($conn, "UPDATE products SET status='rejected' WHERE id=$id");
}

$result = mysqli_query($conn, "SELECT * FROM products WHERE status='pending'");
?>

<div class="container mt-5">
    <h2>Approve New Products</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Seller</th><th>Price</th><th>Category</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <?php
                if (isset($row['seller_email'])) {
                    $seller_email = $row['seller_email'];
                } else {
                    $seller_email = 'N/A'; // Default value if seller_email is not set
                }
                ?>
                <td><?= htmlspecialchars($seller_email) ?></td>
                <td>R<?= number_format($row['price'], 2) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>
                    <a href="?approve=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                    <a href="?reject=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
