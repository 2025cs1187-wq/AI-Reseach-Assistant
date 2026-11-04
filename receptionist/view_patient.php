<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$msg = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'added') {
    $msg = "Patient added successfully!";
}


$patients = mysqli_query($conn, "SELECT * FROM patient ORDER BY patient_id ASC");
include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-procedures"></i> Patients</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Patients</p>
        </div>
        <a href="add_patient.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Patient
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Patients</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Marital Status</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($patients) > 0):
                    while ($r = mysqli_fetch_assoc($patients)): ?>
                    <tr>
                        <td><?= $r['patient_id'] ?></td>
                        <td><div class="cell-main"><?= htmlspecialchars($r['name']) ?></div></td>
                        <td><?= $r['dob'] ? date('d-m-Y', strtotime($r['dob'])) : '-' ?></td>
                        <td><?= htmlspecialchars($r['gender']) ?></td>
                        <td>
                            <?php if ($r['blood_group']): ?>
                                <span class="badge badge-danger"><?= htmlspecialchars($r['blood_group']) ?></span>
                            <?php else: echo '-'; endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['marital_status']) ?></td>
                        <td><?= htmlspecialchars($r['phone']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['address']) ?></td>
                        <td>
                            <a href="edit_patient.php?id=<?= $r['patient_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-procedures"></i>
                                <p>No patients found</p>
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