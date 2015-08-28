<?php
/** FOR DB **/
require './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/
require './includes/functions.php';

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$sessionUsername = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

$content = "";
if(isset($_GET['tableName'])){
	$origTable = mysql_real_escape_string($_GET['tableName']);
	$table = $origTable;
	if($table==='Applicant'){
		$table = "employee";
	}
	else{
		$table = "employer";
	}
}

$filename = "./downloads/csv/" . $table. "-" . strtotime('now') . ".csv";
$fP = fopen($filename, "w");

//get database table content;
if($table == 'employee'){
	$table1 = $table;
	produceCSVContent($table1, $fP);
	$table2 = 'account-2';
	produceCSVContent($table2, $fP);
	$table3 = 'interest';
	produceCSVContent($table3, $fP);
	$table4 = 'work_history';
	produceCSVContent($table4, $fP);
	$table5 = 'application_history';
	produceCSVContent($table5, $fP);

}
else if($table === 'employer'){
	$table1 = $table;
	produceCSVContent($table1, $fP);
	$table2 = 'account-1';
	produceCSVContent($table2, $fP);
	$table3 = 'job_post';
	produceCSVContent($table3, $fP);
	$table4 = 'location_posts';
	produceCSVContent($table4, $fP);
	$table5 = 'job_location';
	produceCSVContent($table5, $fP);
}

//end file
$endMeta = "*end~" . strtotime("now");
fputs($fP, $endMeta);

//create download link for export
$downloadLink = "Download File: <a href=\"#\" onclick=\"downloadExportedFile('$filename');\">$origTable Database Table</a>";
echo $downloadLink; //print link


//function to add exported values to file
function produceCSVContent($tName, $fP){
	if($tName == "account-2"){
		$getTableQuery = "SELECT * FROM account WHERE acct_type='2' ORDER BY date_joined DESC";
		$tName = substr($tName, 0, -2);
	}
	else if($tName == "account-1"){
		$getTableQuery = "SELECT * FROM account WHERE acct_type='1' ORDER BY date_joined DESC";
		$tName = substr($tName, 0, -2);
	}
	else{
		$getTableQuery = "SELECT * FROM $tName";
	}
	
	$getTable = mysql_query($getTableQuery) or die(mysql_error());
	$numColumns = mysql_num_fields($getTable);
	$numRows = mysql_num_rows($getTable);

	mysql_data_seek($getTable, 0);

	$rCount = 0;
	//get table contents
	while ($tableDataRow = mysql_fetch_assoc($getTable)) {
		if($rCount === 0){
			$tableHeader = "*".$tName."\n";
			fputs($fP, $tableHeader);
		}
		fputcsv($fP, $tableDataRow);
		$rCount += 1;
	}
}
fclose($fP);

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT DATA', '$table')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

mysql_close($link_id);//close database connection
?>