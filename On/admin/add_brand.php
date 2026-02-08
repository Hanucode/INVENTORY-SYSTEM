<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Brand Add Logic ---
if (isset($_POST['add_brand'])) {
    $brand_name = mysqli_real_escape_string($conn, $_POST['brand_name']);
    if (!empty($brand_name)) {
        $query = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['msg'] = "Brand Added Successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['msg'] = "Error: " . mysqli_error($conn);
            $_SESSION['msg_type'] = "danger";
        }
    }
    header("Location: add_brand.php");
    exit();
}

// --- Brand Delete Logic ---
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM brands WHERE id=$id");
    $_SESSION['msg'] = "Brand Deleted!";
    $_SESSION['msg_type'] = "warning";
    header("Location: add_brand.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata'); 
?>

<title>Manage Brands - Inventory System</title>
<?php include 'dash.php';?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase"><i class="bi bi-patch-check me-2"></i> Add New Brand</h6>
                </div>
                <div class="card-body">
                    <form action="add_brand.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Brand Name</label>
                            <input type="text" name="brand_name" class="form-control" placeholder="e.g. HP, Dell, Apple, Samsung" required>
                        </div>
                        <button type="submit" name="add_brand" class="btn btn-primary w-100 shadow-sm" style="background-color: #0275d8; border:none;">
                            Save Brand
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-uppercase"><i class="bi bi-list-stars me-2"></i> Registered Brands</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">#</th>
                                    <th>Brand Name</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
                                $count = 1;
                                if(mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?php echo $count++; ?></td>
                                    <td><?php echo $row['brand_name']; ?></td>
                                    <td class="text-center">
                                        <a href="add_brand.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-outline-danger btn-sm border-0" 
                                           onclick="return confirm('Delete this brand?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='3' class='text-center py-3'>No brands found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>