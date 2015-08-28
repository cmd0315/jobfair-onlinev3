<?php
include './includes/db.php';
/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRISearchUser'];
extract($_POST);

$answer = changePassword($username, md5($newPassword));

//DELETE CODE FROM VERIFICATION_CODE TABLE
$deleteCodeQuery = "DELETE FROM verification_code WHERE username='$username' ";
$deleteCode = mysql_query($deleteCodeQuery) or die(mysql_error());

?>