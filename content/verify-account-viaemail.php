<?php
include './includes/db.php';
extract($_GET);

if(isset($verificationCode)){
	$sqlSelect = mysql_query("SELECT * FROM verification_code WHERE code='$verificationCode'") or die(mysql_query());
	$count = mysql_num_rows($sqlSelect);
	if($count > 0){
		$row = mysql_fetch_assoc($sqlSelect);
		$username = $row['username'];
		$sqlDelete = mysql_query("DELETE FROM verification_code WHERE code='$verificationCode'") or die(mysql_error());
		$sqlUpdate = mysql_query("UPDATE account SET status='1' WHERE username='$username'") or die(mysql_error());
		$userStatus = getAcctInfo($username, "status");
		//START SESSION TO REDIRECT TO APPROPRIATE LANDING PAGE
		session_start();
		$_SESSION['SRIUsername'] = $username;
		if($userStatus == "Employee"){
			header("Location: browse-jobs.php");
		}
		else if($userStatus == "Employer") {
			header("Location: add-job-post.php");
		}
		else{
			$errorMsg = "An error occured: Account status not specified.";
		}
	}else{
		$errorMsg = "An error occured: Invalid confirmation code!";
	}
	echo $errorMsg;
}
?>
