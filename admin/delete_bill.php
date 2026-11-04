<?php
require_once '../includes/auth_admin.php';
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM bills WHERE bill_id = $id");
}

header('Location: bills.php');
exit;
?>