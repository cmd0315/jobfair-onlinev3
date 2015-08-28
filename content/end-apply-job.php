<?php
	session_start();
	$_SESSION['SRIEmployeeApplying'] = "false";
	header("Location: browse-jobs.php");
?>
