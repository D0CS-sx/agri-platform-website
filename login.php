<?php
session_start();
include 'includes/db.php';

$login_error = "";

// Check for registration success message
$registration_success = "";
if (isset($_SESSION['registration_success'])) {
    $registration_success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']); // Clear the message after displaying it
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role; // Ensure this is set correctly
            $_SESSION['email'] = $email;

            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: /agri-platform/admin/dashboard.php"); // Correct redirection
                    break;
                case 'seller':
                    header("Location: /agri-platform/seller/dashboard.php");
                    break;
                case 'buyer':
                    header("Location: /agri-platform/buyer/index.php");
                    break;
                default:
                    header("Location: index.php");
            }
            exit;
        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "No account found with that email.";
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Login to Agri Platform</h3>

                <?php if (!empty($registration_success)): ?>
                    <div class="alert alert-success"><?php echo $registration_success; ?></div>
                <?php endif; ?>

                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" required placeholder="you@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required placeholder="Enter your password">
                    </div>

                    <button type="submit" class="btn btn-success w-100">Login</button>
                </form>

                <div class="text-center mt-3">
                    <p class="already-account">Don't have an account? <a href="register.php">Register here</a>.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
