<?php

include './includes/db.php';
$username = $_GET['username'];
$type = $_GET['type'];
$value = $_GET['value'];
$result = "";

$employeeEmailAvailabilityQuery = "SELECT * FROM employee WHERE $type='$value'  AND username <> '$username' ";
$checkEmployeeEmailAvailability = mysql_query($employeeEmailAvailabilityQuery) or die(mysql_error());
$employeeEmailCount = mysql_num_rows($checkEmployeeEmailAvailability);

$employerEmailAvailabilityQuery = "SELECT * FROM employer WHERE $type='$value' AND username <> '$username' ";
$checkEmployerEmailAvailability = mysql_query($employerEmailAvailabilityQuery) or die(mysql_error());
$employerEmailCount = mysql_num_rows($checkEmployerEmailAvailability);


if($employeeEmailCount >0 || $employerEmailCount >0){
	$result = "false";
}
else{
	$result = "true";
}

echo $result;

?>