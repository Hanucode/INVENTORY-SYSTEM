<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: view_asset.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// --- Update Logic ---
if (isset($_POST['update_asset'])) {
    $assetid    = mysqli_real_escape_string($conn, $_POST['assetid']);
    $cat        = mysqli_real_escape_string($conn, $_POST['categories']);
    $pod        = mysqli_real_escape_string($conn, $_POST['pod']);
    $serial     = mysqli_real_escape_string($conn, $_POST['serial_no']);
    $model      = mysqli_real_escape_string($conn, $_POST['model_no']);
    
    // New Fields
    $brand      = mysqli_real_escape_string($conn, $_POST['brand']);
    $condition  = mysqli_real_escape_string($conn, $_POST['asset_condition']);
    $warranty   = mysqli_real_escape_string($conn, $_POST['warranty']);

    // Technical specs (using null coalescence to handle disabled/empty inputs)
    $processor  = isset($_POST['processor']) ? mysqli_real_escape_string($conn, $_POST['processor']) : '';
    $ram        = isset($_POST['ram']) ? mysqli_real_escape_string($conn, $_POST['ram']) : '';
    $hdd        = isset($_POST['hdd_sdd']) ? mysqli_real_escape_string($conn, $_POST['hdd_sdd']) : '';
    $screen     = isset($_POST['screen_size']) ? mysqli_real_escape_string($conn, $_POST['screen_size']) : '';

    // Check if the new Asset ID already exists for a DIFFERENT record
    $check_duplicate = mysqli_query($conn, "SELECT id FROM add_assets WHERE assetid='$assetid' AND id != '$id'");
    if(mysqli_num_rows($check_duplicate) > 0) {
        $error = "Error: Asset ID '$assetid' is already assigned to another item!";
    } else {
        $update_query = "UPDATE add_assets SET 
                         assetid='$assetid', categories='$cat', pod='$pod', 
                         processor='$processor', ram='$ram', hdd_sdd='$hdd', 
                         serial_no='$serial', model_no='$model', screen_size='$screen',
                         brand='$brand', asset_condition='$condition', warranty='$warranty'
                         WHERE id='$id'";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['msg'] = "Asset Updated Successfully!";
            $_SESSION['msg_type'] = "success";
            header("Location: view_asset.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch Existing Data
$res = mysqli_query($conn, "SELECT * FROM add_assets WHERE id = '$id'");
$asset = mysqli_fetch_assoc($res);

if (!$asset) {
    header("Location: view_asset.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Asset - Inventory System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include 'dash.php'; ?>

<div class="container-fluid p-4">

    <?php if(isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> EDIT ASSET</h6>
        </div>
        <div class="card-body p-4">
            <form action="edit_asset.php?id=<?php echo $id; ?>" method="POST">
                <div class="row g-3">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Asset ID</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag-fill"></i></span>
                            <input type="text" name="assetid" class="form-control" value="<?php echo $asset['assetid']; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Category</label>
                        <select name="categories" id="categorySelect" class="form-select" required>
                            <?php
                            $cat_res = mysqli_query($conn, "SELECT * FROM categories");
                            while($cat = mysqli_fetch_assoc($cat_res)) {
                                $selected = ($cat['name'] == $asset['categories']) ? 'selected' : '';
                                echo "<option value='".strtolower($cat['name'])."' $selected>".$cat['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Purchase Date</label>
                        <input type="date" name="pod" class="form-control" value="<?php echo $asset['pod']; ?>" required>
                    </div>

                    <hr>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Brand</label>
                        <select name="brand" class="form-select" required>
                            <?php
                            $brand_res = mysqli_query($conn, "SELECT * FROM brands");
                            while($b = mysqli_fetch_assoc($brand_res)) {
                                $b_selected = ($b['brand_name'] == $asset['brand']) ? 'selected' : '';
                                echo "<option value='".$b['brand_name']."' $b_selected>".$b['brand_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Condition</label>
                        <select name="asset_condition" class="form-select">
                            <option value="New" <?php if($asset['asset_condition'] == 'New') echo 'selected'; ?>>New</option>
                            <option value="Used" <?php if($asset['asset_condition'] == 'Used') echo 'selected'; ?>>Used</option>
                            <option value="Refurbished" <?php if($asset['asset_condition'] == 'Refurbished') echo 'selected'; ?>>Refurbished</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Warranty</label>
                        <input type="text" name="warranty" class="form-control" value="<?php echo $asset['warranty']; ?>">
                    </div>

                    <hr>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Processor</label>
                        <input type="text" name="processor" id="processor" class="form-control" value="<?php echo $asset['processor']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">RAM</label>
                        <input type="text" name="ram" id="ram" class="form-control" value="<?php echo $asset['ram']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">HDD / SSD</label>
                        <input type="text" name="hdd_sdd" id="hdd_sdd" class="form-control" value="<?php echo $asset['hdd_sdd']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Screen Size</label>
                        <input type="text" name="screen_size" id="screen_size" class="form-control" value="<?php echo $asset['screen_size']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Model Number</label>
                        <input type="text" name="model_no" class="form-control" value="<?php echo $asset['model_no']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Serial Number</label>
                        <input type="text" name="serial_no" class="form-control" value="<?php echo $asset['serial_no']; ?>" required>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" name="update_asset" class="btn btn-danger px-4 py-2 shadow-sm" style="background-color: #ef5350; border:none;">
                        Update Asset
                    </button>
                    <a href="view_asset.php" class="btn btn-secondary px-4 py-2 shadow-sm ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function toggleFields() {
        var category = $('#categorySelect').val();
        
        // Reset
        $('#processor, #ram, #hdd_sdd, #screen_size').prop('disabled', false).css('background-color', '#fff');

        if (category === 'printer') {
            $('#processor, #ram, #hdd_sdd, #screen_size').prop('disabled', true).val('').css('background-color', '#e9ecef');
        } 
        else if (category === 'tablet' || category === 'mobile') {
            $('#hdd_sdd').prop('disabled', true).val('').css('background-color', '#e9ecef');
        }
    }

    // Run on change
    $('#categorySelect').on('change', toggleFields);
    
    // Run on page load to handle initial data
    toggleFields();
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>