<?php
include './includes/db.php';

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRISearchUser'];

$code = $_POST['code'];
$answer = "";

$checkCodeQuery = "SELECT * FROM verification_code WHERE code='$code' AND username='$username' ";
$checkCode = mysql_query($checkCodeQuery);
$rowNum = mysql_num_rows($checkCode);
if($rowNum > 0) {
	$answer = "true";
}
else {
	$answer = "false";
}

echo $answer;

?>