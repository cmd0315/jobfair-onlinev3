<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;
/**START get applicant list queries**/
$getTotalLogsQuery = "SELECT * FROM activity_logs";
$linkVariables = array();
/**END get applicant list queries**/

$getTotalLogs = mysql_query($getTotalLogsQuery) or die(mysql_error());
$totalLogs = mysql_num_rows($getTotalLogs);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 20; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navogation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalLogs);
/**END pagination**/

/**START results**/
$getTotalLogsQuery .= " ORDER BY date_made DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedLogs = mysql_query($getTotalLogsQuery) or die(mysql_error());
$totalLimitedLogs = mysql_num_rows($getLimitedLogs);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedLogs > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>Username</th>
						<th>Name</th>
						<th>Account Type</th>
						<th>Action</th>
						<th>Reference Object</th>
						<th>Status</th>
					</tr>";

	$count = 0;
	while($logRow = mysql_fetch_assoc($getLimitedLogs)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$logID = $logRow['id'];
		$logDate = $logRow['date_made'];
		$userID = $logRow['username'];
		$accountType = getAcctInfo($userID, "status");

		if($accountType === ""){
			$accountType = "PUBLIC";
		}
		
		$name = "-";
		if($accountType === "Web Admin"){
			$name = getWebAdminData($userID, "full-name");
		}
		else if($accountType === "Employer" || $accountType === "SRI Branch Manager"){
			$name = getEmployerData($userID, "company-name");
		}
		else if($accountType === "Employee"){
			$name = getEmployeeData($userID, "full-name");
		}
		
		$logAction = $logRow['action'];
		$referenceObj = $logRow['reference_object'];
		$logStatus = $logRow['status'];
		if($logStatus === "0"){
			$logStatus = "Failed";
		}
		else if($logStatus === "1"){
			$logStatus = "Successful";
		}

		$jobPosition = "";
		$jobLocation = "";

		if($referenceObj != "" || $referenceObj != NULL){
			if(strpos($logAction, "JOB POST") !== false){
				$jobPosition = getJobPost($referenceObj, "job-pos-name");
				$referenceObj = $referenceObj . " ($jobPosition)";
			}
			else if(strpos($logAction, "SEARCH JOB") !== false){
				$extractedReferenceObj = explode("-", $referenceObj);
				if($extractedReferenceObj[0] != ""){
					$jobLocation = getJobLocation($extractedReferenceObj[0]);
				}
				if($extractedReferenceObj[1] != ""){
					$jobPosition = getJobPositionName($extractedReferenceObj[1]);
				}

				$referenceObj = $referenceObj . " ($jobLocation - $jobPosition)";
			}
		}

		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . $logDate . "</td>";
		$content .= "<td>" . $userID . "</td>";
		$content .= "<td>" . $name . "</td>";
		$content .= "<td>" . $accountType . "</td>";
		$content .= "<td>" . $logAction . "</td>";
		$content .= "<td>" . $referenceObj . "</td>";
		$content .= "<td>" . $logStatus . "</td>";
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
								(<strong>$currStartItemNum</strong> - <strong>$countDisplay</strong> of <span style=\"color:#00c2ff; font-weight:bold;\">$totalLogs</span>)
							</div>
							<div class=\"span3\">
								<div class=\"btn-toolbar specialToolBar\">
									<div class=\"btn-group\">
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" onclick=\"exportLog();\"><i class=\"icon-download-alt icon-white\"></i> Export Log History</a></span>
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