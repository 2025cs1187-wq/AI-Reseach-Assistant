<?php
require_once '../includes/auth_pharmacist.php';
require_once '../config/db.php';

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'added')   $msg = "Medicine added successfully!";
if (isset($_GET['msg']) && $_GET['msg'] === 'updated') $msg = "Medicine updated successfully!";
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $msg = "Medicine deleted.";

$medicines = mysqli_query($conn, "SELECT * FROM medicines ORDER BY medicine_id DESC");

include '../includes/pharmacist_header.php';
?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-pills"></i> Medicines</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Medicines</p>
        </div>
        <a href="add_medicine.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Medicine
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Medicines</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Company</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Expiry Date</th>
                        <th>Dosage Form</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($medicines) > 0):
                    while ($r = mysqli_fetch_assoc($medicines)): ?>
                <tr>
                    <td><?= $r['medicine_id'] ?></td>
                    <td><div class="cell-main"><?= htmlspecialchars($r['ord_name']) ?></div></td>
                    <td><?= htmlspecialchars($r['type']) ?></td>
                    <td><?= htmlspecialchars($r['company']) ?></td>
                    <td>
                        <?php
                        $stock = $r['stock'];
                        $color = $stock <= 10 ? 'danger' : ($stock <= 50 ? 'warning' : 'success');
                        ?>
                        <span class="badge badge-<?= $color ?>"><?= $stock ?> units</span>
                    </td>
                    <td>Rs. <?= number_format($r['price'], 2) ?></td>
                    <td><?= htmlspecialchars($r['expiry_date']) ?></td>
                    <td><?= htmlspecialchars($r['dosage_form']) ?></td>
                    <td>
                        <a href="edit_medicine.php?id=<?= $r['medicine_id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_medicine.php?id=<?= $r['medicine_id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this medicine?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-pills"></i>
                            <p>No medicines found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>