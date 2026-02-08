<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Data Save Logic ---
if (isset($_POST['add_asset'])) {
    $assetid    = mysqli_real_escape_string($conn, $_POST['assetid']);
    $cat        = mysqli_real_escape_string($conn, $_POST['categories']);
    $pod        = mysqli_real_escape_string($conn, $_POST['pod']);
    $serial     = mysqli_real_escape_string($conn, $_POST['serial_no']);
    $model      = mysqli_real_escape_string($conn, $_POST['model_no']);
    
    // Technical specs (Might be empty/disabled based on category)
    $processor  = isset($_POST['processor']) ? mysqli_real_escape_string($conn, $_POST['processor']) : '';
    $ram        = isset($_POST['ram']) ? mysqli_real_escape_string($conn, $_POST['ram']) : '';
    $hdd        = isset($_POST['hdd_sdd']) ? mysqli_real_escape_string($conn, $_POST['hdd_sdd']) : '';
    $screen     = isset($_POST['screen_size']) ? mysqli_real_escape_string($conn, $_POST['screen_size']) : '';
    
    // Updated Identification Fields (Brand is now from a dropdown)
    $brand      = mysqli_real_escape_string($conn, $_POST['brand']); 
    $condition  = mysqli_real_escape_string($conn, $_POST['asset_condition']);
    $warranty   = mysqli_real_escape_string($conn, $_POST['warranty']);

    // Updated Query
    $query = "INSERT INTO add_assets (assetid, categories, pod, processor, ram, hdd_sdd, serial_no, model_no, screen_size, brand, asset_condition, warranty, creat_at) 
              VALUES ('$assetid', '$cat', '$pod', '$processor', '$ram', '$hdd', '$serial', '$model', '$screen', '$brand', '$condition', '$warranty', NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['msg'] = "Asset Added Successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Error: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: add_Assets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Assets - Inventory System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-grid-fill me-2"></i> ADD NEW ASSET</h6>
        </div>
        <div class="card-body p-4">
            <form action="add_Assets.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="assetid" class="form-control" placeholder="Asset ID" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select name="categories" id="categorySelect" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php
                            $cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
                            while($cat = mysqli_fetch_assoc($cat_res)) {
                                echo "<option value='".strtolower($cat['name'])."'>".$cat['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Purchase Date</span>
                            <input type="date" name="pod" class="form-control" required>
                        </div>
                    </div>

                    <hr> 
                    
                    <div class="col-md-4">
                        <select name="brand" class="form-select" required>
                            <option value="">Select Brand</option>
                            <?php
                            $brand_res = mysqli_query($conn, "SELECT * FROM brands ORDER BY brand_name ASC");
                            while($brand_row = mysqli_fetch_assoc($brand_res)) {
                                echo "<option value='".$brand_row['brand_name']."'>".$brand_row['brand_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="asset_condition" class="form-select">
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                            <option value="Refurbished">Refurbished</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="warranty" class="form-control" placeholder="Warranty Info">
                    </div>

                    <hr> 
                    
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-cpu"></i></span>
                            <input type="text" name="processor" id="processor" class="form-control" placeholder="Processor">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">RAM</span>
                            <input type="text" name="ram" id="ram" class="form-control" placeholder="RAM size">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-hdd-fill"></i></span>
                            <input type="text" name="hdd_sdd" id="hdd_sdd" class="form-control" placeholder="HDD/SSD Size">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-display"></i></span>
                            <input type="text" name="screen_size" id="screen_size" class="form-control" placeholder="Screen Size">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="model_no" class="form-control" placeholder="Model Number" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="serial_no" class="form-control" placeholder="Serial Number" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="add_asset" class="btn btn-danger px-5 py-2" style="background-color: #ef5350; border:none;">
                        Save Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#categorySelect').on('change', function() {
        var category = $(this).val();

        // Default: Enable everything
        $('#processor, #ram, #hdd_sdd, #screen_size').prop('disabled', false).css('background-color', '#fff');

        if (category === 'printer') {
            // Disable Specs for Printer
            $('#processor, #ram, #hdd_sdd, #screen_size').prop('disabled', true).val('').css('background-color', '#e9ecef');
        } 
        else if (category === 'tablet' || category === 'mobile') {
            // Block Storage/Screen for specific reasons if requested
            $('#hdd_sdd').prop('disabled', true).val('').css('background-color', '#e9ecef');
        }
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>