<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Category Add Logic ---
if (isset($_POST['add_cat'])) {
    $cat_name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    if (!empty($cat_name)) {
        $query = "INSERT INTO categories (name) VALUES ('$cat_name')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['msg'] = "Category Added Successfully!";
            $_SESSION['msg_type'] = "success";
        }
    }
    header("Location: categorie.php");
    exit();
}

// --- Category Delete Logic ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
    header("Location: categorie.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata'); 
?>

<title>Categories - Inventory System</title>
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
                    <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3 me-2"></i> ADD NEW CATEGORY</h6>
                </div>
                <div class="card-body">
                    <form action="categorie.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="cat_name" class="form-control" placeholder="Category Name" required>
                        </div>
                        <button type="submit" name="add_cat" class="btn btn-primary w-100 shadow-sm" style="background-color: #5cb85c; border:none;">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i> ALL CATEGORIES</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Categories</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $count++; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="categorie.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
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