<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM lab_tests WHERE lab_test_id=$id");
}
header("Location: view_lab_test.php?msg=deleted");
exit();
?>