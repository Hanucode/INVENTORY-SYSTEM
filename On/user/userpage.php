<?php
// Database connection file include karein
include '../datawase/config.php';

// Step 1: Session start karna
session_start();

// Step 2: Security Check
// Agar login nahi hai YA user type 'user' nahi hai, toh login page par bhej do
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .user-nav {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .welcome-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .action-card {
            border: none;
            border-radius: 10px;
            transition: 0.3s;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .btn-logout {
            background-color: #ef5350;
            color: white;
            border-radius: 20px;
            padding: 8px 25px;
        }
        .btn-logout:hover {
            background-color: #d32f2f;
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar user-nav py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="#">
                <i class="bi bi-box-seam text-danger me-2"></i> Inventory User
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3 d-none d-md-inline text-muted">Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                <a href="../logout.php" class="btn btn-logout btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card welcome-card p-5 text-center">
                    <h1 class="display-5 fw-bold text-dark">Hello, <?php echo $_SESSION['user_name']; ?>!</h1>
                    <!-- <p class="lead text-muted"></p> -->
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4 text-center">
                <div class="card action-card p-4 bg-white">
                    <i class="bi bi-card-list fs-1 text-primary mb-3"></i>
                    <h5>My View Items</h5>
                    <p class="text-muted small">Check available products in stock.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card action-card p-4 bg-white">
                    <i class="bi bi-clock-history fs-1 text-success mb-3"></i>
                    <h5>My Activity</h5>
                    <p class="text-muted small">View your login and transaction history.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card action-card p-4 bg-white">
                    <i class="bi bi-person-gear fs-1 text-warning mb-3"></i>
                    <h5>Account Settings</h5>
                    <p class="text-muted small">Update your profile and password.</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h4 class="mb-3">Recent Notifications</h4>
            <div class="alert alert-info border-0 shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> System update scheduled for tomorrow.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
