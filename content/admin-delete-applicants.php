<?php
require './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/

session_start();
if(isset($_SESSION['SRIUsername'])){
	$user = $_SESSION['SRIUsername'];
}
else{
	$user = "none";
}

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

//EXTRACT GET VARIABLES
extract($_GET);

if(isset($usernames)){
	$usernames = explode("-", $usernames);
	$usernamesNum = count($usernames);
}

$result = "";

if(sizeof($usernames) > 0){
	foreach($usernames as $uN){
		$deleteApplicantAccountQuery = "DELETE FROM account WHERE username='$uN'";
		$deleteApplicantAccount = mysql_query($deleteApplicantAccountQuery) or die(mysql_error());
		
		$deleteApplicantQuery = "DELETE FROM employee WHERE username='$uN'";
		$deleteApplicant = mysql_query($deleteApplicantQuery) or die(mysql_error());

		$deleteApplicantInterestQuery = "DELETE FROM interest WHERE employee_username='$uN'";
		$deleteApplicantInterest = mysql_query($deleteApplicantInterestQuery) or die(mysql_error());

		$deleteApplicantAHistoryQuery = "DELETE FROM application_history WHERE employee_username='$uN'";
		$deleteApplicantAHistory = mysql_query($deleteApplicantAHistoryQuery) or die(mysql_error());

		$deleteApplicantWHistoryQuery = "DELETE FROM work_history WHERE employee_username='$uN'";
		$deleteApplicantWHistory = mysql_query($deleteApplicantWHistoryQuery) or die(mysql_error());

		//add log activity
		$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$user', 'DELETE APPLICANT', '$uN')";
		$addLog = mysql_query($addLogQuery) OR die(mysql_error());
	}
	$result = "0";
}
else{
	$result = "1";
}

echo $result;
mysql_close($link_id);
?>