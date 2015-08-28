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
//GET ALL AVAILABLE JOB LOCATIONS
$jobPositions = getJobPositions();

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

//array of job posts codes for batch export
$jobApplicationsArray = "";
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
			<div class="span2 offset1"> 
				<h4 id="e-dash-tag">FILTER BY:</h4>
				<div class="row-fluid form-inline">
					<div class="span12 well" style="background-color: #cfd3d7;">
						<div class="row-fluid">
							<div class="span12" id="jobPositionBlock" name="jobPositionBlock" style="margin-left: 5px;">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Job Position:</label>
									<div class="controls">
										<select class="span12" id="jobPosition" name="jobPosition" style="margin-top:-10px;">
											<?php
												echo "<option></option>";
												foreach($jobPositions as $jP) {
													echo "<option class=\"filterOption\" value=\"$jP\">$jP</option>";
												}
												echo "<option value=''>All POSITIONS</option>";
											?>
										</select>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="locationBlock" name="locationBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Location:</label>
									<div class="controls">
										<select class="span12" id="jobLocation" name="jobLocation" style="margin-top:-10px;">
											<?php
												echo "<option></option>";
												foreach($jobLocations as $jL) {
													echo "<option class=\"filterOption\" value=\"$jL\">$jL</option>";
												}
												echo "<option value=''>All Locations</option>";
											?>
										</select>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="statusBlock" name="statusBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Status:</label>
									<div class="controls">
										<select class="span12" id="status" name="status" style="margin-top:-10px;">
											<option></option>
											<option class="filterOption" value="Y">With Applicants</option>
											<option class="filterOption" value="N">Without Applicants</option>
											<option class="filterOption" value="">All Applications</option>
										</select>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="dateStartBlock" name="dateStartBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Date Start</label>
									<div class="controls">
										<input type="text" class="span12" id="postStartDate" name="postStartDate" data-date-format="yyyy-mm-dd">
									</div>
								</div>
							</div>
							<div class="span12" id="dateEndBlock" name="dateEndBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Date End</label>
									<div class="controls">
										<input type="text" class="span12" value="" id="postEndDate" name="postEndDate" data-date-format="yyyy-mm-dd">
									</div>
								 </div>
							</div>
						</div>
						<div class="row-fluid" style="height:10px;"></div>
						<div class="row-fluid">
							<div class="span12">
								<button type="button" class="btn btn-info extraBtn span6" id="clearBtn" name="clearBtn" onclick="clearFilter();">CLEAR</button>
								<button type="button" class="btn btn-info extraBtn span6" id="showBtn" name="showBtn" onclick="showTable();">SHOW</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span8">
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">ADMIN REPORTS</h4>
						<div class="tabbable">
							<ul class="nav nav-tabs nav-pills">
								<li><a href="admin-reports.php">Sign-ups (Applicant)</a></li>
								<li><a href="admin-report-sers.php">Sign-ups (Employer)</a></li>
								<li><a href="admin-report-jposts.php">Jobs Posted</a></li>
								<li class="active"><a href="#lD" data-toggle="tab">Job Applications</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="lD">
									<span class="flash"></span>
									<span id="filtertags" name="filtertags" style="display:none;"></span>
									<div id="adminReportsResults">
										<!-- Print results from reports-admin-jobapplications.php-->
									</div>
									<span class="flash2"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END SUMMARY MODAL -->
<!-- START ERROR MODAL-->
<div id="errorModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Data Not Yet Filtered</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="modalContent" style="text-align:center;">Please filter the data first.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END ERROR MODAL-->
<!-- START APPLICANTS LIST MODAL-->
<div id="applicantsListModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END APPLICANTS LIST MODAL -->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script>
 $(document).ready(function(){
	$("#jobLocation").select2();
	$("#jobPosition").select2();
	$("#status").select2();
	$("#postStartDate").datepicker();
	$("#postEndDate").datepicker();
	changePagination('0'); //start displaying results starting from first page
});

var resultsFileURL = "reports-admin-jobapplications.php";
var currentURL = "";

function showTable(){
	var location = document.getElementById("jobLocation").value;
	var position = document.getElementById("jobPosition").value;
	var jobStatus = document.getElementById("status").value;
	var startDate = document.getElementById("postStartDate").value;
	var endDate = document.getElementById("postEndDate").value;

	var url = "reports-admin-jobapplications.php";
	var linkVariables = "";
	var linksSuffixes = new Array();
	var filters = new Array();

	if(location !== ""){
		filters.push(location + " (location)");
		linksSuffixes.push("location=" + location);
	}
	if(position !== ""){
		filters.push(position + " (job position)");
		linksSuffixes.push("jobPosition=" + position);
	}
	if(startDate !== ""){
		filters.push(startDate + " (post start date)");
		linksSuffixes.push("startDate=" + startDate);
	}
	if(endDate !== ""){
		filters.push(endDate + " (post end date)");
		linksSuffixes.push("endDate=" + endDate);
	}
	if(jobStatus !== ""){
		if(jobStatus === "Y")
			jobStatus = "With Applicants";
		else
			jobStatus = "Without Applicants";
		filters.push(jobStatus + " (status)");
		linksSuffixes.push("jobStatus=" + jobStatus);
	}

	//START create url
	if(linksSuffixes.length >= 1){
		for(var j=0; j<=linksSuffixes.length; j++){
			if(linksSuffixes[j]==undefined){
				url += "";
			}
			else{
				if(j==0){
					url += "?" + linksSuffixes[j];
				}
				else{
					url += "&" + linksSuffixes[j];
				}
			}
		}
	}
	//END create url

	updateFilterTags(filters);
	resultsFileURL = url;
	changePagination(0);
}

function changePagination(pageNum){
	$(".flash").show();
	$(".flash").fadeIn(400).html('Please wait while data is being processed. <img src="./img/ajax-loader.gif" />');
	
	if(pageNum!=0){
		$(".flash2").show();
		$(".flash2").fadeIn(400).html('Please wait while data is being processed. <img src="./img/ajax-loader.gif" />');
	}

	var dataString = 'pageNum='+ pageNum;
	$.ajax({
	   type: "GET",
	   url: resultsFileURL,
	   data: dataString,
	   cache: false,
	   success: function(result){
	   		$(".flash").hide();
	   		$(".flash2").hide();
	        $("#adminReportsResults").html(result);
	   }
	});
}

function updateFilterTags(filterArray){
	var filtertags = "";
	if(filterArray.length > 0){
		for(i=0; i<= filterArray.length; i++){
			if(filterArray[i] !== undefined){
				if(i === 0){
					filtertags += filterArray[i];
				}
				else{
					filtertags +=  ", " + filterArray[i];
				}
			}
		}
		filterTags = document.getElementById("filtertags");
		filterTags.style.display = "block";
		filterTags.innerHTML= "<div class=\"alert alert-info\"><strong><i>Filter Tags: </i></strong> " + filtertags+"</div>";
	}
}

function viewSummary(jobPostCode){
	var hr = new XMLHttpRequest();
	var url = "admin-display-jobdetails.php?jobPostCode="+jobPostCode;
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

function viewApplicants(jobPostCode){
	var hr = new XMLHttpRequest();
	var url = "admin-display-jobapplicants.php?jobPostCode="+jobPostCode;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("applicantsListModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#applicantsListModal").modal('show');
}

function clearFilter(){
	document.getElementById("filtertags").innerHTML= "";
	$("#jobPosition").select2('val', 'All');
	$("#jobLocation").select2('val', 'All');
	$("#status").select2('val', 'All');
	document.getElementById("postStartDate").value="";
	document.getElementById("postEndDate").value="";
	window.location.reload();
}

function viewJobPostInfo(){
	var jobPostCode = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			jobPostCode = this.id;
		}
		else{
			jobPostCode += "-" + this.id;
		}
		count+=1;
	});

	if(count>1){
		//display error message2
		modalTitle.innerHTML = "SELECT ONE JOB POST";
		modalContent.innerHTML = "<span class='text-error'>Error! You can select only one job post.</span>";
		$("#errorModal").modal('show');
	}
	else if(count===0){
		modalContent.innerHTML = "Please select a job post to view.";
		$("#errorModal").modal('show');
	}
	else{
		viewSummary(jobPostCode);
	}
}

function exportJobApplicationsData(){
	var jobPostCodes = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			jobPostCodes = this.id;
		}
		else{
			jobPostCodes += "-" + this.id;
		}
		count+=1;
	});
	
	if(jobPostCodes !== ""){
		var dataString = 'codes='+ jobPostCodes;
		$.ajax({
		   type: "GET",
		   url: "job-applications-sheet.php",
		   data: dataString,
		   cache: false,
		   beforeSend: function() {
	         $(".flash").fadeIn(400).html('Downloading list... <img src="./img/ajax-loader.gif" />');
	       },
		   success: function(result){
		   	$(".flash").hide();
		      window.location.href = "job-applications-sheet.php?codes="+jobPostCodes;
		   }
		});
	}
	else{
		modalTitle.innerHTML = "Data Not Yet Filtered";
		modalContent.innerHTML = "Please select job post/s.";
		$("#errorModal").modal('show');
	}
}

function checkAll(){
	var mainCheckbox = document.getElementById('selectAll');
	if(mainCheckbox.value==0){
		$(".checkBoxes").prop("checked", true);
		mainCheckbox.value=1;
	}
	else{
		$(".checkBoxes").prop("checked", false);
		mainCheckbox.value=0;
	}
}
</script>
<!-- JS scripts -->
</body>
</html>