<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$patients = mysqli_query($conn, "SELECT * FROM patient ORDER BY patient_id DESC");
include '../includes/lab_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-procedures"></i> Patients</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Patients</p>
        </div>
    </div>

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
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="9">
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