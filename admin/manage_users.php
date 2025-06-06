<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$users = mysqli_query($conn, "SELECT id, name, email, role FROM users");
?>

<div class="container mt-5">
  <h2>Manage Users</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = mysqli_fetch_assoc($users)): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= ucfirst($user['role']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
