<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- SUPER ADMIN CONFIG ---
// Yahan apni login wali email likhein
$super_admin_email = "admin@gmail.com"; 
$is_super = ($_SESSION['user_email'] == $super_admin_email); 

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $admin_id = $_SESSION['user_id'];

    if ($is_super) {
        // Direct Action for Super Admin
        mysqli_query($conn, "DELETE FROM user WHERE id = '$id'");
        $_SESSION['msg'] = "User Deleted Successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        // Approval Request for Junior Admin
        mysqli_query($conn, "INSERT INTO pending_requests (requester_id, target_id, request_type) VALUES ('$admin_id', '$id', 'DELETE')");
        $_SESSION['msg'] = "Delete request sent for approval!";
        $_SESSION['msg_type'] = "warning";
    }
    header("Location: userdata.php"); exit();
}

// --- UPDATE LOGIC ---
if (isset($_POST['update_user'])) {
    $id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $pass = $_POST['password'];
    $admin_id = $_SESSION['user_id'];

    if ($is_super) {
        // Direct Update for Super Admin
        $pass_sql = !empty($pass) ? ", password='".password_hash($pass, PASSWORD_DEFAULT)."'" : "";
        mysqli_query($conn, "UPDATE user SET name='$name', email='$email', user_type='$type' $pass_sql WHERE id='$id'");
        $_SESSION['msg'] = "User <b>$name</b> updated directly!";
        $_SESSION['msg_type'] = "success";
    } else {
        // Approval Request for Junior Admin
        $update_data = ['name' => $name, 'email' => $email, 'user_type' => $type, 'password' => !empty($pass) ? password_hash($pass, PASSWORD_DEFAULT) : null];
        $json_data = mysqli_real_escape_string($conn, json_encode($update_data));
        mysqli_query($conn, "INSERT INTO pending_requests (requester_id, target_id, request_type, data_json) VALUES ('$admin_id', '$id', 'UPDATE', '$json_data')");
        $_SESSION['msg'] = "Update request sent to Super Admin!";
        $_SESSION['msg_type'] = "info";
    }
    header("Location: userdata.php"); exit();
}
?>

<title>User Management - Inventory System</title>
<?php include 'dash.php'; ?>

<style>
    .user-card-header { background: #fff; border-bottom: 2px solid #f1f5f9; }
    .table thead th { background-color: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; }
    .user-avatar { width: 35px; height: 35px; background: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; }
    .modal-content { border: none; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .modal-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; padding: 1.5rem; }
    .modal-body { padding: 2rem; }
    .form-label { font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem; }
    .form-control { border-radius: 10px; padding: 0.6rem 1rem; border: 1px solid #e2e8f0; }
    .form-control:focus { box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); border-color: #6366f1; }
    .input-group-text { border-radius: 10px 0 0 10px; background: #f8fafc; border-color: #e2e8f0; }
    .btn-save { background: #4f46e5; border: none; border-radius: 10px; padding: 0.6rem 2rem; font-weight: 600; transition: 0.3s; }
    .btn-save:hover { background: #4338ca; transform: translateY(-2px); }
</style>

<div class="container-fluid p-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark">User Control Panel</h4>
            <p class="text-muted small">
                <?php echo $is_super ? "Logged in as <b>Super Admin</b> (Direct Access)" : "Logged in as <b>Admin</b> (Requires Approval)"; ?>
            </p>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex justify-content-end">
                <div class="input-group w-50 shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Find user..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i> <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">User Details</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                    $query = "SELECT u.*, (SELECT request_type FROM pending_requests WHERE target_id = u.id AND status = 'PENDING' LIMIT 1) as p_act 
                             FROM user u WHERE u.name LIKE '%$search%' OR u.email LIKE '%$search%' ORDER BY u.id DESC";
                    $res = mysqli_query($conn, $query);

                    while($row = mysqli_fetch_assoc($res)) {
                        $role_badge = ($row['user_type'] == 'admin') ? 'bg-primary' : 'bg-info';
                        $initial = strtoupper(substr($row['name'], 0, 1));
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3"><?php echo $initial; ?></div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo $row['name']; ?></div>
                                    <small class="text-muted">ID: #<?php echo $row['id']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $row['email']; ?></td>
                        <td><span class="badge <?php echo $role_badge; ?> rounded-pill px-3"><?php echo ucfirst($row['user_type']); ?></span></td>
                        <td>
                            <?php if($row['p_act']): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending <?php echo $row['p_act']; ?></span>
                            <?php else: ?>
                                <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <?php if($is_super || !$row['p_act']): ?>
                                <button class="btn btn-sm btn-light text-primary editBtn" 
                                        data-id="<?php echo $row['id']; ?>"
                                        data-name="<?php echo $row['name']; ?>"
                                        data-email="<?php echo $row['email']; ?>"
                                        data-type="<?php echo $row['user_type']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="userdata.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-danger ms-1" onclick="return confirm('Confirm this action?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light text-muted" disabled><i class="bi bi-lock-fill"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="userEditModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form action="userdata.php" method="POST" class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Edit User Profile</h5>
                    <small class="opacity-75">Modify account settings and permissions</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Change only if needed">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Account Role</label>
                        <select name="user_type" id="edit_type" class="form-select">
                            <option value="admin">Administrator</option>
                            <option value="user">Standard User</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="update_user" class="btn btn-save text-white shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.editBtn').on('click', function() {
        $('#edit_user_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_type').val($(this).data('type'));
        $('#userEditModal').modal('show');
    });
});
</script>

<?php include 'footer.php'; ?>