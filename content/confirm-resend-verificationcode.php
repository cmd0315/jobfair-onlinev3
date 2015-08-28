<?php
session_start();

$username = $_GET['username'];
$_SESSION['SRISearchUser'] = $username;

header("Location: resend-verification-code.php");

?>