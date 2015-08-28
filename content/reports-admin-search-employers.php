<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$getFilteredEmployersQuery = "SELECT * FROM job_post WHERE status1='0' AND status2='0'";
$getTotalEmployersQuery = "SELECT * FROM employer INNER JOIN account ON employer.username = account.username";
$linkVariables = array();
$getFinalEmployersQuery = "";
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$startPage = ($page-1) * $resultpPageMax;
$jobPositionsBool = 1; //$_GET job positions is not set
$error = 0;

/**START get applicant list based on interest**/
if(isset($_GET['jobPositions'])){
	$jobPositions = $_GET['jobPositions'];
	$jPositions = json_decode(stripslashes($jobPositions), true);
	$jCount = 0;
	$jPositionsSize = sizeof($jPositions);
	while($jCount<$jPositionsSize){ //filter by interests
		$jP = $jPositions[$jCount];
		$jCount +=1;
		$linkString = "job_position='". $jP . "'";
		array_push($linkVariables, $linkString);
	}

	//complete '$getFilteredEmployersQuery' query
	$formedQuery = formQuery($linkVariables, $getFilteredEmployersQuery, 2);

	$getFilteredEmployers = mysql_query($formedQuery) or die(mysql_error());
	$filteredEmployersNumRow = mysql_num_rows($getFilteredEmployers);
	$filteredEmployers = array();
	$linkVariables = array();

	//get filtered applicants
	while($filteredEmployersRow = mysql_fetch_assoc($getFilteredEmployers)){
		$qAUsername = $filteredEmployersRow['employer_username'];
		// $linkString = "employee.username='". $qAUsername . "'";
		// array_push($linkVariables, $linkString);
		array_push($filteredEmployers, $qAUsername);
	}
	$newFilteredEmployers = array_count_values($filteredEmployers);
	$nFECount = 0;
	foreach($newFilteredEmployers as $nFEKey => $nFE){
		if($jPositionsSize <= 1 || $nFE >= 2){
			$linkString = "employer.username='". $nFEKey . "'";
			array_push($linkVariables, $linkString);
			$nFECount += 1;
		}
	}

	if($nFECount < 1){
		$error += 1;
		$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
		printContent($error, '', $content, '');
	}
	$getFinalEmployersQuery = formQuery($linkVariables, $getTotalEmployersQuery, 1);
	$jobPositionsBool = 0;
}
else{
	$getFinalEmployersQuery = $getTotalEmployersQuery;
	$jobPositionsBool = 1;

}
/**END get applicant list based on interest**/

/*END other filters*/
$linkVariables = array();
if(isset($_GET['location'])){
	$location = $_GET['location'];
	$location = explode(",", $location);
	$city = trim($location[0]);
	$province = trim($location[1]);
	$linkString = "employer.city1='". $city . "' AND employer.province1='" . $province . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['startDate'])){
	$startDate = $_GET['startDate'];
	$linkString = "account.date_joined>='". $startDate . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['endDate'])){
	$endDate = $_GET['endDate'];
	$linkString = "account.date_joined<='". $endDate . "'";
	array_push($linkVariables, $linkString);
}

//complete query to add other filter values
if($jobPositionsBool === 0){
	$getFinalEmployersQuery = formQuery($linkVariables, $getFinalEmployersQuery, 2);
}
/*END other filters*/

if($getFinalEmployersQuery !=""){
	$getFinalEmployers = mysql_query($getFinalEmployersQuery) or die(mysql_error());
	$totalEmployers = mysql_num_rows($getFinalEmployers);
}


/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 8; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navigation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalEmployers);
/**END pagination**/

/*START prepare HTML to print*/
$getFinalEmployersQuery .= " ORDER BY account.date_joined DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getFinalEmployers = mysql_query($getFinalEmployersQuery) or die(mysql_error());

$content = "";
$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalEmployers > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th></th>
						<th>Company Name</th>
						<th>Account Type</th>
						<th>Company Description</th>
						<th>Location</th>
						<th>Date Registered</th>
					</tr>";

	$count = 0;
	while($companyRow = mysql_fetch_assoc($getFinalEmployers)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$companyUsername = $companyRow['username'];
		$acctType = getAcctInfo($companyUsername, "status");
		$companyName = $companyRow['company_name'];
		$companyDescription = $companyRow['company_desc'];
		$companyDescMaxChar = 100;
		$companyDescription = substr($companyDescription, 0, $companyDescMaxChar);
		$pos = strrpos($companyDescription, " ");
		if ($pos>0) {
			$companyDescription = substr($companyDescription, 0, $pos) . " ...";
		}
		$companyLocation = $companyRow['city1'] . ", " . $companyRow['province1'];
		$dateRegistered = getAcctInfo($companyUsername, "date-joined");
		$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . "<input type=\"checkbox\" class=\"checkBoxes\" name=\"checkBoxes\" id='" . $companyUsername. "' val='1'></td>";
		$content .= "<td><a onclick=\"viewSummary('$companyUsername');\" style=\"cursor:pointer;\">" . $companyName . "</a></td>";
		$content .= "<td>" . $acctType . "</td>";
		$content .= "<td>" . $companyDescription . "</td>";
		$content .= "<td>" . $companyLocation . "</td>";
		$content .= "<td>" . $dateRegistered . "</td>";
		$content .= "</tr>";
	}
	$content .= "</table>";
}
else{
	$error += 1;
	$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
}
$content.="</div></div>";

//html for total count of results
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
										<span id=\"viewBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"viewBtn\" name=\"viewBtn\" href=\"#\" onclick=\"viewEmployerProfile();\"><i class=\"icon-file icon-white\"></i> View</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportEmployerData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
										<span id=\"promoteBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"promoteBtn\" name=\"promoteBtn\" href=\"#\" onclick=\"showChangeAccountQuestion();\"><i class=\"icon-user icon-white\"></i> Change Account Type</a></span>
									</div>
								</div>
							</div>
						</div>";
printContent($error, $totalNumberDisplay, $content, $pagination);
/*END prepare HTML to print*/

/*START print outputs*/
function printContent($errorNum, $tND, $c, $p){
	if($errorNum===0)
		echo $tND . $c . $p;
	else
		echo $c . "</br>";
	exit;
}
/*END print outputs*/


/*formQuery function*/
function formQuery($filteredArray, $query, $typeNum){
	$fACount = 0;
	$conditionalLink = "";
	if($typeNum === 1){
		foreach($filteredArray as $fA){
			if($fACount === 0){
				$query .= " WHERE (" . $fA;
			}
			else if($fACount === 0){
				$query .= " OR " . $fA . ")";
			}
			else{
				$query .= " OR " . $fA;
			}
			$fACount += 1;
		}
		$query .= ")";
	}
	else if($typeNum === 2){
		foreach($filteredArray as $fA){
			$query .= " AND " . $fA;
			$fACount += 1;
		}
	}
	return $query;
}
?>