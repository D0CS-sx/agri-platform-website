<?php
session_start();
include 'includes/db.php';

$registrationMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['user_role'] = $role;
        $_SESSION['email']     = $email;
        
        // Set a session variable for successful registration
        $_SESSION['registration_success'] = "Your account has been successfully registered. Please log in.";
        
        header("Location: login.php");
        exit;
    } else {
        $registrationMessage = "❌ Registration failed: " . htmlspecialchars($stmt->error);
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Create Your Account</h3>

                    <?php if (!empty($registrationMessage)): ?>
                        <div class="alert alert-danger"><?= $registrationMessage ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. john@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Choose a strong password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Register As</label>
                            <select name="role" class="form-select" required>
                                <option value="buyer">Buyer</option>
                                <option value="seller">Seller</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>

                    <p class="already-account">Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
