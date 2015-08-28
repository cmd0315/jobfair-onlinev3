<?php
include './includes/functions.php';
include './includes/db.php';

/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//GET ALL AVAILABLE JOB LOCATIONS
$jobLocations = getJobLocations();
//GET ALL AVAILABLE EDUCATIONAL ATTAINMENTS
$educationalAttainments = getEducationalAttainments();

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

//get job position requests
$getInterestRequestsQuery = "SELECT * FROM interest WHERE status='1' ORDER BY date_added ASC";
$getInterestRequests = mysql_query($getInterestRequestsQuery) or die(mysql_error());
$interestRequestsCount = mysql_num_rows($getInterestRequests);
if($interestRequestsCount > 0){
	$interestBadge = "inline";
}
else{
	$interestBadge = "none";
}

//array of job position/interest requests for batch export
$interestRequestsArray = "";
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
<style type="text/css">
	.filterOption, input[type="number"]{
		font-size: 11.5px;
	}
	select[size] {
		height: auto;
		width: 100px;
	}
	#filterLabel{
		color: black;
		font-size: 11.5px;
		font-weight: normal;
	}
</style>
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
	<?php echo $searchBar=getSearchBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">JOB POSITIONS</h4>
						<div class="tabbable tabs-left">
							<ul class="nav nav-tabs nav-pills">
								<li><a href="edit-job-positions.php">Manage Job Positions</a></li>
								<li class="active"><a href="#lF" data-toggle="tab">Job Position Requests <span class="badge badge-important" style="display:<?php echo $interestBadge; ?>;"><?php echo $interestRequestsCount; ?></span></a></li>
							</ul>
							<div class="tab-content" >
								<div class="tab-pane active" id="lF">
									<div class="row-fluid">
										<div class="span6">
											<p><strong>Total Job Position Requests: <span style="color:#00c2ff;"><?php echo $interestRequestsCount;?></span></strong></p>
										</div>
									</div><br>
									<div class="row-fluid">
										<div class="span12">
											<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center;">
												<thead>
													<th>#</th>
													<th>Edit</th>
													<th>Job Position</th>
													<th>Employee Username</th>
													<th>Date Added</th>
												</thead>
												<tbody>
													<?php
														$count = 0;
														while($interestRequestsData = mysql_fetch_assoc($getInterestRequests)){
															$count += 1;
															$content = "";
															$interestRequestId = $interestRequestsData['id'];
															$jobPositionRequest = $interestRequestsData['position_id'];
															if(is_numeric($jobPositionRequest)){
																$jobPositionRequest = getJobPositionName($jobPositionRequest);
															}
															
															$employeeUsername = $interestRequestsData['employee_username'];
															$dateAdded = $interestRequestsData['date_added'];
															$dateAdded = date('Y-m-d', strtotime($dateAdded));
															$content.="<tr>
															<td>$count</td>
															<td style=\"cursor: pointer;\"><i class=\"icon-edit\" onclick=\"editJobPosition('$jobPositionRequest');\"></i></td>
															<td style=\"text-align:left;\"><span class=\"extra-label3\">$jobPositionRequest</span></td>
															<td style=\"text-align:left;\"><span class=\"extra-label3\">$employeeUsername</span></td>
															<td>$dateAdded</td>
															</tr>";
															$interestRequestsArray .= $interestRequestId . "-";
															echo $content;
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- hidden variable-->
				<input type="hidden" id="interestRequestsArray" name="interestRequestsArray" value="<?php echo $interestRequestsArray; ?>"/>
				<!--<div class="row-fluid">
					<div class="span5 offset7">
						<button type="button" id="exportBtn" name="exportBtn" class="btn btn-primary span12" onclick="exportJobPostData();"><i class="icon-download-alt icon-white"></i> Job Positions</button>
					</div>
				</div>-->
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $webAdminName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
				<div class="row-fluid">
					<div class="span10 offset1">
						<div class="alert alert-info">
							<p style="font-weight:bold;">Note:</p>
							<ul>
								<li>To display table results, key in value/s in the 'Filter by' box, then click 'Show'.</li>
								<li>To reset table results, click 'Clear', then click 'Show'.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>
<!-- START EDIT JOB POSITION MODAL-->
<div id="editJobPositionModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END EDIT JOB POSITION MODAL -->
<!-- START JOB POSITION ALREADY EXISTS MODAL-->
<div id="positionExistsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Position Already Exists!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center>Adding of job position <span id="existingJobPosition" name="existingJobPosition" class="text-info" style="font-weight:bold;"></span> is unsuccessful. <br/>Job position already exists!</center>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary extraBtn span12" data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END ADD JOB POSITION ALREADY EXISTS MODAL -->
<!-- START SUCCESSFUL ADDING OF JOB POSITION MODAL-->
<div id="positionAdded" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Position Request Added</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center>You have successfully added job position <span id="addedJobPosition" name="addedJobPosition" class="text-info" style="font-weight:bold;"></span>.</center>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary extraBtn span12" onclick="window.location.href='add-jobposition-requests.php';">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END SUCCESSFUL ADDING OF JOB POSITION MODAL -->
<!-- START DELETE JOB POSITION MODAL-->
<div id="askDeleteJobPosition" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Delete Job Position?</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center>Are you sure you want to delete job position <span id="deleteJobPositionName" name="deleteJobPositionName" class="text-info" style="font-weight:bold;"></span>?</center>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset2">
			<button class="btn extraBtn span12" data-dismiss="modal">CANCEL</button>
		</div>
		<div class="span4">
			<button class="btn btn-primary extraBtn span12" onclick="deleteJobPositionFinal();">DELETE</button>
		</div>
	</div>
  </div>
</div>
<!-- END DELETE JOB POSITION MODAL -->
<!-- START SUCCESSFUL DELETION OF JOB POSITION MODAL-->
<div id="positionDeleted" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Position Deleted</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center>You have successfully deleted job position <span id="deletedJobPosition" name="deletedJobPosition" class="text-info" style="font-weight:bold;"></span>.</center>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary extraBtn span12" onclick="window.location.href='add-jobposition-requests.php';">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END SUCCESSFUL DELETION OF JOB POSITION MODAL -->
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

function addJobPosition(){
	$("#addJobPositionModal").modal('show');
}

function editJobPosition(jobPosition){
	var hr = new XMLHttpRequest();
	var url = "job-positionrequest-summary.php?jobPosition="+jobPosition;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("editJobPositionModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#editJobPositionModal").modal('show');
}

function saveJobPosition(oldJobPosition){
	var jobPosition = document.getElementById("editJobPosition").value;
	var hr = new XMLHttpRequest();
	var url = "save-jobposition-request.php?jobPosition="+jobPosition+"&oldJobPosition="+oldJobPosition;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("addedJobPosition").innerHTML = jobPosition;
			$("#editJobPositionModal").modal('hide');
			$("#positionAdded").modal('show');
		}
	}
	hr.send(null); 
}

deleteJobPositionName = "";
function deleteJobPosition(jobPositionName){
	deleteJobPositionName = jobPositionName;
	document.getElementById("deleteJobPositionName").innerHTML = jobPositionName;
	$("#editJobPositionModal").modal('hide');
	$("#askDeleteJobPosition").modal('show');
}

function deleteJobPositionFinal(){
	var hr = new XMLHttpRequest();
	var url = "delete-jobposition-request.php?jobPositionName="+deleteJobPositionName;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("deletedJobPosition").innerHTML = deleteJobPositionName;
			$("#askDeleteJobPosition").modal('hide');
			$("#positionDeleted").modal('show');
		}
	}
	hr.send(null); 
}

function exportJobPostData(){
	var interestRequestsArray = document.getElementById('interestRequestsArray').value;
	window.location.href = "job-positions-sheet.php?jobPositionIDs="+interestRequestsArray;
}
</script>
<!-- JS scripts -->
</body>
</html>