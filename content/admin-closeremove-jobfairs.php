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

if(isset($jobFairCodes)){
	$jobFairCodes = explode("-", $jobFairCodes);
	$jobFairCodesNum = count($jobFairCodes);
}

$result = "";
$decisionCode = 0;

if($decision === "close"){
	$decisionCode = 1;
}
else{
	$decisionCode = 2;
}

if(sizeof($jobFairCodes) > 0){
	foreach($jobFairCodes as $jFC){
		$checkCloseRemoveQuery = "SELECT status FROM job_fair WHERE code='$jFC'";
		$checkCloseRemove = mysql_query($checkCloseRemoveQuery) OR die(mysql_error());
		$checkCloseRemoveResult = mysql_fetch_row($checkCloseRemove);
		$status = $checkCloseRemoveResult[0];

		if($status == $decisionCode){
			$result = "<span class=\"text-error\">Error! Unable to " . $decision ." job fair #" . $jFC . ". Job Fair is already " . $decision . "d</span>";
		}
		else{
			if($decision === "close"){
				$closeRemoveJobFairQuery = "UPDATE job_fair SET status='1' WHERE code='$jFC'"; //close
				$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$user', 'CLOSE JOB FAIR', '$jFC')";
			}
			else{
				$closeRemoveJobFairQuery = "UPDATE job_fair SET status='2' WHERE code='$jFC'"; //remove
				$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$user', 'REMOVE JOB FAIR', '$jFC')";
			}
			$closeRemoveJobFair = mysql_query($closeRemoveJobFairQuery) OR die(mysql_error());
			$addLog = mysql_query($addLogQuery) OR die(mysql_error()); //add log activity
			$result = "You have successfully <span class=\"text-info\">" . $decision . "d</span> job fair #<span class=\"text-info\">" . $jFC . "</span>";
		}
	}
}
else{
	$result = "<span class=\"text-error\">Error! No selected job fairs.</span>";
}

echo $result;
mysql_close($link_id);
?>