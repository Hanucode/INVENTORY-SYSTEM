<?php
include '../datawase/config.php';
session_start();

// Security: Sirf Admin hi access kar sake
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- APPROVE LOGIC ---
if (isset($_GET['approve_id'])) {
    $req_id = mysqli_real_escape_string($conn, $_GET['approve_id']);
    
    // Request details fetch karein
    $req_res = mysqli_query($conn, "SELECT * FROM pending_requests WHERE id = '$req_id' AND status = 'PENDING'");
    if ($req = mysqli_fetch_assoc($req_res)) {
        $target_id = $req['target_id'];
        $type = $req['request_type'];

        if ($type == 'UPDATE') {
            $data = json_decode($req['data_json'], true);
            $name = mysqli_real_escape_string($conn, $data['name']);
            $email = mysqli_real_escape_string($conn, $data['email']);
            $utype = mysqli_real_escape_string($conn, $data['user_type']);
            
            // Password tabhi update karein agar naya password bheja gaya ho
            $pass_sql = "";
            if (!empty($data['password'])) {
                $pass_sql = ", password='{$data['password']}'";
            }
            
            $final_query = "UPDATE user SET name='$name', email='$email', user_type='$utype' $pass_sql WHERE id='$target_id'";
        } else {
            // DELETE Logic
            $final_query = "DELETE FROM user WHERE id = '$target_id'";
        }

        if (mysqli_query($conn, $final_query)) {
            mysqli_query($conn, "UPDATE pending_requests SET status = 'APPROVED' WHERE id = '$req_id'");
            $_SESSION['msg'] = "Request Approved Successfully!";
            $_SESSION['msg_type'] = "success";
        }
    }
    header("Location: requests.php"); exit();
}

// --- REJECT LOGIC ---
if (isset($_GET['reject_id'])) {
    $req_id = mysqli_real_escape_string($conn, $_GET['reject_id']);
    mysqli_query($conn, "UPDATE pending_requests SET status = 'REJECTED' WHERE id = '$req_id'");
    $_SESSION['msg'] = "Request Rejected!";
    $_SESSION['msg_type'] = "danger";
    header("Location: requests.php"); exit();
}
?>

<title>Approval Requests - Inventory System</title>
<?php include 'dash.php'; ?>

<style>
    .req-card { border-radius: 15px; border: none; transition: 0.3s; }
    .req-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .badge-update { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
    .badge-delete { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .data-box { background: #f8fafc; border-radius: 8px; padding: 10px; font-size: 0.85rem; border: 1px dashed #cbd5e1; }
</style>

<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold"><i class="bi bi-shield-check text-primary me-2"></i>Approval Queue</h4>
            <p class="text-muted small">Review and authorize changes requested by other administrators.</p>
        </div>
    </div>

    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php
        // Pending requests ko fetch karein requester ke naam ke saath
        $query = "SELECT r.*, u.name as requester_name, t.name as target_name, t.email as target_email 
                  FROM pending_requests r 
                  JOIN user u ON r.requester_id = u.id 
                  LEFT JOIN user t ON r.target_id = t.id 
                  WHERE r.status = 'PENDING' ORDER BY r.id DESC";
        $res = mysqli_query($conn, $query);

        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $isUpdate = ($row['request_type'] == 'UPDATE');
                $json_data = $isUpdate ? json_decode($row['data_json'], true) : null;
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card req-card shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between">
                    <span class="badge <?php echo $isUpdate ? 'badge-update' : 'badge-delete'; ?> px-3 py-2">
                        <i class="bi <?php echo $isUpdate ? 'bi-pencil-square' : 'bi-trash3'; ?> me-1"></i>
                        <?php echo $row['request_type']; ?>
                    </span>
                    <small class="text-muted"><?php echo date('d M, h:i A', strtotime($row['created_at'])); ?></small>
                </div>
                <div class="card-body">
                    <p class="small mb-1 text-muted text-uppercase fw-bold">Requested By:</p>
                    <h6 class="fw-bold mb-3"><?php echo $row['requester_name']; ?></h6>

                    <hr class="text-muted opacity-25">

                    <p class="small mb-1 text-muted text-uppercase fw-bold">Target User:</p>
                    <div class="mb-3">
                        <strong><?php echo $row['target_name']; ?></strong><br>
                        <small class="text-muted"><?php echo $row['target_email']; ?></small>
                    </div>

                    <?php if ($isUpdate): ?>
                    <div class="data-box mb-3">
                        <p class="mb-1 fw-bold text-primary"><i class="bi bi-arrow-right-circle me-1"></i> Proposed Changes:</p>
                        <div><i class="bi bi-person me-1"></i> <?php echo $json_data['name']; ?></div>
                        <div><i class="bi bi-envelope me-1"></i> <?php echo $json_data['email']; ?></div>
                        <div><i class="bi bi-person-badge me-1"></i> <?php echo ucfirst($json_data['user_type']); ?></div>
                        <?php if($json_data['password']): ?>
                            <div class="text-danger"><i class="bi bi-key me-1"></i> Password change requested</div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger py-2 px-3 small">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> This user will be permanently removed.
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0 pb-3 d-flex gap-2">
                    <a href="requests.php?approve_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm w-100 py-2 shadow-sm" onclick="return confirm('Approve this action?')">Approve</a>
                    <a href="requests.php?reject_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm w-100 py-2" onclick="return confirm('Reject this action?')">Reject</a>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo '<div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7466/7466140.png" width="100" class="opacity-25 mb-3">
                    <h5 class="text-muted">No pending requests to show.</h5>
                  </div>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>