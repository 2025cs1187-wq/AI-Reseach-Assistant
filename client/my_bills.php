<?php
session_start();
require_once '../config/db.php';

$active_page  = 'bills';
$page_title   = 'My Bills';
$top_bar_icon = 'file-invoice-dollar';

require_once __DIR__ . '/../includes/client_header.php';

$result = mysqli_query($conn, "SELECT * FROM bills WHERE patient_id = $patient_id ORDER BY bill_date DESC");

$total  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS t FROM bills WHERE patient_id=$patient_id"))['t'];
$paid   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS t FROM bills WHERE patient_id=$patient_id AND pay_status='Paid'"))['t'];
$unpaid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS t FROM bills WHERE patient_id=$patient_id AND pay_status='Unpaid'"))['t'];
?>

<div class="stats">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-file-invoice"></i></div>
        <div><h3>Rs. <?= number_format($total ?? 0, 2) ?></h3><p>Total Amount</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div><h3>Rs. <?= number_format($paid ?? 0, 2) ?></h3><p>Paid Amount</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
        <div><h3>Rs. <?= number_format($unpaid ?? 0, 2) ?></h3><p>Pending Amount</p></div>
    </div>
</div>

<div class="table-card">
    <h3><i class="fas fa-list"></i> My Bills</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Amount</th>
                <th>Discount</th>
                <th>Insurance</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0):
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td>Rs. <?= number_format($row['amount'], 2) ?></td>
                <td>Rs. <?= number_format($row['discount'] ?? 0, 2) ?></td>
                <td>Rs. <?= number_format($row['insurance_amount'] ?? 0, 2) ?></td>
                <td>Rs. <?= number_format($row['total_amount'], 2) ?></td>
                <td><?= htmlspecialchars($row['payment_method'] ?? '-') ?></td>
                <td><?= $row['bill_date'] ? date('d-m-Y', strtotime($row['bill_date'])) : '-' ?></td>
                <td>
                    <?php $bc = $row['pay_status'] === 'Paid' ? 'green' : ($row['pay_status'] === 'Partial' ? 'orange' : 'red'); ?>
                    <span class="badge <?= $bc ?>"><?= htmlspecialchars($row['pay_status']) ?></span>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <p>No bills found.</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
require_once __DIR__ . '/../includes/client_footer.php';

 ?>
