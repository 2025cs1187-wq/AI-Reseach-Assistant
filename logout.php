

<?php
session_start();
session_destroy();
header("Location: /hospital_management/login.php");
exit();
?>