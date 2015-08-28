<?php
session_start();
  /** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

extract($_POST);


$street = trim(mysql_real_escape_string($street));
$jobDesc = trim(mysql_real_escape_string($jobDesc));

$_SESSION['SRIJobPosition'] = $position;
$_SESSION['SRIJobNumVacancies'] = $numVacancies;
$_SESSION['SRIJobLocation'] = $location;
$_SESSION['SRIJobStreet'] = $street;
$_SESSION['SRIJobDesc'] = $jobDesc;

header("Location: add-job-post.php?browsing=true");

mysql_close($link_id);
?>