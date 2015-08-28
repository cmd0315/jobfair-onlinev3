<?php 
include './includes/db.php';
$username = $_GET['accountInfo'];
$answer = checkAccountExists($username);

if($answer == "Yes" || $answer == "Not Verified"){
	session_start();
	$_SESSION['SRISearchUser'] = $username;
	$user = $_SESSION['SRISearchUser']; 
}

echo $answer;

?>