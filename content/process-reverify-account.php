<?php

include './includes/db.php';

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRISearchUser'];
extract($_POST);

//DELETE CODE FROM VERIFICATION_CODE TABLE
$deleteCodeQuery = "DELETE FROM verification_code WHERE username='$username' ";
$deleteCode = mysql_query($deleteCodeQuery) or die(mysql_error());


$setStatusQuery = "UPDATE account SET status='1' WHERE username='$username' ";
$setStatus = mysql_query($setStatusQuery) or die(mysql_error());
header("Location: ./login.php?new=true");

?>