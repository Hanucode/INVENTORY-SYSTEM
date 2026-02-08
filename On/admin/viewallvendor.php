<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- DELETE VENDOR ---
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM vendor_creation WHERE id = '$id'")) {
        $_SESSION['msg'] = "Vendor deleted successfully!";
        $_SESSION['msg_type'] = "warning";
    }
    header("Location: viewallvendor.php");
    exit();
}

// --- UPDATE VENDOR (Modal Submit) ---
if (isset($_POST['update_vendor'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $company = mysqli_real_escape_string($conn, $_POST['company_name']);
    $owner = mysqli_real_escape_string($conn, $_POST['company_owner']);
    $mail = mysqli_real_escape_string($conn, $_POST['mail_id']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pin_code']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile_no']);

    $update_query = "UPDATE vendor_creation SET 
                     company_name='$company', company_owner='$owner', mail_id='$mail', 
                     address='$address', pin_code='$pincode', mobile_no='$mobile' 
                     WHERE id='$id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['msg'] = "Vendor <b>$company</b> Updated Successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Error: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: viewallvendor.php");
    exit();
}
?>

<title>Vendor Management - Inventory System</title>
<?php include 'dash.php'; ?>

<style>
    .vendor-card { border-radius: 15px; border: none; }
    .table thead th { background-color: #f8fafc; color: #64748b; font-weight: 700; font-size: 0.8rem; border-bottom: 2px solid #edf2f7; }
    .company-icon { width: 40px; height: 40px; background: #fff1f2; color: #e11d48; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .modal-header { background: linear-gradient(135deg, #0f172a, #1e293b); color: white; }
    .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; }
    .btn-update { background: #e11d48; border: none; color: white; font-weight: 600; border-radius: 8px; padding: 10px 25px; }
    .btn-update:hover { background: #be123c; }
</style>

<div class="container-fluid p-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0">Vendor Directory</h4>
            <p class="text-muted small">Manage all your suppliers and vendor contact details.</p>
        </div>
        <div class="col-md-6 text-end">
            <form action="" method="GET" class="d-inline-block me-2">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control border-0" placeholder="Search vendor..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button class="btn btn-white bg-white border-0" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <a href="add_vendor.php" class="btn btn-danger px-4 shadow-sm"><i class="bi bi-plus-lg me-2"></i>Add Vendor</a>
        </div>
    </div>

    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm mb-4">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card vendor-card shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Company & Owner</th>
                        <th>Contact Information</th>
                        <th>Location</th>
                        <th>Registered On</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                    $sql = "SELECT * FROM vendor_creation WHERE company_name LIKE '%$search%' OR company_owner LIKE '%$search%' ORDER BY id DESC";
                    $res = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="company-icon me-3"><i class="bi bi-buildings"></i></div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo $row['company_name']; ?></div>
                                    <small class="text-muted"><?php echo $row['company_owner']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-envelope me-2"></i><?php echo $row['mail_id']; ?></div>
                            <div class="small"><i class="bi bi-telephone me-2"></i><?php echo $row['mobile_no']; ?></div>
                        </td>
                        <td>
                            <div class="small text-truncate" style="max-width: 200px;"><?php echo $row['address']; ?></div>
                            <small class="badge bg-light text-dark fw-normal">PIN: <?php echo $row['pin_code']; ?></small>
                        </td>
                        <td class="small"><?php echo date('d M, Y', strtotime($row['creat_at'])); ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light text-primary editVendorBtn" 
                                data-id="<?php echo $row['id']; ?>"
                                data-company="<?php echo $row['company_name']; ?>"
                                data-owner="<?php echo $row['company_owner']; ?>"
                                data-mail="<?php echo $row['mail_id']; ?>"
                                data-address="<?php echo $row['address']; ?>"
                                data-pin="<?php echo $row['pin_code']; ?>"
                                data-mobile="<?php echo $row['mobile_no']; ?>">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="viewallvendor.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-danger ms-1" onclick="return confirm('Are you sure you want to delete this vendor?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No vendors found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editVendorModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="viewallvendor.php" method="POST" class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Update Vendor Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="v_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" id="v_company" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Owner</label>
                        <input type="text" name="company_owner" id="v_owner" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="mail_id" id="v_mail" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile_no" id="v_mobile" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Full Address</label>
                        <input type="text" name="address" id="v_address" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pin Code</label>
                        <input type="text" name="pin_code" id="v_pin" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="update_vendor" class="btn btn-update shadow-sm">Save Vendor Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.editVendorBtn').on('click', function() {
        $('#v_id').val($(this).data('id'));
        $('#v_company').val($(this).data('company'));
        $('#v_owner').val($(this).data('owner'));
        $('#v_mail').val($(this).data('mail'));
        $('#v_mobile').val($(this).data('mobile'));
        $('#v_address').val($(this).data('address'));
        $('#v_pin').val($(this).data('pin'));
        
        $('#editVendorModal').modal('show');
    });
});
</script>

<?php include 'footer.php'; ?>