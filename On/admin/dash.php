<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css"> </head>
<body>

<div class="wrapper d-flex">
    <nav id="sidebar">
        <div class="brand-logo text-white">INVENTORY SYSTEM</div>
        <ul class="list-unstyled components">
            <li><a href="dashbord.php"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li><a href="vendorcreation.php"><i class="bi bi-person-fill"></i> Vendor Creation</a></li>
            <li><a href="categorie.php"><i class="bi bi-grid-fill"></i> Categories</a></li>
            <li><a href="add_brand.php"><i class="bi bi-grid-fill"></i> Add Brand</a></li>
            
            <li>
                <a href="javascript:void(0)" class="dropdown-btn" onclick="toggleProductMenu()">
                    <i class="bi bi-grid-3x3-gap-fill"></i> ASSETS
                    <i class="bi bi-chevron-down float-end small mt-1"></i>
                </a>
                <div class="dropdown-container" id="productDropdown">
                    <a href="add_Assets.php"><i class="bi bi-dot"></i> Add Assets</a>
                    <a href="view_asset.php"><i class="bi bi-dot"></i> View Assets</a>
                </div>
            </li>
            
            <li><a href="add_department.php"><i class="bi bi-credit-card-fill"></i> Department</a></li>
            <li><a href="issue_asset.php"><i class="bi bi-person-fill"></i>Issue Asset</a></li>
            <li><a href="user_asset.php"><i class="bi bi-person-fill"></i>User Asset</a></li>
            <li><a href="savedata.php"><i class="bi bi-file-earmark-bar-graph"></i> Save Data</a></li>
                        <!-- <li><a href="requests.php"><i class="bi bi-file-earmark-bar-graph"></i> Requests</a></li> -->
                         <?php
// Pending requests ka count nikalne ke liye (Sirf Super Admin ke liye)
$super_admin_email = "admin@gmail.com"; 
$pending_count = 0;
if ($_SESSION['user_email'] == $super_admin_email) {
    $count_res = mysqli_query($conn, "SELECT id FROM pending_requests WHERE status = 'PENDING'");
    $pending_count = mysqli_num_rows($count_res);
}
?>

<li class="nav-item">
    <a class="nav-link d-flex justify-content-between align-items-center" href="requests.php">
        <span><i class="bi bi-shield-check me-2"></i> Approval Requests</span>
        <?php if($pending_count > 0): ?>
            <span class="badge bg-danger rounded-pill" style="font-size: 0.7rem;"><?php echo $pending_count; ?></span>
        <?php endif; ?>
    </a>
</li>
        </ul>
    </nav>

    <div id="content">
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-dark border-0" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex align-items-center">
                <span class="text-muted me-3 d-none d-md-block">
                    <?php echo date('F d, Y, g:i a'); ?>
                </span>
                
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center border-0" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-4 me-2"></i> 
                        <?php echo $_SESSION['user_name']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>