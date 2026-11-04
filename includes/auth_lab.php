<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'lab_technician') {
    header("Location: /hospital_management/lab_login.php");
    exit();
}
?>