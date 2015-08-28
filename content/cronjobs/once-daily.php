<?php
/** FOR DB **/
include '../includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

/*Start Delete Expired Job Posts*/
function deleteExpiredJobPosts(){
	$getJobPostQuery = "Select * FROM job_post WHERE job_closedate<=CURRENT_DATE "; //select all job posts that have already expired
	$getJobPost = mysql_query($getJobPostQuery) or die(mysql_error());
	$getJobPostNumRow = mysql_num_rows($getJobPost);
	if($getJobPostNumRow > 0){
		$expiredLocationsArr = array();
		while($jobPostResultRow = mysql_fetch_assoc($getJobPost)){
			$resultJobPostCode = $jobPostResultRow['code'];
			// set status as removed(entries will stay on database but appear as removed)
			$deleteJobPostQuery = "UPDATE job_post SET status2='1'  WHERE code='$resultJobPostCode'";
			$deleteJobPost = mysql_query($deleteJobPostQuery) or die(mysql_error());
			
			// set status of location post as removed(entries will stay on database but appear as removed)
			$deleteLocationJobPostQuery = "UPDATE location_posts SET status='1' WHERE post_code='$resultJobPostCode' ";
			$deleteLocationJobPost = mysql_query($deleteLocationJobPostQuery) or die(mysql_error());
					
			//get all job locations with job post
			$getJobLocationQuery = "SELECT * FROM location_posts WHERE post_code='$resultJobPostCode' ";
			$getJobLocation = mysql_query($getJobLocationQuery) or die(mysql_error());
			$getJobLocationNumRow = mysql_num_rows($getJobLocation);
			
			if($getJobLocationNumRow>0){
				while($locationResultRow = mysql_fetch_assoc($getJobLocation)){
					$resultJobLocationId = $locationResultRow['location_id'];
					array_push($expiredLocationsArr, $resultJobLocationId); //add to array all job locations that have at least one job post that has expired
					
				}
			}
		}
		$finalELArr = array_unique($expiredLocationsArr); //remove duplicate entries
	}
	foreach($finalELArr as $fEL){
		$countExpiredLocationPostsQuery = "SELECT * FROM location_posts WHERE location_id='$fEL' AND status='0' ";
		$countExpiredLocationPosts = mysql_query($countExpiredLocationPostsQuery) or die(mysql_error());
		$fELCount = mysql_num_rows($countExpiredLocationPosts);
		if($fELCount == 0){
			//delete from job_location table, locations that do not have job posts
			$deleteLocationQuery = "DELETE FROM job_location WHERE location_id='$fEL' ";
			$deleteLocation = mysql_query($deleteLocationQuery) or die(mysql_error());
			//echo "hey";
		}
		else{
			echo "not delete <br/>";
		}
	}
}
/*End Delete Expired Job Posts*/

/*START Export Employers*/
function exportEmployees(){
	$filename = "../downloads/csv/employee-contacts-" . strtotime('now') . ".csv";
	$fP = fopen($filename, "w");
	$getTableQuery = "SELECT * FROM employee";

	$getTable = mysql_query($getTableQuery) or die(mysql_error());
	mysql_data_seek($getTable, 0);

	$rCount = 0;
	//get table contents
	while ($tableDataRow = mysql_fetch_assoc($getTable)) {
		fputcsv($fP, $tableDataRow);
		$rCount += 1;
	}
	fclose($fP);
}
/*END Export Employers*/

deleteExpiredJobPosts(); //call delete expired jobs function
exportEmployees();
mysql_close($link_id);
?>