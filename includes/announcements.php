<?php
require_once 'db.php';

// Fetch latest announcements
$stmt = $pdo->prepare("SELECT title, content, created_at FROM announcements ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="mb-4">Latest Announcements</h2>
    <div class="alert alert-info">
        <h5>Welcome to Agri Platform!</h5>
        <p>We are excited to launch our new platform connecting farmers and buyers directly.</p>
        <small class="text-muted">June 1, 2025</small>
    </div>
    <div class="alert alert-info">
        <h5>Upcoming Event: Farmers Market</h5>
        <p>Join us at the annual Farmers Market on June 15th. Fresh produce and great deals await!</p>
        <small class="text-muted">June 5, 2025</small>
    </div>
    <div class="alert alert-info">
        <h5>New Feature: Product Reviews</h5>
        <p>You can now leave reviews for products you purchase. Share your feedback with the community!</p>
        <small class="text-muted">June 3, 2025</small>
    </div>
    <div class="alert alert-info">
        <h5>Discount Alert!</h5>
        <p>Enjoy up to 20% off on selected products this week. Don't miss out!</p>
        <small class="text-muted">June 4, 2025</small>
    </div>
    <div class="alert alert-info">
        <h5>Platform Maintenance</h5>
        <p>Our platform will undergo maintenance on June 10th from 12 AM to 4 AM. We apologize for any inconvenience.</p>
        <small class="text-muted">June 2, 2025</small>
    </div>
</div>
