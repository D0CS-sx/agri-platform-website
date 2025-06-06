<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'buyer') {
    header("Location: ../login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();
?>

<div class="container mt-5">
  <h2>My Profile</h2>
  <div class="card">
    <div class="card-body">
      <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
