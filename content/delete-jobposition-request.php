<?php
include './includes/db.php';
$jobPositionName = $_GET['jobPositionName'];

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

$updateJobPositionQuery = "DELETE FROM interest WHERE position_id='$jobPositionName'";
$updateJobPosition = mysql_query($updateJobPositionQuery) or die(mysql_error());

echo "successful";
?>