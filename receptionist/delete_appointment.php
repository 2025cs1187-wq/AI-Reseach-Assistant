<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    mysqli_query($conn, "DELETE FROM appointment WHERE app_id=$id");
}

header("Location: view_appointment.php?msg=deleted");
exit();
?>