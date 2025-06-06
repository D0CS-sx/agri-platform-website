<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'seller') {
    header("Location: ../login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$seller_email = $_SESSION['email'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $price    = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image    = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image_name;

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $image_name;
            } else {
                $message = "<div class='alert alert-danger'>❌ Error uploading image.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>⚠️ Invalid image format. Only JPG, PNG, and GIF are allowed.</div>";
        }
    }

    // Get seller ID
    $seller_query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$seller_email' AND role = 'seller'");
    $seller_data  = mysqli_fetch_assoc($seller_query);

    if ($seller_data) {
        $seller_id = $seller_data['id'];

        $insert = "INSERT INTO products (name, description, price, category, image, seller_id, approved, created_at)
                   VALUES ('$name', '$desc', '$price', '$category', '$image', '$seller_id', 0, NOW())";

        if (mysqli_query($conn, $insert)) {
            $message = "<div class='alert alert-success'>✅ Product submitted for admin approval.</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error adding product: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠️ Invalid seller account.</div>";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Add New Product</h3>

                    <?= $message ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Organic Tomatoes" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" placeholder="Describe your product" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price (ZAR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 25.50" required>
                        </div>

                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" name="weight" id="weight" class="form-control" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Vegetables" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Submit Product</button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        <a href="dashboard.php">⬅ Back to Dashboard</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
