<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['role'] !== 'pharmacist') {
    header("Location: /hospital_management/login.php");
    exit();
}
?>