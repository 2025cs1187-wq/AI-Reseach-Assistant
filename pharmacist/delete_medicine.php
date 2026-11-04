<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM medicines WHERE medicine_id=$id");
}
header("Location: view_medicine.php?msg=deleted");
exit();
?>