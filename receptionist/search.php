<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$search  = trim($_GET['q'] ?? '');
$type    = $_GET['type'] ?? 'patient';
$results = [];

if ($search !== '') {
    $s = "%$search%";

    if ($type === 'patient') {
        $stmt = $conn->prepare("
            SELECT * FROM patient 
            WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?
            ORDER BY patient_id ASC
        ");
        $stmt->bind_param('sss', $s, $s, $s);
        $stmt->execute();
        $results = $stmt->get_result();

    } elseif ($type === 'appointment') {
        $stmt = $conn->prepare("
            SELECT a.*, p.name AS patient_name, p.email AS patient_email, 
                   p.phone AS patient_phone, d.name AS doctor_name
            FROM appointments a
            LEFT JOIN patient p ON a.patient_id = p.patient_id
            LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
            WHERE p.name LIKE ? OR p.email LIKE ? OR d.name LIKE ?
            ORDER BY a.app_id ASC
        ");
        $stmt->bind_param('sss', $s, $s, $s);
        $stmt->execute();
        $results = $stmt->get_result();

    } elseif ($type === 'bill') {
        $stmt = $conn->prepare("
            SELECT b.*, p.name AS patient_name, p.email AS patient_email,
                   p.phone AS patient_phone
            FROM bills b
            LEFT JOIN patient p ON b.patient_id = p.patient_id
            WHERE p.name LIKE ? OR p.email LIKE ?
            ORDER BY b.bill_id ASC
        ");
        $stmt->bind_param('ss', $s, $s);
        $stmt->execute();
        $results = $stmt->get_result();

    } elseif ($type === 'doctor') {
        $stmt = $conn->prepare("
            SELECT * FROM doctor
            WHERE name LIKE ? OR specialization LIKE ? OR phone LIKE ?
            ORDER BY doctor_id ASC
        ");
        $stmt->bind_param('sss', $s, $s, $s);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-search"></i> Search</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Search</p>
        </div>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-search"></i> Search Records</div>
        </div>
        <div style="padding:1.5rem;">
            <form method="GET">
                <div style="display:flex;gap:10px;margin-bottom:1rem;flex-wrap:wrap;">
                    <a href="?type=patient&q=<?= urlencode($search) ?>"
                       style="padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;
                              background:<?= $type=='patient'?'#6a0dad':'#f0f0f0' ?>;
                              color:<?= $type=='patient'?'#fff':'#555' ?>;">
                        <i class="fas fa-procedures"></i> Patients
                    </a>
                    <a href="?type=appointment&q=<?= urlencode($search) ?>"
                       style="padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;
                              background:<?= $type=='appointment'?'#6a0dad':'#f0f0f0' ?>;
                              color:<?= $type=='appointment'?'#fff':'#555' ?>;">
                        <i class="fas fa-calendar-check"></i> Appointments
                    </a>
                    <a href="?type=bill&q=<?= urlencode($search) ?>"
                       style="padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;
                              background:<?= $type=='bill'?'#6a0dad':'#f0f0f0' ?>;
                              color:<?= $type=='bill'?'#fff':'#555' ?>;">
                        <i class="fas fa-file-invoice-dollar"></i> Bills
                    </a>
                    <a href="?type=doctor&q=<?= urlencode($search) ?>"
                       style="padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;
                              background:<?= $type=='doctor'?'#6a0dad':'#f0f0f0' ?>;
                              color:<?= $type=='doctor'?'#fff':'#555' ?>;">
                        <i class="fas fa-user-md"></i> Doctors
                    </a>
                </div>
                <div style="display:flex;gap:10px;">
                    <input type="hidden" name="type" value="<?= $type ?>">
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
                           placeholder="Search by name, email, phone..."
                           style="flex:1;padding:11px 16px;border:1.5px solid #ddd;border-radius:9px;font-size:.95rem;outline:none;">
                    <button type="submit"
                            style="padding:11px 24px;background:#6a0dad;color:#fff;border:none;border-radius:9px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($search !== ''): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-list"></i> Results for "<?= htmlspecialchars($search) ?>"
            </div>
        </div>
        <div class="table-responsive">

            <?php if ($type === 'patient'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>DOB</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Marital Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($results && $results->num_rows > 0):
                    while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['patient_id'] ?></td>
                    <td><?= htmlspecialchars($r['name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['gender'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['blood_group'] ?? '—') ?></td>
                    <td><?= $r['dob'] ? date('d-m-Y', strtotime($r['dob'])) : '—' ?></td>
                    <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['email'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['marital_status'] ?? '—') ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" style="text-align:center;padding:30px;color:#999;">
                    <i class="fas fa-search" style="font-size:2rem;"></i>
                    <p>No patients found</p>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php elseif ($type === 'appointment'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Follow Up</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($results && $results->num_rows > 0):
                    while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['app_id'] ?></td>
                    <td><?= htmlspecialchars($r['patient_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['patient_phone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['doctor_name'] ?? '—') ?></td>
                    <td><?= $r['date_time'] ? date('d-m-Y H:i', strtotime($r['date_time'])) : '—' ?></td>
                    <td><?= htmlspecialchars($r['reason'] ?? '—') ?></td>
                    <td>
                        <?php
                        $status = $r['status'] ?? 'Pending';
                        $badge = match($status) {
                            'Completed'  => 'success',
                            'Cancelled'  => 'danger',
                            'Scheduled'  => 'warning',
                            default      => 'secondary'
                        };
                        ?>
                        <span class="badge badge-<?= $badge ?>"><?= $status ?></span>
                    </td>
                    <td><?= $r['follow_up_date'] ? date('d-m-Y', strtotime($r['follow_up_date'])) : '—' ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" style="text-align:center;padding:30px;color:#999;">
                    <i class="fas fa-calendar" style="font-size:2rem;"></i>
                    <p>No appointments found</p>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php elseif ($type === 'bill'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Bill Date</th>
                        <th>Amount</th>
                        <th>Discount</th>
                        <th>Insurance</th>
                        <th>Total</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($results && $results->num_rows > 0):
                    while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['bill_id'] ?></td>
                    <td><?= htmlspecialchars($r['patient_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['patient_phone'] ?? '—') ?></td>
                    <td><?= $r['bill_date'] ? date('d-m-Y', strtotime($r['bill_date'])) : '—' ?></td>
                    <td>Rs. <?= number_format($r['amount'] ?? 0, 0) ?></td>
                    <td>Rs. <?= number_format($r['discount'] ?? 0, 0) ?></td>
                    <td>Rs. <?= number_format($r['insurance_amount'] ?? 0, 0) ?></td>
                    <td>Rs. <?= number_format($r['total_amount'] ?? 0, 0) ?></td>
                    <td><?= htmlspecialchars($r['payment_method'] ?? '—') ?></td>
                    <td>
                        <?php
                        $status = $r['pay_status'] ?? '—';
                        $badge = match($status) {
                            'Paid'    => 'success',
                            'Unpaid'  => 'danger',
                            'Partial' => 'warning',
                            default   => 'secondary'
                        };
                        ?>
                        <span class="badge badge-<?= $badge ?>"><?= $status ?></span>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="10" style="text-align:center;padding:30px;color:#999;">
                    <i class="fas fa-file-invoice-dollar" style="font-size:2rem;"></i>
                    <p>No bills found</p>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php elseif ($type === 'doctor'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Qualification</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Experience</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($results && $results->num_rows > 0):
                    while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['doctor_id'] ?></td>
                    <td><?= htmlspecialchars($r['name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['specialization'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['qualification'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['gender'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['phone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['email'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['experience'] ?? '—') ?> yrs</td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" style="text-align:center;padding:30px;color:#999;">
                    <i class="fas fa-user-md" style="font-size:2rem;"></i>
                    <p>No doctors found</p>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>