<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;
/**START get employer list queries**/
$getTotalEmployersQuery = "SELECT * FROM employer INNER JOIN account ON employer.username = account.username";
$linkVariables = array();
/**END get employer list queries**/

/*START get link values*/
if(isset($_GET['employerName'])){
	$employerName = $_GET['employerName'];
	$linkString = "company_name LIKE '%". $employerName . "%'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['location'])){
	$location = $_GET['location'];
	$location = explode(",", $location);
	$city = trim($location[0]);
	$province = trim($location[1]);
	$linkString = "city1='". $city . "' AND province1='" . $province . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['startDate'])){
	$startDate = $_GET['startDate'];
	$linkString = "account.date_joined>='". $startDate . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['endDate'])){
	$endDate = $_GET['endDate'];
	$endDate = date('Y-m-d', strtotime('+ 1 days'));
	$linkString = "account.date_joined<='". $endDate . "'";
	array_push($linkVariables, $linkString);
}

/*END get link values*/

/*START create query filter values*/
	$lvCount = 0;
	foreach($linkVariables as $lV){
		if($lvCount === 0){
			$getTotalEmployersQuery .= " WHERE " . $lV;
		}
		else{
			$getTotalEmployersQuery .= " AND " . $lV;
		}
		$lvCount += 1;
	}
/*END create query filter values*/


$getTotalEmployers = mysql_query($getTotalEmployersQuery) or die(mysql_error());
$totalEmployers = mysql_num_rows($getTotalEmployers);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 8; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navogation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalEmployers);
/**END pagination**/

/**START results**/
$getTotalEmployersQuery .= " ORDER BY account.date_joined DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedEmployers = mysql_query($getTotalEmployersQuery) or die(mysql_error());
$totalLimitedEmployers = mysql_num_rows($getLimitedEmployers);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedEmployers > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th></th>
						<th>Company Name</th>
						<th>Account Type</th>
						<th>Company Description</th>
						<th>Contact Person</th>
						<th>Location</th>
						<th>Date Registered</th>
					</tr>";

	$count = 0;
	while($employerRow = mysql_fetch_assoc($getLimitedEmployers)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$companyUsername = $employerRow['username'];
		$acctType = getAcctInfo($companyUsername, "status");
		$companyName = $employerRow['company_name'];

		//START summarize company description
		$companyDescription = $employerRow['company_desc'];
		$companyDescMaxChar = 100;
		$companyDescription = substr($companyDescription, 0, $companyDescMaxChar);
		$pos = strrpos($companyDescription, " ");
		if ($pos>0) {
			$companyDescription = substr($companyDescription, 0, $pos) . " ...";
		}
		//END summarize company description

		$companyContactPerson = $employerRow['first_name'] . " " . $employerRow['middle_name'] . " " . $employerRow['last_name'];
		$companyLocation = $employerRow['city1'] . ", " . $employerRow['province1'];
		$dateRegistered = getAcctInfo($companyUsername, "date-joined");
		$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . "<input type=\"checkbox\" class=\"checkBoxes\" name=\"checkBoxes\" id='" . $companyUsername. "'>" . "</td>";
		$content .= "<td><a onclick=\"viewSummary('$companyUsername');\" style=\"cursor:pointer;\">" . $companyName . "</a></td>";
		$content .= "<td>" . $acctType . "</td>";
		$content .= "<td>" . $companyDescription . "</td>";
		$content .= "<td>" . $companyContactPerson . "</td>";
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
										<span id=\"viewBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"viewBtn\" name=\"viewBtn\" href=\"#\" onclick=\"viewEmployerProfile();\"><i class=\"icon-file icon-white\"></i> View</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportEmployerData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
										<span id=\"promoteBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"promoteBtn\" name=\"promoteBtn\" href=\"#\" onclick=\"showChangeAccountQuestion();\"><i class=\"icon-user icon-white\"></i> Change Account Type</a></span>
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