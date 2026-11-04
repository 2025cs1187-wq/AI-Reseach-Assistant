<?php
session_start();
require_once '../config/db.php';

$active_page  = 'prescriptions';
$page_title   = 'My Prescriptions';
$top_bar_icon = 'prescription-bottle-alt';

require_once __DIR__ . '/../includes/client_header.php';

$prescriptions = mysqli_query($conn, "
    SELECT p.*, d.name AS doctor_name
    FROM prescriptions p
    JOIN doctor d ON p.doctor_id = d.doctor_id
    WHERE p.patient_id = $patient_id
    ORDER BY p.presc_id DESC
");
?>

<div class="table-card">
    <h3><i class="fas fa-list"></i> My Prescriptions</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Doctor</th>
                <th>Dosage</th>
                <th>Duration</th>
                <th>Instructions</th>
                <th>Refill Count</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($prescriptions) > 0):
                $i = 1;
                while ($row = mysqli_fetch_assoc($prescriptions)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['duration'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['instructions'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['refill_count'] ?? '-') ?></td>
                <td><?= $row['date'] ? date('d-m-Y', strtotime($row['date'])) : '-' ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-prescription-bottle"></i>
                        <p>No prescriptions found.</p>
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
