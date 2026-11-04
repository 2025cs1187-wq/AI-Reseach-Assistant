<?php
require_once '../includes/auth_lab.php';
require_once '../config/db.php';

$results     = null;
$search_type = $_GET['search_type'] ?? '';
$search_val  = trim($_GET['search_val'] ?? '');

if ($search_type && $search_val) {
    if ($search_type === 'test') {
        $results = mysqli_query($conn, "
            SELECT lt.*, pt.name AS patient_name, pt.email AS patient_email
            FROM lab_tests lt
            LEFT JOIN patient pt ON lt.patient_id = pt.patient_id
            WHERE lt.name LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            ORDER BY lt.lab_test_id DESC
        ");
    } elseif ($search_type === 'patient') {
        $results = mysqli_query($conn, "
            SELECT lt.*, pt.name AS patient_name, pt.email AS patient_email
            FROM lab_tests lt
            LEFT JOIN patient pt ON lt.patient_id = pt.patient_id
            WHERE pt.email LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            OR pt.name LIKE '%" . mysqli_real_escape_string($conn, $search_val) . "%'
            ORDER BY lt.lab_test_id DESC
        ");
    }
}

include '../includes/lab_header.php';
?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-search"></i> Search</h1>
            <p class="breadcrumb"><a href="dashboard.php">Home</a> &rsaquo; Search</p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-search"></i> Search Lab Records</div>
        </div>
        <div style="padding:1.5rem;">
            <form method="GET">
                <!-- Search Type Tabs -->
                <div style="display:flex; gap:12px; margin-bottom:1.5rem;">
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="search_type" value="test" 
                               <?= $search_type==='test'||!$search_type ? 'checked':'' ?>
                               style="display:none;" 
                               onchange="updatePlaceholder(this)">
                        <div class="search-tab <?= $search_type==='test'||!$search_type ? 'search-tab-active':'' ?>"
                             style="border:2px solid <?= $search_type==='test'||!$search_type ? 'var(--primary)':'#ddd' ?>; border-radius:10px; padding:16px; text-align:center; transition:all .2s;">
                            <i class="fas fa-flask" style="font-size:1.5rem; color:<?= $search_type==='test'||!$search_type ? 'var(--primary)':'#aaa' ?>;"></i>
                            <div style="font-weight:600; margin-top:6px; color:<?= $search_type==='test'||!$search_type ? 'var(--primary)':'#666' ?>;">Search by Test Name</div>
                            <div style="font-size:0.8rem; color:#aaa;">e.g. CBC, MRI, ECG</div>
                        </div>
                    </label>
                    <label style="flex:1; cursor:pointer;">
                        <input type="radio" name="search_type" value="patient"
                               <?= $search_type==='patient' ? 'checked':'' ?>
                               style="display:none;"
                               onchange="updatePlaceholder(this)">
                        <div class="search-tab <?= $search_type==='patient' ? 'search-tab-active':'' ?>"
                             style="border:2px solid <?= $search_type==='patient' ? 'var(--primary)':'#ddd' ?>; border-radius:10px; padding:16px; text-align:center; transition:all .2s;">
                            <i class="fas fa-user" style="font-size:1.5rem; color:<?= $search_type==='patient' ? 'var(--primary)':'#aaa' ?>;"></i>
                            <div style="font-weight:600; margin-top:6px; color:<?= $search_type==='patient' ? 'var(--primary)':'#666' ?>;">Search by Patient</div>
                            <div style="font-size:0.8rem; color:#aaa;">Name or Email</div>
                        </div>
                    </label>
                </div>

                <!-- Search Input -->
                <div style="display:flex; gap:10px;">
                    <input type="text" name="search_val" id="search_input" class="form-control"
                           placeholder="<?= $search_type==='patient' ? 'Enter patient name or email...' : 'Enter test name e.g. CBC, MRI...' ?>"
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

    <!-- Results -->
    <?php if ($results !== null): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-list"></i> Results
                <span style="font-size:0.85rem; color:#888; font-weight:400;">
                    — <?= mysqli_num_rows($results) ?> record(s) found for 
                    "<strong><?= htmlspecialchars($search_val) ?></strong>"
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Test Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($results) > 0):
                    while ($r = mysqli_fetch_assoc($results)):
                        $sc = ['Pending'=>'badge-warning','Completed'=>'badge-success','Cancelled'=>'badge-danger'];
                        $c  = $sc[$r['status']] ?? 'badge-info';
                ?>
                <tr>
                    <td><?= $r['lab_test_id'] ?></td>
                    <td><?= htmlspecialchars($r['patient_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['patient_email'] ?? '—') ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td><?= $r['test_date'] ? date('d-m-Y', strtotime($r['test_date'])) : '—' ?></td>
                    <td><span class="badge <?= $c ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                    <td><?= htmlspecialchars($r['result'] ?? '—') ?></td>
                    <td>
                        <a href="edit_lab_test.php?id=<?= $r['lab_test_id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="8">
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
// Tab click pe highlight
document.querySelectorAll('input[name="search_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.search-tab').forEach(tab => {
            tab.style.border = '2px solid #ddd';
            tab.querySelector('i').style.color = '#aaa';
            tab.querySelector('div').style.color = '#666';
        });
        const activeTab = this.nextElementSibling;
        activeTab.style.border = '2px solid var(--primary)';
        activeTab.querySelector('i').style.color = 'var(--primary)';
        activeTab.querySelector('div').style.color = 'var(--primary)';
        
        document.getElementById('search_input').placeholder = 
            this.value === 'patient' ? 'Enter patient name or email...' : 'Enter test name e.g. CBC, MRI...';
    });
});
</script>

<?php include '../includes/footer.php'; ?>