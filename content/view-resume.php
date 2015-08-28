<?php
session_start();
$applicant= $_GET['username'];
$_SESSION['SRIEResumeId'] = $applicant;

if(isset($_SESSION['SRIEResumeId'])){
	header("Location: resume.php");
}

?>