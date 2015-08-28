<?php
include './includes/functions.php';
include './includes/db.php';

extract($_GET);
/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}
//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");
	
if($query !== ""){
	$searchApplicantNumQuery = "SELECT Count(*) FROM `employee` WHERE  Concat(first_name, space(1), middle_name, space(1), last_name) LIKE '%$query%' OR Concat(city1, space(1), province1) LIKE '%$query%' ORDER BY `first_name` ASC";
	$searchApplicantNum = mysql_result(mysql_query($searchApplicantNumQuery),0);
	$searchApplicantQuery = "SELECT * FROM `employee` WHERE  Concat(first_name, space(1), middle_name, space(1), last_name) LIKE '%$query%' OR Concat(city1, space(1), province1) LIKE '%$query%'  ORDER BY `first_name` ASC";
	$searchApplicant = mysql_query($searchApplicantQuery);

	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'SEARCH', '$query')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- START META -->
<?php echo $meta=getMeta(); ?>
<!-- END META -->
<!-- CSS scripts -->
<link rel="stylesheet" href="./css/bootstrap.css"/>
<link rel="stylesheet" href="./css/select2.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="./js/datatable/css/demo_table.css">
<link rel="stylesheet" href="./js/datatable/css/datatables.responsive.css"/>
<!-- CSS scripts -->
</head>
<body>
<!-- START GTM  -->
<?php echo $gtm=getGTM(); ?>
<!-- END GTM-->
<div class="wrap">
	<!-- START HEADER -->
	<?php echo $header = getHeader('Default'); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getEmployerNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1"> 
				<h4 id="e-dash-tag" style="font-style:italic;">SEARCH RESULTS for: <span style="color:black;"><?php echo $query;?></span></h4>
				<div class="row-fluid">
					<div class="span12 well">
						<?php
							if($searchApplicantNum > 0){
								if($searchApplicantNum > 1){
									echo "<h4 style=\"color:black;\">Applicants</h4>";
								}
								else
									echo "<h4 style=\"color:black;\">Applicant</h4>";
									
								$table =<<<EOT
								<div class="row-fluid">
									<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center;">
										<thead>
											<th>Name</th>
											<th>Username</th>
											<th>Location</th>
										</thead>
										<tbody>
EOT;
									
								while ($row = mysql_fetch_assoc($searchApplicant)){
									$applicantUsername = $row['username'];
									$applicantName = getEmployeeData($applicantUsername, "full-name");
									$applicantLocation = getEmployeeData($applicantUsername, "address");
									$table .=<<<EOT
										<tr>
											<td><a onclick="viewSummary('$applicantUsername');" style="cursor:pointer; font-weight:bold;">$applicantName<a></td>
											<td>$applicantUsername</td>
											<td>$applicantLocation</td>
										</tr>
EOT;
								}
								$table .=<<<EOT
									</tbody>
								</table>
EOT;
								echo $table . "</div><br/>";
							}
							else{
								echo "<h4 style=\"color:black;\">No results found for query.</h4>";
							}
						?>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $coName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
			</div>
		</div>
		<!-- hidden variables-->
		<input type="hidden" name="username" id="username" value="<?php echo $username;?>"/>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>

<!-- START EMPLOYEE RESUME SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END EMPLOYEE RESUME SUMMARY MODAL -->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/datatable/js/datatables.responsive.js"></script>
<script>
 $(document).ready(function(){
	$('#record_table').dataTable();
});

function viewSummary(applicantUsername){
	var hr = new XMLHttpRequest();
	var url = "employee-resume-summary.php?username="+applicantUsername;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("summaryModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#summaryModal").modal('show');
}

function viewResumeEmp(username){
	window.open("view-resume.php?username="+username, '_blank');
}
</script>
<!-- JS scripts -->
</body>
</html>