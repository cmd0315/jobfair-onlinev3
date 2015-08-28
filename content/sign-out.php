<?php
include './includes/functions.php';
/* FOR DB CONNECTION*/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/* FOR DB CONNECTION*/

$action = $_GET['action'];
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

session_start();
if(isset($_SESSION['SRIUsername'])){
	$username = $_SESSION['SRIUsername'];
}
else{
	$username = "none";

}

if(session_destroy()){
	//add log activity
	if($action != "back"){
		$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$username', 'SIGN OUT')";
		$addLog = mysql_query($addLogQuery) OR die(mysql_error());
		mysql_close($link_id);
	}
	header("Location: ./index.php");
}
?>