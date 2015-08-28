<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;
/**START get employer list queries**/
$getJobPostsQuery = "SELECT * FROM job_post WHERE status2=0";
$linkVariables = array();
/**END get employer list queries**/

/*START get link values*/
if(isset($_GET['location'])){
	$location = $_GET['location'];
	$locationID = getLocationID($location);
	$linkString = "location_id='". $locationID . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['jobPosition'])){
	$jobPosition = $_GET['jobPosition'];
	$jobPositionID = getJobPositionID($jobPosition);
	$linkString = "job_position='". $jobPositionID . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['startDate'])){
	$startDate = $_GET['startDate'];
	$linkString = "date_posted>='". $startDate . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['endDate'])){
	$endDate = $_GET['endDate'];
	$endDate = date('Y-m-d', strtotime('+ 1 days'));
	$linkString = "date_posted<='". $endDate . "'";
	array_push($linkVariables, $linkString);
}

/*END get link values*/

/*START create query filter values*/
	$lvCount = 0;
	foreach($linkVariables as $lV){
		$getJobPostsQuery .= " AND " . $lV;
		$lvCount += 1;
	}
/*END create query filter values*/


$getTotalJobPosts = mysql_query($getJobPostsQuery) or die(mysql_error());
$totalEmployers = mysql_num_rows($getTotalJobPosts);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 10; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navogation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalEmployers);
/**END pagination**/

/**START results**/
$getJobPostsQuery .= " ORDER BY date_posted DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedJobPosts = mysql_query($getJobPostsQuery) or die(mysql_error());
$totalLimitedJobPosts = mysql_num_rows($getLimitedJobPosts);
//$totalLimitedLastPageNum = ceil($totalLimitedJobPosts/$resultpPageMax);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedJobPosts > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th></th>
						<th>Job Position</th>
						<th>Company Name</th>
						<th>Job Site</th>
						<th>Date Posted</th>
					</tr>";

	$count = 0;
	while($jobPostsRow = mysql_fetch_assoc($getLimitedJobPosts)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$jobPostCode = $jobPostsRow['code'];
		$jobPosition = getJobPost($jobPostCode, "job-pos-name");
		$companyName = getJobPost($jobPostCode, "company-name");
		$jobSite = getJobPost($jobPostCode, "location");
		$datePosted = getJobPost($jobPostCode, "date-posted");
		$datePosted = date('Y-m-d', strtotime($datePosted));

		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . "<input type=\"checkbox\" class=\"checkBoxes\" name=\"checkBoxes\" id='" . $jobPostCode. "'>" . "</td>";
		$content .= "<td><a onclick=\"viewSummary('$jobPostCode');\" style=\"cursor:pointer;\">" . $jobPosition . "</a></td>";
		$content .= "<td>" . $companyName . "</td>";
		$content .= "<td>" . $jobSite . "</td>";
		$content .= "<td>" . $datePosted . "</td>";
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
								(<strong>$currStartItemNum</strong> - <strong>$countDisplay</strong> of <span style=\"color:#00c2ff; font-weight:bold;\">$totalEmployers</span>)
							</div>
							<div class=\"span2\">
								<label class=\"checkbox\"><input type=\"checkbox\" id=\"selectAll\" name=\"selectAll\" value=\"0\" onclick=\"checkAll();\">Select All</label>
							</div>
							<div class=\"span3\">
								<div class=\"btn-toolbar specialToolBar\">
									<div class=\"btn-group\">
										<span id=\"viewBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"viewBtn\" name=\"viewBtn\" href=\"#\" onclick=\"viewJobPostInfo();\"><i class=\"icon-file icon-white\"></i> View</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportJobPostsData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
									</div>
								</div>
							</div>
						</div>";
/**END select all and total number**/

/*START print outputs*/
if($error===0)
	echo $totalNumberDisplay . $content . $pagination;
else
	echo $content;
/*END print outputs*/
?>