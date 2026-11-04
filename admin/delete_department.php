<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM departments WHERE department_id=$id");
}
header("Location: view_department.php?msg=deleted");
exit();
?>