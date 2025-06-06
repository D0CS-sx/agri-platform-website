<?php
session_start();
include '../includes/db.php';

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = 'admin';
            $_SESSION['email'] = $email;

            // Debugging: Check session variables
            error_log("Admin ID: " . $_SESSION['user_id']);
            error_log("Admin Role: " . $_SESSION['user_role']);

            header("Location: /agri-platform/admin/dashboard.php");
            exit;
        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "No admin account found with that email.";
    }
    $stmt->close();
}
?>

<?php include '../includes/header.php'; ?>

<main>
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Admin Login</h3>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" required placeholder="admin@example.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-warning w-100">Login as Admin</button>
            </form>
        </div>
    </div>
</div>
</main>

<?php include '../includes/footer.php'; ?>