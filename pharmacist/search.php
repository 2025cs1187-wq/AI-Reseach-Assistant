<?php
require_once '../includes/auth_pharmacist.php';
require_once '../config/db.php';

$search_type = $_GET['search_type'] ?? '';
$search_val  = trim($_GET['search_val'] ?? '');
$results     = null;

if ($search_type && $search_val) {
    if ($search_type === 'medicine') {
        $results = mysqli_query($conn, "
            SELECT * FROM medicines
            WHERE name LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            ORDER BY medicine_id DESC
        ");
    } elseif ($search_type === 'patient') {
        $results = mysqli_query($conn, "
            SELECT p.*, pr.medicine_name, pr.dosage
            FROM patient p
            LEFT JOIN prescriptions pr ON p.patient_id = pr.patient_id
            WHERE p.name LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            OR p.email LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            ORDER BY p.patient_id DESC
        ");
    }
}

include '../includes/pharmacist_header.php';
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
                <div style="display:flex; gap:12px; margin-bottom:1.5rem;">
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="search_type" value="medicine"
                               <?= $search_type==='medicine'||!$search_type ? 'checked':'' ?>
                               style="display:none;">
                        <div style="border:2px solid <?= $search_type==='medicine'||!$search_type ? 'var(--primary)':'#ddd' ?>; border-radius:10px; padding:16px; text-align:center;">
                            <i class="fas fa-pills" style="font-size:1.5rem; color:<?= $search_type==='medicine'||!$search_type ? 'var(--primary)':'#aaa' ?>;"></i>
                            <div style="font-weight:600; margin-top:6px; color:<?= $search_type==='medicine'||!$search_type ? 'var(--primary)':'#666' ?>;">Search by Medicine</div>
                            <div style="font-size:0.8rem; color:#aaa;">e.g. Panadol, Amoxicillin</div>
                        </div>
                    </label>
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="search_type" value="patient"
                               <?= $search_type==='patient' ? 'checked':'' ?>
                               style="display:none;">
                        <div style="border:2px solid <?= $search_type==='patient' ? 'var(--primary)':'#ddd' ?>; border-radius:10px; padding:16px; text-align:center;">
                            <i class="fas fa-user" style="font-size:1.5rem; color:<?= $search_type==='patient' ? 'var(--primary)':'#aaa' ?>;"></i>
                            <div style="font-weight:600; margin-top:6px; color:<?= $search_type==='patient' ? 'var(--primary)':'#666' ?>;">Search by Patient</div>
                            <div style="font-size:0.8rem; color:#aaa;">Name or Email</div>
                        </div>
                    </label>
                </div>

                <div style="display:flex; gap:10px;">
                    <input type="text" name="search_val" id="search_input" class="form-control"
                           placeholder="<?= $search_type==='patient' ? 'Enter patient name or email...' : 'Enter medicine name...' ?>"
                           value="<?= htmlspecialchars($search_val) ?>"
                           style="flex:1; font-size:1rem; padding:12px 16px;">
                    <button type="submit" class="btn btn-primary" style="padding:12px 24px;">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if ($search_val): ?>
                    <a href="search.php" class="btn btn-outline" style="padding:12px 24px;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if ($results !== null): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-list"></i> Results
                <span style="font-size:0.85rem; color:#888; font-weight:400;">
                    — <?= mysqli_num_rows($results) ?> record(s) found
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <?php if ($search_type === 'medicine'): ?>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Expiry</th>
                        <?php else: ?>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($results) > 0):
                    while ($r = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <?php if ($search_type === 'medicine'): ?>
                    <td><?= $r['medicine_id'] ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td><?= htmlspecialchars($r['category'] ?? '—') ?></td>
                    <td>
                        <?php if ($r['stock_quantity'] <= 10): ?>
                            <span class="badge badge-danger"><?= $r['stock_quantity'] ?> Low!</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $r['stock_quantity'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>Rs. <?= number_format($r['price'] ?? 0, 0) ?></td>
                    <td><?= $r['expiry_date'] ? date('d-m-Y', strtotime($r['expiry_date'])) : '—' ?></td>
                    <?php else: ?>
                    <td><?= $r['patient_id'] ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['phone']) ?></td>
                    <td><?= htmlspecialchars($r['medicine_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['dosage'] ?? '—') ?></td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>No records found</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('input[name="search_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('search_input').placeholder =
            this.value === 'patient' ? 'Enter patient name or email...' : 'Enter medicine name...';
    });
});
</script>

<?php include '../includes/footer.php'; ?>