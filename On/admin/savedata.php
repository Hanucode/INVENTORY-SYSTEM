<?php
include '../datawase/config.php';
session_start();

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export Asset Data - Inventory System</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .export-card { border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .table thead th { background-color: #f8fafc; color: #475569; font-weight: 700; font-size: 0.85rem; }
        
        /* Export Buttons Style */
        .dt-buttons .btn {
            border-radius: 8px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            margin-right: 5px;
            padding: 8px 16px !important;
            transition: 0.3s;
        }
        .btn-excel { background-color: #198754 !important; color: white !important; border: none !important; }
        .btn-pdf { background-color: #dc3545 !important; color: white !important; border: none !important; }
        .btn-print { background-color: #0dcaf0 !important; color: white !important; border: none !important; }
        .dt-buttons .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        
        .dataTables_filter input {
            border-radius: 10px;
            padding: 6px 15px;
            border: 1px solid #e2e8f0;
            outline: none;
        }
    </style>
</head>
<body>

<?php include 'dash.php'; ?>

<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold"><i class="bi bi-file-earmark-arrow-down-fill text-primary me-2"></i>Download Reports</h4>
            <p class="text-muted">Generate and download All Asset List in Excel, PDF or Print format.</p>
        </div>
    </div>

    <div class="card export-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="saveDataTable" class="table table-hover align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Asset ID</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Serial No</th>
                            <th>RAM</th>
                            <th>Storage</th>
                            <th>Condition</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM add_assets ORDER BY id DESC";
                        $res = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <tr>
                            <td><strong><?php echo $row['assetid']; ?></strong></td>
                            <td><?php echo $row['categories']; ?></td>
                            <td><?php echo $row['brand']; ?></td>
                            <td><?php echo $row['model_no']; ?></td>
                            <td><small class="text-muted"><?php echo $row['serial_no']; ?></small></td>
                            <td><?php echo $row['ram']; ?></td>
                            <td><?php echo $row['hdd_sdd']; ?></td>
                            <td><span class="badge bg-success-subtle text-success border-success-subtle px-2"><?php echo $row['asset_condition']; ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#saveDataTable').DataTable({
        "dom": '<"d-flex justify-content-between align-items-center mb-4"Bf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        "buttons": [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel-fill me-1"></i> Download Excel',
                className: 'btn-excel',
                title: 'Inventory_Asset_Report_' + new Date().toLocaleDateString()
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF',
                className: 'btn-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Asset Inventory Report'
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer-fill me-1"></i> Print Table',
                className: 'btn-print'
            }
        ],
        "pageLength": 10,
        "language": {
            "search": "Filter Data:",
            "paginate": {
                "next": '<i class="bi bi-chevron-right"></i>',
                "previous": '<i class="bi bi-chevron-left"></i>'
            }
        }
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>