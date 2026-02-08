<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Add Department Logic ---
if (isset($_POST['add_dept'])) {
    $dept_name = mysqli_real_escape_string($conn, $_POST['department']);
    if (!empty($dept_name)) {
        $query = "INSERT INTO departments (department) VALUES ('$dept_name')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['msg'] = "Department Added Successfully!";
            $_SESSION['msg_type'] = "success";
        }
    }
    header("Location: add_department.php");
    exit();
}

// --- Delete Logic ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM departments WHERE id=$id");
    header("Location: add_department.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata'); 
?>

<title>Departments - Inventory System</title>
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
                    <h6 class="mb-0 fw-bold text-danger"><i class="bi bi-building-add me-2"></i> ADD DEPARTMENT</h6>
                </div>
                <div class="card-body">
                    <form action="add_department.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department Name</label>
                            <input type="text" name="department" class="form-control" placeholder="e.g. IT, HR, worker" required>
                        </div>
                        <button type="submit" name="add_dept" class="btn btn-danger w-100 shadow-sm" style="background-color: #ef5350; border:none;">Save Department</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i> DEPARTMENT LIST</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Department Name</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM departments ORDER BY id DESC");
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $count++; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td class="text-center">
                                    <a href="add_department.php?delete=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this department?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>