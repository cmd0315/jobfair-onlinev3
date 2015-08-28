<?php
/** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

$rowNum = $_GET['rownum'];
$deleteRowQuery = "DELETE FROM work_history WHERE id='$rowNum' ";
$deleteRow = mysql_query($deleteRowQuery);
mysql_close($link_id);

echo $deleteRowQuery;
?>