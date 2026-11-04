<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id     = (int)$_GET['id'];
$action = $_GET['action'] ?? '';

if ($action === 'confirm') {
    mysqli_query($conn, "UPDATE appointment SET status='Scheduled' WHERE app_id=$id");
} elseif ($action === 'reject') {
    mysqli_query($conn, "UPDATE appointment SET status='Cancelled' WHERE app_id=$id");
}

header("Location: view_appointment.php?msg=updated");
exit();
?>