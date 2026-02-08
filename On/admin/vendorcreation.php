<?php
// datawase/config.php ko include kiya
include '../datawase/config.php';
session_start();

// Security Check: Bina login ke koi access na kar sake
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- DATA SAVE LOGIC ---
if (isset($_POST['save_vendor'])) {
    // Inputs ko secure banaya SQL injection se bachne ke liye
    $c_name  = mysqli_real_escape_string($conn, $_POST['company_name']);
    $c_owner = mysqli_real_escape_string($conn, $_POST['company_owner']);
    $mail    = mysqli_real_escape_string($conn, $_POST['mail_id']);
    $addr    = mysqli_real_escape_string($conn, $_POST['address']);
    $pin     = mysqli_real_escape_string($conn, $_POST['pin_code']);
    $mobile  = mysqli_real_escape_string($conn, $_POST['mobile_no']);

    // Aapki di hui query ko dynamic variables ke saath set kiya
    // creat_at humne CURRENT_TIMESTAMP rakha hai isliye query mein dene ki zarurat nahi
    $query = "INSERT INTO `vendor_creation`(`company_name`, `company_owner`, `mail_id`, `address`, `pin_code`, `mobile_no`) 
              VALUES ('$c_name','$c_owner','$mail','$addr','$pin','$mobile')";

    if (mysqli_query($conn, $query)) {
        // Bootstrap Alert ke liye success message
        $_SESSION['msg'] = "Vendor Data Saved Successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Database Error: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
    }
    header("Location: add_vendor.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata'); 
?>

<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <strong><?php echo $_SESSION['msg']; ?></strong>
            <?php unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-danger"></i> Create New Vendor</h5>
        </div>
        <div class="card-body p-4">
            <form action="add_vendor.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Owner</label>
                        <input type="text" name="company_owner" class="form-control" placeholder="Owner Name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mail ID</label>
                        <input type="email" name="mail_id" class="form-control" placeholder="vendor@example.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mobile No.</label>
                        <input type="text" name="mobile_no" class="form-control" placeholder="10 Digit Number" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full Address" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" placeholder="Pin Code" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" name="save_vendor" class="btn btn-danger px-5 shadow-sm" style="background-color: #ff3f3f; border:none;">
                        Save Vendor Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>