<?php
include './includes/functions.php';
include './includes/db.php';
	
//extract variables in link
extract($_GET);
$content = "";

$employersQuery = "SELECT employer.username FROM employer INNER JOIN account ON employer.username = account.username  ORDER BY account.date_joined DESC";
$getEmployers = mysql_query($employersQuery) or die(mysql_error());
$employerArray = ""; // container for exporting of reports
$employerArr = array();


while($employerData = mysql_fetch_assoc($getEmployers)){
	$username = $employerData['username'];
	array_push($employerArr, $username);
}

$employerArr = array_unique($employerArr);
if($location != ""){
	$employerArr = filterEmployerByLocation($employerArr, $location);
	//echo count($applicantArr);
}

if($dayView != ""){
	$employerArr = filterByDayView($employerArr, $dayView);
	//echo count($applicantArr);
}

$arrayCount = count($employerArr);

$content .=<<<EOT
<div class="row-fluid">
	<div class="span12">
		<p><strong>Total Employers: <span style="color:#00c2ff;">$arrayCount</span></strong></p>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center; font-size:11px;">
			<thead>
				<th>#</th>
				<th>Company Name</th>
				<th>Company Desc</th>
				<th>Location</th>
				<th>Date Registered</th>
			</thead>
			<tbody>
EOT;
						$count = 0;
						foreach($employerArr as $username){
							$count += 1;
							$companyName = getEmployerData($username, "company-name");
							$companyDesc = getEmployerData($username, "company_desc");
							$address = getEmployerData($username, "address");
							$dateRegistered = getAcctInfo($username, "date-joined");
							$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
					
							$content.="<tr onclick=\"viewSummary('$username');\">
							<td>$count</td>
							<td style=\"text-align:left; cursor:pointer;\"><span class=\"extra-label3\">$companyName</span></td>
							<td style=\"cursor:pointer;\">$companyDesc</td>
							<td style=\"cursor:pointer;\">$address</td>
							<td style=\"cursor:pointer;\">$dateRegistered</td>
							</tr>";
							$employerArray .= $username . "-";
						}
			$content .=<<<EOT
			</tbody>
		</table>
	</div>
</div>
EOT;
$arr = array($content, $employerArray);
echo json_encode($arr);
?>