<?php
session_start();
require_once '../config/db.php';

$active_page  = 'appointments';
$page_title   = 'My Appointments';
$top_bar_icon = 'calendar-check';

require_once __DIR__ . '/../includes/client_header.php';

$result = mysqli_query($conn, "
    SELECT a.*, d.name AS doctor_name, d.specialization
    FROM appointment a
    JOIN doctor d ON a.doctor_id = d.doctor_id
    WHERE a.patient_id = $patient_id
    ORDER BY a.date_time DESC
");
?>

<div class="table-card">
    <h3><i class="fas fa-list"></i> My Appointments</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Notes</th>
                <th>Follow Up</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0):
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['specialization'] ?? '—') ?></td>
                <td><?= $row['date_time'] ? date('d-m-Y', strtotime($row['date_time'])) : '—' ?></td>
                <td><?= $row['date_time'] ? date('h:i A', strtotime($row['date_time'])) : '—' ?></td>
                <td>
                    <?php
                    $status = $row['status'] ?? '';
                    $bc = match($status) {
                        'Completed' => 'green',
                        'Scheduled' => 'blue',
                        'Cancelled', 'No Show' => 'red',
                        default => 'orange'
                    };
                    ?>
                    <span class="badge <?= $bc ?>"><?= htmlspecialchars($status ?: '—') ?></span>
                </td>
                <td><?= htmlspecialchars($row['reason'] ?? '—') ?></td>
                <td><?= htmlspecialchars($row['notes'] ?? '—') ?></td>
                <td><?= $row['follow_up_date'] ? date('d-m-Y', strtotime($row['follow_up_date'])) : '—' ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No appointments found.</p>
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
