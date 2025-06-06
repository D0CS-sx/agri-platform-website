<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);
$stmt->fetch();
$stmt->close();
?>

<main>
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card profile-card">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Your Profile</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>