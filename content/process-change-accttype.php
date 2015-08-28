<?php
require './includes/db.php';

session_start();
if(isset($_SESSION['SRIUsername'])){
	$sessionUser = $_SESSION['SRIUsername'];
}
else{
	header('Location: ./index.php');
}

if(isset($_GET['username'])){
	$employer= mysql_real_escape_string($_GET['username']);
}

$content = "";

//get currentdate
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");

//get employer info
$oldAcctType = getAcctInfo($employer, "status");
$companyName = getEmployerData($employer, "company-name");
$changeAcctTypeQuery = "";

//change account type
if($oldAcctType === "Employer"){
	$changeAcctTypeQuery = "UPDATE account SET acct_type='3'";
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUser', 'PROMOTE EMPLOYER')";
}
else{
	$changeAcctTypeQuery = "UPDATE account SET acct_type='1'";
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUser', 'DEMOTE EMPLOYER')";
}
$changeAcctTypeQuery .= ", date_updated='$currDateTime' WHERE username='$employer'";
$changeAcctType = mysql_query($changeAcctTypeQuery);
$addLog = mysql_query($addLogQuery) OR die(mysql_error()); //add log activity

//print results
if($changeAcctType){
	$newAcctType = getAcctInfo($employer, "status");
	$content .="<div class=\"modal-header\">
							<h4 class=\"content-heading3\">Account Type Changed</h4>
						</div>
						<div class=\"modal-body\">
							<div class=\"row-fluid\">
								<div class=\"span12\">
									<p>You have successfuly changed the account type of <span style=\"font-weight:bold; color:#089DFF;\">$companyName</span> from <span style=\"color:#089DFF;\">$oldAcctType</span> to <span style=\"color:#089DFF;\">$newAcctType</span>.</p>";

	$content.="	</div>
						</div>
						<div class=\"modal-footer\">
							<div class=\"row-fluid\">
								<div class=\"span4 offset4\">
									<button class=\"btn btn-primary span12\" style=\"font-size:12px;\" onclick=\"window.location .reload();\">OK</button>
								</div>
							</div>
						</div>";
	echo $content;
}
else{
	echo "<span class=\"alert alert-error\">ERROR: " . mysql_error() . "</span>";
}

?>