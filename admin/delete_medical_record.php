<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    mysqli_query($conn, "DELETE FROM medical_records WHERE record_id=$id");
}

header("Location: view_medical_record.php?msg=deleted");
exit();
?>