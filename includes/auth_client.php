<?php

session_start();

if(!isset($_SESSION['client']))
{
    header("location:../login.php?role=client");
}

?>