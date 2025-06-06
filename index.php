<?php
// Connect to the database
include 'includes/db.php';

// Include HTML <head>, navbar, and opening <body> tag
include 'includes/header.php';
?>

<!-- Dashboard Layout -->
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Left Sidebar (Announcements) -->
        <div class="col-md-3">
            <div class="panel p-3" style="height: 100%; background-color: #1b4332; color: #f8f9fa; border-radius: 10px;">
                <h4 class="text-center">Announcements</h4>
                <p>Welcome to Agri Trading Platform! Check out our latest updates and offers.</p>
                <p>Don't miss our upcoming events and promotions!</p>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="col-md-6">
            <div class="panel p-3 mb-4" style="background-color: #40916c; color: #f8f9fa; border-radius: 10px;">
                <h2 class="text-center">Welcome to Agri Trading Platform</h2>
                <p class="text-center">
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-success">Register</a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="/agri-platform/admin/dashboard.php" class="btn btn-warning">Admin</a>
                    <?php endif; ?>
                </p>
            </div>

            <div class="panel p-3 mb-4" style="background-color: #74c69d; color: #f8f9fa; border-radius: 10px;">
                <h3 class="text-center">About Us</h3>
                <p>
                    Agri Trading Platform is a mission-driven online marketplace that bridges the gap between small-scale farmers and urban buyers. We are committed to transforming the agricultural value chain by removing middlemen, reducing costs, and ensuring farmers get fair value for their produce.
                </p>
                <p>
                    Join our growing community and be part of a smarter, fairer food economy.
                </p>
            </div>

            <div class="panel p-3" style="background-color: #74c69d; color: #f8f9fa; border-radius: 10px;">
                <h3 class="text-center">Reviews and Ratings</h3>
                <p>
                    “This platform has revolutionized the way I buy fresh produce. I now get farm-fresh vegetables delivered straight to my home, with full confidence in quality and safety!” — Jane Doe
                </p>
                <p>
                    “As a farmer, I love the direct connection with buyers. I receive fair prices and instant feedback. Highly recommend for all small-scale producers.” — John Smith
                </p>
                <p>
                    Average Rating: ⭐⭐⭐⭐⭐ 4.8/5 (Based on 300+ verified user reviews)
                </p>
            </div>
        </div>

        <!-- Right Sidebar (Local Farmers) -->
        <div class="col-md-3">
            <div class="panel p-3" style="height: 100%; background-color: #1b4332; color: #f8f9fa; border-radius: 10px;">
                <h4 class="text-center">Local Farmers</h4>
                <ul class="list-group" style="background-color: transparent; border: none;">
                    <?php
                    // Fetch farmers from the database
                    $query = "SELECT name, location FROM farmers WHERE location = 'Your Location' LIMIT 5";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($farmer = mysqli_fetch_assoc($result)) {
                            echo "<li class='list-group-item' style='background-color: transparent; border: none; color: #f8f9fa;'>{$farmer['name']} - {$farmer['location']}</li>";
                        }
                    } else {
                        echo "<li class='list-group-item' style='background-color: transparent; border: none; color: #f8f9fa;'>No local farmers found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer with closing </body> and </html> tags
include 'includes/footer.php';
?>
