<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Data Save Logic ---
if (isset($_POST['issue_now'])) {
    $asset_id = mysqli_real_escape_string($conn, $_POST['asset_id']);
    $emp_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    $dept     = mysqli_real_escape_string($conn, $_POST['dept_name']);
    $desig    = mysqli_real_escape_string($conn, $_POST['designation']);
    $emp_code = mysqli_real_escape_string($conn, $_POST['employee_code']);
    $doj      = mysqli_real_escape_string($conn, $_POST['doj']);
    $status   = mysqli_real_escape_string($conn, $_POST['status']);
    $return   = mysqli_real_escape_string($conn, $_POST['is_returned']);

    // 1. UNIQUE CHECK: Employee Code same nahi hona chahiye
    $check_code = mysqli_query($conn, "SELECT id FROM issue_asset WHERE employee_code = '$emp_code'");
    
    // 2. AVAILABILITY CHECK: Asset pehle se issue to nahi?
    $check_issued = mysqli_query($conn, "SELECT id FROM issue_asset WHERE asset_id = '$asset_id' AND is_returned = 'No'");
    
    if (mysqli_num_rows($check_code) > 0) {
        $_SESSION['msg'] = "Error: Employee Code <b>$emp_code</b> already exists! Use a unique code.";
        $_SESSION['msg_type'] = "danger";
    } 
    elseif (mysqli_num_rows($check_issued) > 0) {
        $_SESSION['msg'] = "Error: Asset $asset_id is already issued and not yet returned!";
        $_SESSION['msg_type'] = "danger";
    } 
    else {
        // Fetch Category
        $asset_info = mysqli_query($conn, "SELECT categories FROM add_assets WHERE assetid = '$asset_id'");
        $asset_data = mysqli_fetch_assoc($asset_info);
        $category   = $asset_data['categories'];

        // Insert Data
        $query = "INSERT INTO issue_asset (asset_id, employee_name, department, categories, designation, employee_code, doj, status, is_returned, issue_date) 
                  VALUES ('$asset_id', '$emp_name', '$dept', '$category', '$desig', '$emp_code', '$doj', '$status', '$return', NOW())";

        if (mysqli_query($conn, $query)) {
            $_SESSION['msg'] = "Asset Issued Successfully to $emp_name!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['msg'] = "Error: " . mysqli_error($conn);
            $_SESSION['msg_type'] = "danger";
        }
    }
    header("Location: issue_asset.php");
    exit();
}
?>

<title>Issue Asset - Inventory System</title>
<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2 text-primary"></i> ISSUE NEW ASSET</h6>
        </div>
        <div class="card-body">
            <form action="issue_asset.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Available Asset</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- Choose Asset --</option>
                            <?php
                            $avail_assets = mysqli_query($conn, "SELECT assetid FROM add_assets WHERE assetid NOT IN (SELECT asset_id FROM issue_asset WHERE is_returned = 'No')");
                            while($a = mysqli_fetch_assoc($avail_assets)) {
                                echo "<option value='".$a['assetid']."'>".$a['assetid']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Employee (User)</label>
                        <select name="employee_name" class="form-select" required>
                            <option value="">-- Choose User --</option>
                            <?php
                            $users = mysqli_query($conn, "SELECT name FROM user");
                            while($u = mysqli_fetch_assoc($users)) {
                                echo "<option value='".$u['name']."'>".$u['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Employee Code (Must be Unique)</label>
                        <input type="text" name="employee_code" class="form-control" placeholder="EMP-001" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Department</label>
                        <select name="dept_name" class="form-select" required>
                            <option value="">-- Select Department --</option>
                            <?php
                            $depts = mysqli_query($conn, "SELECT * FROM departments");
                            while($d = mysqli_fetch_assoc($depts)) {
                                echo "<option value='".$d['department']."'>".$d['department']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Designation</label>
                        <input type="text" name="designation" class="form-control" placeholder="e.g. Developer" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date of Joining</label>
                        <input type="date" name="doj" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Account Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Non-Active">Non-Active</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Return Status</label>
                        <select name="is_returned" class="form-select">
                            <option value="No">Not Returned (Pending)</option>
                            <option value="Yes">Returned</option>
                        </select>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <button type="submit" name="issue_now" class="btn btn-primary px-5 shadow-sm">
                            Issue Asset Now
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-muted small"> RECENTLY ISSUED </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Asset ID</th>
                            <th>Employee Name</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Return Status</th>
                            <th>Issue Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM issue_asset ORDER BY id DESC LIMIT 2");
                        while($row = mysqli_fetch_assoc($res)) {
                            $ret_badge = ($row['is_returned'] == 'No') ? 'bg-danger' : 'bg-success';
                        ?>
                        <tr>
                            <td><strong><?php echo $row['asset_id']; ?></strong></td>
                            <td><?php echo $row['employee_name']; ?></td>
                            <td><?php echo $row['employee_code']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><span class="badge <?php echo $ret_badge; ?> rounded-pill"><?php echo ($row['is_returned'] == 'No') ? 'Pending' : 'Returned'; ?></span></td>
                            <td><?php echo date('d M, Y', strtotime($row['issue_date'])); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>