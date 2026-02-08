<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete Logic
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM add_assets WHERE id = '$id'")) {
        $_SESSION['msg'] = "Asset Deleted Successfully!";
        $_SESSION['msg_type'] = "danger";
        header("Location: view_asset.php");
        exit();
    }
}
?>

<title>View Assets - Inventory System</title>
<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show border-0 shadow-sm">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2"></i> ALL ASSETS</h5>
            <a href="add_Assets.php" class="btn btn-primary btn-sm shadow-sm px-3">ADD NEW</a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle text-center">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Asset ID</th>
                            <th>Brand / Category</th>
                            <th>Model / Serial</th>
                            <th>RAM / Storage</th>
                            <th>Processor</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM add_assets ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        $count = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $cat = strtolower($row['categories']);
                        ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><strong><?php echo $row['assetid']; ?></strong></td>
                                <td>
                                    <div class="fw-bold"><?php echo $row['brand']; ?></div>
                                    <span class="badge bg-info text-dark small"><?php echo $row['categories']; ?></span>
                                </td>
                                <td>
                                    <div class="small">M: <?php echo $row['model_no']; ?></div>
                                    <div class="small text-muted">S: <?php echo $row['serial_no']; ?></div>
                                </td>
                                
                                <td>
                                    <?php 
                                    if(empty($row['ram']) && empty($row['hdd_sdd'])) {
                                        echo "<i class='text-muted small'>Its ".ucfirst($cat)."</i>";
                                    } else {
                                        echo $row['ram'] . " / " . $row['hdd_sdd'];
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php 
                                    if(empty($row['processor'])) {
                                        echo "<i class='text-muted small'>Its ".ucfirst($cat)."</i>";
                                    } else {
                                        echo $row['processor'];
                                    }
                                    ?>
                                </td>

                                <td><span class="badge bg-light text-dark border"><?php echo $row['asset_condition']; ?></span></td>
                                
                                <td>
                                    <div class="btn-group">
                                        <a href="edit_asset.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-info btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="view_asset.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this asset?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='8' class='py-4 text-muted'>No assets found in inventory.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>