<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;
/**START get applicant list queries**/
$getTotalJobFairsQuery = "SELECT * FROM job_fair WHERE status!='2'";
$linkVariables = array();
/**END get applicant list queries**/

/*START get link values*/
if(isset($_GET['title'])){
	$title = $_GET['title'];
	$linkString = "title LIKE '%". $title . "%'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['venue'])){
	$venue = $_GET['venue'];
	$linkString = "establishment_name LIKE '%". $venue . "%'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['location'])){
	$location = $_GET['location'];
	$locationID = getLocationID($location);
	$linkString = "location_id='". $locationID . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['duration'])){
	$duration = $_GET['duration'];
	$linkString = "duration='". $duration . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['dateScheduled'])){
	$dateScheduled = $_GET['dateScheduled'];
	$linkString = "date_scheduled='". $dateScheduled . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['jobFairStatus'])){
	$jobFairStatus = $_GET['jobFairStatus'];
	$linkString = "status='". $jobFairStatus . "'";
	array_push($linkVariables, $linkString);
}

/*END get link values*/

/*START create query filter values*/
	$lvCount = 0;
	foreach($linkVariables as $lV){
		$getTotalJobFairsQuery .= " AND " . $lV;
		$lvCount += 1;
	}
/*END create query filter values*/

$getTotalJobFairs = mysql_query($getTotalJobFairsQuery) or die(mysql_error());
$totalApplicants = mysql_num_rows($getTotalJobFairs);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 10; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navigation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalApplicants);
/**END pagination**/

/**START results**/
$getTotalJobFairsQuery .= " ORDER BY date_added DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedJobFairs = mysql_query($getTotalJobFairsQuery) or die(mysql_error());
$totalLimitedJobFairs = mysql_num_rows($getLimitedJobFairs);
//$totalLimitedLastPageNum = ceil($totalLimitedJobFairs/$resultpPageMax);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedJobFairs > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th></th>
						<th>Date Added</th>
						<th>Code</th>
						<th>Title</th>
						<th>Venue</th>
						<th>Address</th>
						<th>Date Scheduled</th>
						<th>Duration</th>
						<th>Opening Time</th>
						<th>Closing Time</th>
						<th>Contact Person</th>
						<th>Status</th>
					</tr>";

	$count = 0;
	while($jobFairRow = mysql_fetch_assoc($getLimitedJobFairs)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$jobFairCode = $jobFairRow['code'];
		
		$title = $jobFairRow['title'];
		$title = ucwords((strtolower($title)));
		
		$venue = $jobFairRow['establishment_name'];
		$street = $jobFairRow['street'];
		$street = ucwords((strtolower($street)));
		$location = $jobFairRow['location_id'];
		$address = $street . ", " . getJobLocation($location);
		
		$dateAdded = $jobFairRow['date_added'];
		$dateAdded = date('Y-m-d', strtotime($dateAdded));
		$dateScheduled = $jobFairRow['date_scheduled'];
		$dateScheduled = date('Y-m-d', strtotime($dateScheduled));
		$duration = $jobFairRow['duration'];
		if($duration > 1){
			$duration = $duration . " days";
		}
		else{
			$duration = $duration . " day";
		}
		$openingTime = $jobFairRow['start_time'];
		$openingTime = date('g:i:a', strtotime($openingTime));
		$closingTime = $jobFairRow['end_time'];
		$closingTime = date('g:i:a', strtotime($closingTime));

		$contactPerson = $jobFairRow['first_name'] . " " . $jobFairRow['middle_name'] . " " . $jobFairRow['last_name'];
		$contactPerson = ucwords((strtolower($contactPerson)));

		$status = $jobFairRow['status'];
		if($status == 0){
			$status = "<span class=\"text-success\">Open</span>";
		}
		else{
			$status = "<span class=\"text-error\">Closed</span>";
		}

		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . "<input type=\"checkbox\" class=\"checkBoxes\" name=\"checkBoxes\" id='" . $jobFairCode. "' val='1'></td>";
		$content .= "<td>" . $dateAdded . "</td>";
		$content .= "<td><a onclick=\"viewSummary('$jobFairCode');\" style=\"cursor:pointer;\">" . $jobFairCode . "</a></td>";
		$content .= "<td>" . $title . "</td>";
		$content .= "<td>" . $venue . "</td>";
		$content .= "<td>" . $address . "</td>";
		$content .= "<td>" . $dateScheduled . "</td>";
		$content .= "<td>" . $duration . "</td>";
		$content .= "<td>" . $openingTime . "</td>";
		$content .= "<td>" . $closingTime . "</td>";
		$content .= "<td>" . $contactPerson . "</td>";
		$content .= "<td>" . $status . "</td>";
		$content .= "</tr>";
	}
	$content .= "</table>";
}
else{
	$error += 1;
	$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
}
$content.="</div></div>";

/**END results**/

/**START select all and total number**/
$currStartItemNum = $startPage + 1;
$totalNumberDisplay = "";
$totalNumberDisplay .= "<div class=\"row-fluid\">
							<div class=\"span2\">
								(<strong>$currStartItemNum</strong> - <strong>$countDisplay</strong> of <span style=\"color:#00c2ff; font-weight:bold;\">$totalApplicants</span>)
							</div>
							<div class=\"span2\">
								<label class=\"checkbox\"><input type=\"checkbox\" id=\"selectAll\" name=\"selectAll\" value=\"0\" onclick=\"checkAll();\">Select All</label>
							</div>
							<div class=\"span3\">
								<div class=\"btn-toolbar specialToolBar\">
									<div class=\"btn-group\">
										<span id=\"viewBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"viewBtn\" name=\"viewBtn\" href=\"#\" onclick=\"viewJobFair();\"><i class=\"icon-file icon-white\"></i> View</a></span>
										<span id=\"closeBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"closeBtn\" name=\"closeBtn\" href=\"#\" onclick=\"closeRemoveJobFairQuery('close');\"><i class=\"icon-minus-sign icon-white\"></i> Close</a></span>
										<span id=\"removeBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"removeBtn\" name=\"removeBtn\" href=\"#\" onclick=\"closeRemoveJobFairQuery('remove');\"><i class=\"icon-trash icon-white\"></i> Remove</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportJobFairData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
									</div>
								</div>
							</div>
						</div>";
/**END select all and total number**/

/*START print outputs*/
if($error===0)
	echo $totalNumberDisplay . $pagination . $content . $pagination;
else
	echo $content;
/*END print outputs*/
?>