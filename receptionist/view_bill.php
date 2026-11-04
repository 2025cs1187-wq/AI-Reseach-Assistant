<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$result = mysqli_query($conn, "
    SELECT b.*, p.name AS patient_name, p.email AS patient_email
    FROM bills b 
    LEFT JOIN patient p ON b.patient_id = p.patient_id 
    ORDER BY b.bill_date ASC
");

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-file-invoice-dollar"></i> Bills</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Bills</p>
        </div>
        <a href="add_bill.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Bill</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Bills</div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Amount (Rs.)</th>
                        <th>Discount (Rs.)</th>
                        <th>Insurance (Rs.)</th>
                        <th>Total (Rs.)</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['bill_id'] ?></td>
                        <td><?= htmlspecialchars($row['patient_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['patient_email'] ?? 'N/A') ?></td>
                        <td><?= number_format($row['amount'], 2) ?></td>
                        <td><?= number_format($row['discount'], 2) ?></td>
                        <td><?= number_format($row['insurance_amount'], 2) ?></td>
                        <td><?= number_format($row['total_amount'], 2) ?></td>
                        <td>
                            <?php
                                $status = $row['pay_status'];
                                $badge  = $status === 'Paid' ? 'success' : ($status === 'Unpaid' ? 'danger' : 'warning');
                            ?>
                            <span class="badge badge-<?= $badge ?>"><?= $status ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                        <td><?= $row['bill_date'] ?></td>
                        
                            <td>
                            <a href="edit_patient.php?id=<?= $r['patient_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            
                        </td>
                           
                        
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center">No bills found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>