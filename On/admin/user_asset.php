<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Update Logic (Modal Submit) ---
if (isset($_POST['update_asset_inline'])) {
    $id         = mysqli_real_escape_string($conn, $_POST['id']);
    $assetid    = mysqli_real_escape_string($conn, $_POST['assetid']);
    $cat        = mysqli_real_escape_string($conn, $_POST['categories']);
    $brand      = mysqli_real_escape_string($conn, $_POST['brand']);
    $model      = mysqli_real_escape_string($conn, $_POST['model_no']);
    $serial     = mysqli_real_escape_string($conn, $_POST['serial_no']);
    $processor  = mysqli_real_escape_string($conn, $_POST['processor']);
    $ram        = mysqli_real_escape_string($conn, $_POST['ram']);
    $hdd        = mysqli_real_escape_string($conn, $_POST['hdd_sdd']);
    $screen     = mysqli_real_escape_string($conn, $_POST['screen_size']);

    $update_query = "UPDATE add_assets SET 
                     assetid='$assetid', categories='$cat', brand='$brand', 
                     model_no='$model', serial_no='$serial', processor='$processor', 
                     ram='$ram', hdd_sdd='$hdd', screen_size='$screen' 
                     WHERE id='$id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['msg'] = "Asset ID $assetid Updated Successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Error updating record: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: user_asset.php");
    exit();
}

// --- Delete Logic ---
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM add_assets WHERE id = '$id'")) {
        $_SESSION['msg'] = "Asset Deleted Successfully!";
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: user_asset.php");
    exit();
}
?>

<title>Manage Assets - Inventory System</title>
<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-display me-2 text-danger"></i> ALL ASSETS LIST</h5>
            <a href="add_Assets.php" class="btn btn-danger btn-sm px-3 shadow-sm">Add New Asset</a>
            <a href="issue_asset.php" class="btn btn-danger btn-sm px-3 shadow-sm">Add New User Asset</a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle text-center">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Asset ID</th>
                            <th>Brand & Category</th>
                            <th>Model / Serial</th>
                            <th>Technical Specs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM add_assets ORDER BY id DESC");
                        $count = 1;
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $category_clean = strtolower($row['categories']);
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td class="fw-bold text-primary"><?php echo $row['assetid']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo $row['brand']; ?></div>
                                <span class="badge bg-light text-dark border small"><?php echo $row['categories']; ?></span>
                            </td>
                            <td>
                                <div class="small"><?php echo $row['model_no']; ?></div>
                                <div class="text-muted" style="font-size: 11px;"><?php echo $row['serial_no']; ?></div>
                            </td>
                            <td class="small">
                                <?php 
                                if(empty($row['ram']) && empty($row['processor'])) {
                                    echo "<span class='text-muted italic'>Its " . ucfirst($category_clean) . "</span>";
                                } else {
                                    echo "RAM: " . $row['ram'] . "<br>CPU: " . $row['processor'];
                                }
                                ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary btn-sm editBtn" 
                                        data-id="<?php echo $row['id']; ?>"
                                        data-assetid="<?php echo $row['assetid']; ?>"
                                        data-cat="<?php echo $row['categories']; ?>"
                                        data-brand="<?php echo $row['brand']; ?>"
                                        data-model="<?php echo $row['model_no']; ?>"
                                        data-serial="<?php echo $row['serial_no']; ?>"
                                        data-processor="<?php echo $row['processor']; ?>"
                                        data-ram="<?php echo $row['ram']; ?>"
                                        data-hdd="<?php echo $row['hdd_sdd']; ?>"
                                        data-screen="<?php echo $row['screen_size']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="user_asset.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Permanent Delete this Asset?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-4 text-muted'>No assets found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg border-0">
    <form action="user_asset.php" method="POST">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title small text-uppercase fw-bold">Update Asset Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <input type="hidden" name="id" id="edit_id">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Asset ID</label>
                    <input type="text" name="assetid" id="edit_assetid" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Category</label>
                    <input type="text" name="categories" id="edit_cat" class="form-control" readonly style="background-color: #f8f9fa;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Brand</label>
                    <input type="text" name="brand" id="edit_brand" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Model Number</label>
                    <input type="text" name="model_no" id="edit_model" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Serial Number</label>
                    <input type="text" name="serial_no" id="edit_serial" class="form-control">
                </div>
                
                <div class="col-md-12"><hr class="text-muted"></div>
                
                <div class="col-md-3 spec-field">
                    <label class="form-label small fw-bold text-muted">Processor</label>
                    <input type="text" name="processor" id="edit_processor" class="form-control">
                </div>
                <div class="col-md-3 spec-field">
                    <label class="form-label small fw-bold text-muted">RAM</label>
                    <input type="text" name="ram" id="edit_ram" class="form-control">
                </div>
                <div class="col-md-3 spec-field">
                    <label class="form-label small fw-bold text-muted">HDD / SSD</label>
                    <input type="text" name="hdd_sdd" id="edit_hdd" class="form-control">
                </div>
                <div class="col-md-3 spec-field">
                    <label class="form-label small fw-bold text-muted">Screen Size</label>
                    <input type="text" name="screen_size" id="edit_screen" class="form-control">
                </div>
            </div>
          </div>
          <div class="modal-footer bg-light border-0">
            <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="update_asset_inline" class="btn btn-danger btn-sm px-4 shadow-sm">Update Now</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.editBtn').on('click', function() {
        // Fetch values from Data Attributes
        const id = $(this).data('id');
        const assetid = $(this).data('assetid');
        const cat = $(this).data('cat');
        const brand = $(this).data('brand');
        const model = $(this).data('model');
        const serial = $(this).data('serial');
        const proc = $(this).data('processor');
        const ram = $(this).data('ram');
        const hdd = $(this).data('hdd');
        const screen = $(this).data('screen');

        // Populate Modal Fields
        $('#edit_id').val(id);
        $('#edit_assetid').val(assetid);
        $('#edit_cat').val(cat);
        $('#edit_brand').val(brand);
        $('#edit_model').val(model);
        $('#edit_serial').val(serial);
        $('#edit_processor').val(proc);
        $('#edit_ram').val(ram);
        $('#edit_hdd').val(hdd);
        $('#edit_screen').val(screen);

        // Hide/Show Logic for Printer/Mobile
        let categoryLower = cat.toLowerCase();
        if (categoryLower === 'printer') {
            $('.spec-field').hide();
        } else if (categoryLower === 'tablet' || categoryLower === 'mobile') {
            $('.spec-field').show();
            $('#edit_hdd').parent().hide(); // Storage hide for mobile if needed
        } else {
            $('.spec-field').show();
        }

        // Trigger Modal
        $('#editModal').modal('show');
    });
});
</script>

<?php include 'footer.php'; ?>