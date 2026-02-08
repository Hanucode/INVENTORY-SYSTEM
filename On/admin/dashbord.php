<?php
// Step 1: Database connection include karein
include '../datawase/config.php';

// Step 2: Session start aur Security Check
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Timezone set karein
date_default_timezone_set('Asia/Kolkata'); 

// Step 3: Database se Stats fetch karna
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM user"));
$total_categories = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM categories"));
$total_brands = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM brands"));
$total_departments = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM departments"));
$total_assets = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM add_assets"));
$total_vendors = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM vendor_creation"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management System - Dashboard</title>
    <style>
        /* Card ko link ki tarah dikhane ke liye style */
        .stat-link {
            text-decoration: none !important;
            display: block;
        }
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            filter: brightness(1.1);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-data h3 {
            margin: 0;
            font-weight: bold;
            font-size: 2rem;
        }
        .stat-data p {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<?php include 'dash.php';?>

<div class="container-fluid p-4">
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="background-color: #dff0d8; color: #3c763d;">
        Welcome to Inventory Management System, <strong><?php echo $_SESSION['user_name']; ?>!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4 col-lg-3">
            <a href="userdata.php" class="stat-link">
                <div class="stat-card" style="background-color: #a27ea8;">
                    <div class="stat-icon"><i class="bi bi-person-fill"></i></div>
                    <div class="stat-data"><h3><?php echo $total_users; ?></h3><p class="mb-0">Users</p></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="categorie.php" class="stat-link">
                <div class="stat-card" style="background-color: #ff8a65;">
                    <div class="stat-icon"><i class="bi bi-grid-fill"></i></div>
                    <div class="stat-data"><h3><?php echo $total_categories; ?></h3><p class="mb-0">Categories</p></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="add_brand.php" class="stat-link">
                <div class="stat-card" style="background-color: #7986cb;">
                    <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="stat-data"><h3><?php echo $total_brands; ?></h3><p class="mb-0">Brands</p></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="add_department.php" class="stat-link">
                <div class="stat-card" style="background-color: #9ccc65;">
                    <div class="stat-icon"><i class="bi bi-building-fill"></i></div>
                    <div class="stat-data"><h3><?php echo $total_departments; ?></h3><p class="mb-0">Department</p></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="view_asset.php" class="stat-link">
                <div class="stat-card" style="background-color: #4db6ac;">
                    <div class="stat-icon"><i class="bi bi-laptop-fill"></i></div>
                    <div class="stat-data"><h3><?php echo $total_assets; ?></h3><p class="mb-0">Assets</p></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="viewallvendor.php" class="stat-link">
                <div class="stat-card" style="background-color: #f06292;">
                    <div class="stat-icon"><i class="bi bi-shop-window"></i></div>
                    <div class="stat-data"><h3><?php echo $total_vendors; ?></h3><p class="mb-0">Vendor</p></div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php';?>
</body>
</html>