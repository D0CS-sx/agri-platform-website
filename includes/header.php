<?php

// Start the session to access $_SESSION variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch notifications
$notification_stmt = $conn->prepare("SELECT message FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$notification_stmt->bind_param("i", $_SESSION['user_id']);
$notification_stmt->execute();
$notifications = $notification_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <title>Agri Trading Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/agri-platform/assets/css/styles.css">


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-GJG19DBP1Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-GJG19DBP1Z');
</script>

</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a href="/agri-platform" class="d-flex align-items-center">
            <img src="/agri-platform/assets/images/Logo.jpg" alt="Agri Platform Logo" loading="lazy"> <!-- Logo with lazy loading -->
            <span class="navbar-brand fs-1 fw-bold">The FarmLink Market</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Other navigation links -->
                <li class="nav-item">
                    <a class="nav-link" href="/agri-platform/profile.php">
                        <i class="bi bi-person-circle profile-icon"></i> <!-- Profile Icon -->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/agri-platform/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/agri-platform/register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="/agri-platform/admin/admin_login.php">Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/agri-platform/buyer/cart.php">
                        <i class="bi bi-cart-fill"></i> <!-- Cart Icon -->
                        <span class="badge bg-danger"><?= $_SESSION['cart_count'] ?? 0 ?></span> <!-- Cart Count -->
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/agri-platform/logout.php">Logout</a> <!-- Logout Button -->
                    </li>
                <?php endif; ?>
            </ul>
            <form class="d-flex ms-3" action="/agri-platform/buyer/products.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </div>
        <!-- Dark Mode Toggle Button -->
        <button aria-label="Toggle Dark Mode" id="darkModeToggle" class="btn btn-outline-primary">Dark Mode</button>
    </div>
  </nav>
  <main class="container-fluid mt-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9">
        </div>
    </div>
  </main>
  <script src="/agri-platform/assets/js/scripts.js"></script>
  <script>
    const searchInput = document.querySelector('input[name="query"]');
    searchInput.addEventListener('input', async () => {
        const response = await fetch(`/api/search_suggestions?query=${searchInput.value}`);
        const suggestions = await response.json();
        // Display suggestions dynamically
    });
  </script>
</body>
</html>
