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
$fullName = getEmployeeData($username, "full-name");
$age = getEmployeeData($username, "age");
$address = getEmployeeData($username, "address");
$profilePic = getEmployeeData($username, "profile_pic");
	
if($query !== ""){
	$searchJobPostNumQuery = "SELECT Count(*) FROM `job_post` WHERE job_position ORDER BY `job_position`, 'location_id' ASC";
	$searchJobPostNum = mysql_result(mysql_query($searchJobPostNumQuery),0);
	$searchJobPostQuery = "SELECT * FROM `job_post` WHERE job_position ORDER BY `job_position`, 'location_id' ASC";
	$searchJobPost = mysql_query($searchJobPostQuery);
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
	<?php echo $header=getHeader(); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getApplicantNavBar(); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1"> 
				<h4 id="e-dash-tag" style="font-style:italic;">SEARCH RESULTS for: <span style="color:black;"><?php echo $query;?></span></h4>
				<div class="row-fluid">
					<div class="span12 well">
						<?php
							//start job post table
							$jobPostCount = 0;
							while ($row = mysql_fetch_assoc($searchJobPost)){
								$jobPostNum = $row['code'];
								$jobPosition = getJobPost($jobPostNum, "job-pos-name");
								$jobEmployerName = getJobPost($jobPostNum, "company-name");
								$jobLocation = getJobPost($jobPostNum, "location");
								$jobLocation2 = explode(",", $jobLocation);
								$jobCity = $jobLocation2[0];
								$jobProvince = $jobLocation2[1];
								$locationIdQuery = "SELECT location_id FROM location_posts WHERE post_code='$jobPostNum'";
								$getLocationId= mysql_query($locationIdQuery) or die(mysql_error());
								$locationId = mysql_result($getLocationId, 0);
								
								$query2 = strtoupper($query);
								if(strpos($jobPosition, $query) != FALSE || strpos($jobPosition, $query2) != FALSE || $jobPosition == $query || strcasecmp($jobPosition, $query) == 0 || strpos($jobCity, $query) != FALSE || $jobCity == $query || strcasecmp($jobCity, $query) == 0 || strpos($jobProvince, $query) != FALSE || $jobProvince == $query || strcasecmp($jobProvince, $query) == 0){
									$jobPostCount += 1;
									$table2 .=<<<EOT
										<tr>
											<td><a onclick="checkJob('$jobPostNum', '$locationId');" style="cursor:pointer; font-weight:bold;">$jobPosition<a></td>
											<td>$jobEmployerName</td>
											<td>$jobLocation</td>
										</tr>
EOT;
								}
							}
							if($jobPostCount > 0){
								if($jobPostCount > 1){
									echo "<h4 style=\"color:black;\">Job Posts</h4>";
								}
								else
									echo "<h4 style=\"color:black;\">Job Post</h4>";
								
								$table1 =<<<EOT
								<div class="row-fluid">
									<table class="display table-striped table-hover table-condensed record_table" width="100%"  id="record_table" style="text-align:center;">
										<thead>
											<th>Job Position</th>
											<th>Company Name</th>
											<th>Job Site</th>
										</thead>
										<tbody>
EOT;

								$table3 .=<<<EOT
									</tbody>
								</table>
EOT;

								$table = $table1 . $table2 . $table3  . "</div><br/>";
								echo $table;
							}
							
							if($searchApplicantNum == 0 && $searchEmployerNum == 0 && $jobPostCount == 0){
								echo "<h4 style=\"color:black;\">No results found for query.</h4>";
							}
						?>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getProfileBox($status, $fullName, $age, $address, $profilePic);?>
				<!-- END PROFILE BOX -->
			</div>
		</div>
		<!-- hidden variables-->
		<input type="hidden" name="username" id="username" value="<?php echo $username;?>"/>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>

<!-- START JOB POST MODAL-->
<div id="jobPostModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END JOB POST MODAL -->>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/datatable/js/datatables.responsive.js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51e2c4c131144687"></script>
<script>
 $(document).ready(function(){
	$('#record_table').dataTable();
});

function checkJob(jobPostCode, jobLocationId){
	var hr = new XMLHttpRequest();
	var shareLink = "index.php?jobLocationId="+jobLocationId;
    var url = "display-job-details.php?jobPostCode="+jobPostCode+"&shareLink="+shareLink;
    hr.open("GET", url, true);
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("jobPostModal").innerHTML = return_data;
			addthis.toolbox('.addthis_toolbox');
			addthis.counter('.addthis_counter');
	    }
    }
    hr.send(null); 
	$("#jobPostModal").modal('show');
}

function applyToJob(jobPostCode){
	var username = document.getElementById("username").value;
	var hr = new XMLHttpRequest();
    var url = "check-login-status.php?username="+username+"&jobPostCode="+jobPostCode;
    hr.open("GET", url, true);
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			if(return_data == "Apply Job"){
				window.location.href = "confirm-apply-job.php";
			}
			else if(return_data == "Invalid"){
				$("#jobPostModal").modal('hide');
				$('#invalidAccountModal').modal('show');
			}
			else{
				window.location.href = "login.php?session=false&applyingjob=true";
			}
	    }
    }
    hr.send(null); 
}
</script>
<!-- JS scripts -->
</body>
</html>