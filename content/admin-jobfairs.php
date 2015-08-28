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

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

//GET ALL AVAILABLE JOB LOCATIONS
$jobLocations = getJobLocations();

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
							<div class="span12" id="titleBlock" name="titleBlock" style="margin-left: 5px;">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Title:</label>
									<div class="controls">
										<input type="text" id="title" name="title" class="span12"/>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="titleBlock" name="titleBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Venue:</label>
									<div class="controls">
										<input type="text" id="venue" name="venue" class="span12"/>
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
							<div class="span12" id="durationBlock" name="durationBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Duration</label>
									<div class="controls">
										<input type="number" class="span12" id="duration" name="duration" min="1" max="30">
									</div>
								</div>
							</div>
							<div class="span12" id="dateScheduledBlock" name="dateScheduledBlock" style="margin-top:15px; margin-bottom:15px;">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Date Scheduled</label>
									<div class="controls">
										<input type="text" class="span12" id="dateScheduled" name="dateScheduled" data-date-format="yyyy-mm-dd">
									</div>
								</div>
							</div>
							<div class="span12" id="jobFairStatusBlock" name="jobFairStatusBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Status:</label>
									<div class="controls">
										<select class="span12" id="jobFairStatus" name="jobFairStatus">
											<option></option>
											<option value="0">Open</option>
											<option value="1">Close</option>
										</select>
									  <p class="help-block"></p>
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
						<h4 class="content-heading2">Job Fairs</h4>
						<div class="tabbable">
							<ul class="nav nav-tabs nav-pills">
								<li class="active"><a href="#lA" data-toggle="tab">Manage Job Fairs</a></li>
								<li><a href="admin-add-jobfair.php">Add a Job Fair</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="lA">
									<div class="row-fluid">
										<span class="flash"></span>
										<span id="filtertags" name="filtertags" style="display:none;"></span>
										<div id="jobFairResults"><!-- print results here from process-admin-jobfairs.php--></div>
										<span class="flash2"></span>
									</div>
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
<!-- START JOB FAIR SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END JOB FAIR SUMMARY MODAL -->
<!-- START RESPONSE MODAL-->
<div id="responseModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">No Selected Records</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <div id="modalContent" style="text-align:center;">Please select job fair/s.</div>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" id="responseOKBtn" onclick="responseOK();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END RESPONSE MODAL -->
<!-- START CLOSE/REMOVE JOB FAIR MODAL-->
<div id="closeRemoveQuestionModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="closeRemoveQuestionTitle"></h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <div id="closeRemoveQuestionContent" style="text-align:center;">Are you sure you want to close selected job fair/s?</div>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span6 offset3">
			<button class="btn btn-primary span6" onclick="closeRemoveJobFair();">OK</button>
			<input type="hidden" id="closeRemoveVal" value=""/>
			<button class="btn btn-primary span6" data-dismiss="modal">Cancel</button>
		</div>
	</div>
  </div>
</div>
<!-- END CLOSE/REMOVE JOB FAIR MODAL -->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function(){
	changePagination('0');
	$("#jobLocation").select2();
	$("#jobFairStatus").select2();
	$("#dateScheduled").datepicker();
});

resultsFileURL = "reports-admin-jobfairs.php"
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
	        $("#jobFairResults").html(result);
	   }
	});
}

function responseOK(){
	$("#responseModal").modal('hide');
	$(".flash").hide();
}

function showTable(){
	var title = document.getElementById("title").value;
	var venue = document.getElementById("venue").value;
	var location = document.getElementById("jobLocation").value;
	var duration = document.getElementById("duration").value;
	var dateScheduled = document.getElementById("dateScheduled").value;
	var jobFairStatus = document.getElementById("jobFairStatus").value;
	var url = "reports-admin-jobfairs.php";
	var linkVariables = "";
	var linksSuffixes = new Array();
	var filters = new Array();

	if(title !== ""){
		filters.push(title + " (title)");
		linksSuffixes.push("title=" + title);
	}
	if(venue !== ""){
		filters.push(venue + " (venue)");
		linksSuffixes.push("venue=" + venue);
	}
	if(location !== ""){
		filters.push(location + " (location)");
		linksSuffixes.push("location=" + location);
	}
	if(duration !== ""){
		filters.push(duration + " (duration)");
		linksSuffixes.push("duration=" + duration);
	}
	if(dateScheduled !== ""){
		filters.push(dateScheduled + " (date scheduled)");
		linksSuffixes.push("dateScheduled=" + dateScheduled);
	}
	if(jobFairStatus !== ""){
		filters.push(jobFairStatus + " (job fair status)");
		linksSuffixes.push("jobFairStatus=" + jobFairStatus);
	}

	//create url
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
	updateFilterTags(filters);
	resultsFileURL = url;
	changePagination(0);
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

function clearFilter(){
	document.getElementById("filtertags").innerHTML= "";
	document.getElementById("dateScheduled").value="";
	document.getElementById("title").value="";
	document.getElementById("venue").value="";
	document.getElementById("duration").value="";
	$("#jobLocation").select2('val', 'All');
	$("#jobFairStatus").select2('val', 'All');
	window.location.reload();
}

function viewSummary(jobFairCode){
	var hr = new XMLHttpRequest();
	var url = "jobfair-summary.php?jobFairCode="+jobFairCode;
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

function viewJobFair(){
	var jobFairCode = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			jobFairCode = this.id;
		}
		else{
			jobFairCode += "-" + this.id;
		}
		count+=1;
	});

	if(count>1){
		modalTitle.innerHTML = "SELECT ONE JOB FAIR";
		modalContent.innerHTML = "<span class='text-error'>Error! You can view only one job fair at a time.</span>";
		$("#responseModal").modal('show');
	}
	else if(count===0){
		modalContent.innerHTML = "Please select the job fair you want to view.";
		$("#responseModal").modal('show');
	}
	else{
		viewSummary(jobFairCode);
	}
}

function editJobFair(jFCode){
	window.location.href= "admin-edit-jobfair.php?jobFairCode="+jFCode;
}

function closeRemoveJobFairQuery(decision){
	var applicantUsername = "";
	var count = 0;
	var closeRemoveQuestionModal = document.getElementById('closeRemoveQuestionModal');
	var closeRemoveQuestionTitle = document.getElementById('closeRemoveQuestionTitle');
	var closeRemoveQuestionContent = document.getElementById('closeRemoveQuestionContent');

	closedJobFairCodes = "";
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			jobFairCode = this.id;
		}
		else{
			jobFairCode += "-" + this.id;
		}
		count+=1;
	});

	if(count>0){
		$("#closeRemoveQuestionModal").modal('show');
		closeRemoveQuestionTitle.innerHTML = decision + " job fair?";
		closeRemoveQuestionContent.innerHTML = "Are you sure you want to " + decision + " selected job fair/s? (<span class='text-info'>" + count + "</span>)";	
		closedJobFairCodes = jobFairCode;
		var closeRemoveVal = document.getElementById('closeRemoveVal');
		closeRemoveVal.value = decision;  //substitute value to hidden variable for close/remove decision
	}
	else{
		$("#responseModal").modal('show');
	}
}

function closeRemoveJobFair(){
	$("#closeRemoveQuestionModal").modal('hide');
	var closeRemoveVal = document.getElementById('closeRemoveVal').value;
	var dataString = 'jobFairCodes=' + closedJobFairCodes + "&decision=" + closeRemoveVal;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	var loadingText = "";
	if(closeRemoveVal == "close"){
		loadingText = 'Closing job fairs ... <img src="./img/ajax-loader.gif" />';
	}
	else{
		loadingText = 'Removing job fairs ... <img src="./img/ajax-loader.gif" />';
	}

	//alert(closeRemoveVal);
	$.ajax({
	   type: "GET",
	   url: "admin-closeremove-jobfairs.php",
	   data: dataString,
	   cache: false,
	   beforeSend: function() {
         $(".flash").fadeIn(400).html(loadingText);
       },
	   success: function(result){
	   		$(".flash").hide();
	   		modalTitle.innerHTML = "Alert Message";
	   		modalContent.innerHTML = result;
			changePagination('0');
	   		$("#responseModal").modal('show');
	   }
	});
}

function exportJobFairData(){
	var jobFairCode = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			jobFairCode = this.id;
		}
		else{
			jobFairCode += "-" + this.id;
		}
		count+=1;
	});
	
	if(jobFairCode !== ""){
		var jobPosition = "All Job Fairs";
		var dataString = 'codes='+ jobFairCode;
		$.ajax({
		   type: "GET",
		   url: "job-fairs-sheet.php",
		   data: dataString,
		   cache: false,
		   beforeSend: function() {
	         $(".flash").fadeIn(400).html('Downloading list... <img src="./img/ajax-loader.gif" />');
	       },
		   success: function(result){
		   	$(".flash").hide();
		      window.location.href = "job-fairs-sheet.php?codes="+jobFairCode;
		   }
		});
	}
	else{
		modalTitle.innerHTML = "No Selected Record";
		modalContent.innerHTML = "Please select job fair/s.";
		$("#responseModal").modal('show');
	}
}

function checkAll(){
	var mainCheckbox = document.getElementById('selectAll');
	var viewBtn = document.getElementById('viewBtnBlock');
	if(mainCheckbox.value==0){
		$(".checkBoxes").prop("checked", true);
		mainCheckbox.value=1;
		viewBtn.style.display="none";
	}
	else{
		$(".checkBoxes").prop("checked", false);
		mainCheckbox.value=0;
		viewBtn.style.display="inline";
	}
}
</script>
<!-- JS scripts -->
</body>
</html>