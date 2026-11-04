<?php
require_once '../includes/auth_receptionist.php';
require_once '../config/db.php';

$doctors = mysqli_query($conn, "SELECT * FROM doctor ORDER BY doctor_id DESC");

include '../includes/receptionist_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-md"></i> Doctors</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Doctors</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list"></i> All Doctors</div>
        </div>
        <div class="table-responsive">
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
                        <th>Consultation Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($doctors) > 0):
                        $i = 1; while ($row = mysqli_fetch_assoc($doctors)): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['specialization']) ?></td>
                        <td><?= htmlspecialchars($row['qualification']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['experience']) ?> yrs</td>
                        <td>
                            <strong style="color:#6a0dad;">
                                Rs. <?= number_format($row['consultation_fee'], 0) ?>
                            </strong>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#999;">
                            <i class="fas fa-user-md" style="font-size:2rem;"></i>
                            <p>No doctors found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>