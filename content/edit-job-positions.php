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

$jobPositionsQuery = "SELECT * FROM position WHERE status='0' ORDER BY name ASC";
$getJobPositions = mysql_query($jobPositionsQuery) or die(mysql_error());
$jobPositionsCount = mysql_num_rows($getJobPositions);
	
//array of job positions for batch export
$jobPositionsArray = "";

//get job position requests
$getInterestRequestsQuery = "SELECT * FROM interest WHERE status='1' ";
$getInterestRequests = mysql_query($getInterestRequestsQuery) or die(mysql_error());
$interestRequestsCount = mysql_num_rows($getInterestRequests);
if($interestRequestsCount > 0){
	$interestBadge = "inline";
}
else{
	$interestBadge = "none";
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
								<li class="active"><a href="#lG" data-toggle="tab">Manage Job Positions</a></li>
								<li><a href="add-jobposition-requests.php">Job Position Requests <span class="badge badge-important" style="display:<?php echo $interestBadge; ?>;"><?php echo $interestRequestsCount; ?></span></a></li>
							</ul>
							<div class="tab-content" >
								<div class="tab-pane active" id="lG">
									<div class="row-fluid">
										<div class="span6">
											<p><strong>Total Job Positions: <span style="color:#00c2ff;"><?php echo $jobPositionsCount;?></span></strong></p>
										</div>
										<div class="span3 offset3">
											<button type="button" class="btn btn-info span12" onclick="addJobPosition();"><i class="icon-plus icon-white"></i> JOB POSITION</button>
										</div>
									</div><br>
									<div class="row-fluid">
										<div class="span12">
											<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center;">
												<thead>
													<th>#</th>
													<th>Edit</th>
													<th>Job Position</th>
													<th>Date Added</th>
												</thead>
												<tbody>
													<?php
														$count = 0;
														while($jobPositionsData = mysql_fetch_assoc($getJobPositions)){
															$count += 1;
															$content = "";
															$jobPositionId = $jobPositionsData['id'];
															$jobPositionName = $jobPositionsData['name'];
															$dateAdded = $jobPositionsData['date_added'];
															$dateAdded = date('Y-m-d', strtotime($dateAdded));
															$content.="<tr>
															<td>$count</td>
															<td style=\"cursor: pointer;\"><i class=\"icon-edit\" onclick=\"editJobPosition('$jobPositionId');\"></i></td>
															<td style=\"text-align:left;\"><span class=\"extra-label3\">$jobPositionName</span></td>
															<td>$dateAdded</td>
															</tr>";
															$jobPositionsArray .= $jobPositionId . "-";
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
				<input type="hidden" id="jobPositionsArray" name="jobPositionsArray" value="<?php echo $jobPositionsArray; ?>"/>
				<div class="row-fluid">
					<div class="span5 offset7">
						<button type="button" id="exportBtn" name="exportBtn" class="btn btn-primary span12" onclick="exportJobPostData();"><i class="icon-download-alt icon-white"></i> Job Positions</button>
					</div>
				</div>
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
<!-- START ADD JOB POSITION MODAL-->
<div id="addJobPositionModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Add New Job Position</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<input type="text" id="newJobPosition" name="newJobPosition" class="span12"/>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset2">
			<button class="btn extraBtn span12" data-dismiss="modal">CANCEL</button>
		</div>
		<div class="span4">
			<button class="btn btn-primary extraBtn span12" onclick="saveNewJobPosition();">SAVE</button>
		</div>
	</div>
  </div>
</div>
<!-- END ADD JOB POSITION MODAL -->
<!-- START JOB POSITION ALREADY EXISTS MODAL-->
<div id="positionExistsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Position Already Exists!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center><span class="text-error">Adding of job position <span id="existingJobPosition" name="existingJobPosition" class="text-info" style="font-weight:bold;"></span> is unsuccessful. <br/>Job position already exists!</span></center>
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
	<h4 class="content-heading4" id="modal-title">Job Position Added</h4>
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
			<button class="btn btn-primary extraBtn span12" onclick="window.location.href='edit-job-positions.php';">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END SUCCESSFUL ADDING OF JOB POSITION MODAL -->
<!-- START SUCCESSFUL EDITING OF JOB POSITION MODAL-->
<div id="positionEdited" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Position Edited</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			<center>Job position <span id="origJobPositionName" name="origJobPositionName" style="font-weight:bold;"></span> is now known as <span id="newJobPositionName" name="newJobPositionName" class="text-info" style="font-weight:bold;"></span>.</center>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary extraBtn span12" onclick="window.location.href='edit-job-positions.php';">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END SUCCESSFUL EDITING OF JOB POSITION MODAL -->
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
			<button class="btn btn-primary extraBtn span12" onclick="window.location.href='edit-job-positions.php';">OK</button>
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

function editJobPosition(jobPositionId){
	var hr = new XMLHttpRequest();
	var url = "job-position-summary.php?jobPositionId="+jobPositionId;
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

function saveJobPosition(jobPositionId){
	var jobPosition = document.getElementById("editJobPosition").value;
	var hr = new XMLHttpRequest();
	var url = "save-job-position.php?jobPositionID="+jobPositionId+"&jobPosition="+jobPosition;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			return_data = return_data.split("-");
			var response = return_data[0];
			var oldJobPosition = return_data[1];
			$("#editJobPositionModal").modal('hide');
			if(response === "1"){
				document.getElementById("origJobPositionName").innerHTML = oldJobPosition;
				jobPosition = jobPosition.toUpperCase();
				document.getElementById("newJobPositionName").innerHTML = jobPosition;
				$("#positionEdited").modal('show');
			}
			else{
				$("#positionExistsModal").modal('show');
			}
		}
	}
	hr.send(null); 
}

function saveNewJobPosition(){
	var jobPosition = document.getElementById("newJobPosition").value;
	var hr = new XMLHttpRequest();
	var url = "save-new-jobposition.php?jobPosition="+jobPosition;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			$("#addJobPositionModal").modal('hide');
			//check if position already exists
			if(return_data === "0"){
				document.getElementById("existingJobPosition").innerHTML = jobPosition;
				$("#positionExistsModal").modal('show');
			}
			else{
				jobPosition = jobPosition.toUpperCase();
				document.getElementById("addedJobPosition").innerHTML = jobPosition;
				$("#positionAdded").modal('show');
			}
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
	var url = "delete-job-position.php?jobPositionName="+deleteJobPositionName;
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
	var jobPositionsArray = document.getElementById('jobPositionsArray').value;
	window.location.href = "job-positions-sheet.php?jobPositionIDs="+jobPositionsArray;
}
</script>
<!-- JS scripts -->
</body>
</html>