<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true || $_SESSION['role'] !== 'pharmacist') {
    header("Location: /hospital_management/login.php");
    exit();
}
?>