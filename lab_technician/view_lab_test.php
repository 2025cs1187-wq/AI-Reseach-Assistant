<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'added')   $msg = "Lab test added successfully!";
if (isset($_GET['msg']) && $_GET['msg'] === 'updated') $msg = "Lab test updated successfully!";
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $msg = "Lab test deleted.";

$lab_tests = mysqli_query($conn, "
    SELECT lt.*, pt.name AS patient_name, pt.email AS patient_email
    FROM lab_tests lt
    LEFT JOIN patient pt ON lt.patient_id = pt.patient_id
    ORDER BY lt.lab_test_id DESC
");

include '../includes/lab_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-flask"></i> Lab Tests</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Lab Tests</p>
        </div>
        <a href="add_lab_test.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Lab Test
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Lab Tests</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Test Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Result</th>
                        <th>Normal Range</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($lab_tests) > 0):
                    while ($r = mysqli_fetch_assoc($lab_tests)):
                        $sc = ['Pending'=>'badge-warning','Completed'=>'badge-success','Cancelled'=>'badge-danger'];
                        $c  = $sc[$r['status']] ?? 'badge-info';
                ?>
                <tr>
                    <td><?= $r['lab_test_id'] ?></td>
                    <td><?= htmlspecialchars($r['patient_name'] ?? '—') ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td><?= $r['test_date'] ? date('d-m-Y', strtotime($r['test_date'])) : '—' ?></td>
                    <td><span class="badge <?= $c ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                    <td><?= htmlspecialchars($r['result'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['normal_range'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['remarks'] ?? '—') ?></td>
                    <td>
                        <a href="edit_lab_test.php?id=<?= $r['lab_test_id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="delete_lab_test.php?id=<?= $r['lab_test_id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this lab test?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-flask"></i>
                            <p>No lab tests found</p>
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