<?php
  /** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

//GET URL VARIABLES
$status = $_GET['status']; 
$codekey = $_GET['codekey'];

//SET CURRDATE
$currDate = date("Y-m-d");

//echo $status . " " . $codekey;
$checkCodeExistsQuery = "SELECT * FROM verification_code WHERE code = '$codekey' ";
$checkCodeExists = mysql_query($checkCodeExistsQuery) or die(mysql_error());
$count = mysql_num_rows($checkCodeExists);
//if code exists
if($count>0){
	$row = mysql_fetch_array($checkCodeExists);
	$email = $row['email'];
	$activateAccountQuery = "UPDATE account SET status='1', date_joined='$currDate' WHERE email ='$email' ";
	$activateAccount = mysql_query($activateAccountQuery) or die(mysql_error());
	$deleteRowQuery = "DELETE FROM verification_code WHERE code='$codekey' ";
	$deleteRow = mysql_query($deleteRowQuery) or die(mysql_error());
	if($activateAccount && $deleteRow) {
		header("Location: login.php?verified=true");
	}
	else
		echo "Failed to verify account.";
}
else
	echo "Error: No code found!";
?>