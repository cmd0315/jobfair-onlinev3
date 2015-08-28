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

//GET ALL AVAILABLE JOB LOCATIONS
$jobLocations = getJobLocations();

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

//array of employer usernames for batch export
$employeeArray = "";
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
							<div class="span12" id="userBlock" name="userBlock" style="margin-left: 5px;">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Company Name:</label>
									<div class="controls">
										<input type="text" id="nameUser" name="nameUser" class="span12"/>
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
							<div class="span12" id="dateStartBlock" name="dateStartBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Date Start</label>
									<div class="controls">
										<input type="text" class="span12" id="applicationStartDate" name="applicationStartDate" data-date-format="yyyy-mm-dd">
									</div>
								</div>
							</div>
							<div class="span12" id="dateEndBlock" name="dateEndBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Date End</label>
									<div class="controls">
										<input type="text" class="span12" value="" id="applicationEndDate" name="applicationEndDate" data-date-format="yyyy-mm-dd">
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
								<li class="active"><a href="#lB" data-toggle="tab">Sign-ups (Employer)</a></li>
								<li><a href="admin-report-jposts.php" >Jobs Posted</a></li>
								<li><a href="admin-report-japps.php">Job Applications</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="lB">
									<span class="flash"></span>
									<span id="filtertags" name="filtertags" style="display:none;"></span>
									<div id="adminReportsResults">
										<!-- Print results from reports-admin-employers.php-->
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
<!-- START EMPLOYEE RESUME SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END EMPLOYEE RESUME SUMMARY MODAL -->
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
<!-- END ERROR MODAL -->
<!-- START PROMOTE USER MODAL-->
<div id="changeAccountTypeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END PROMOTE USER MODAL -->
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
	$("#applicationStartDate").datepicker();
	$("#applicationEndDate").datepicker();
	changePagination('0'); //start displaying results starting from first page
});

var resultsFileURL = "reports-admin-employers.php";
var currentURL = "";

function showTable(){
	var nameOfUser = document.getElementById("nameUser").value;
	var location = document.getElementById("jobLocation").value;
	var startDate = document.getElementById("applicationStartDate").value;
	var endDate = document.getElementById("applicationEndDate").value;
	var url = "reports-admin-employers.php";
	var linkVariables = "";
	var linksSuffixes = new Array();
	var filters = new Array();

	if(nameOfUser !== ""){
		filters.push(nameOfUser + " (Employer's name)");
		linksSuffixes.push("employerName=" + nameOfUser);
	}
	if(location !== ""){
		filters.push(location + " (location)");
		linksSuffixes.push("location=" + location);
	}
	if(startDate !== ""){
		filters.push(startDate + " (application start date)");
		linksSuffixes.push("startDate=" + startDate);
	}
	if(endDate !== ""){
		filters.push(endDate + " (application end date)");
		linksSuffixes.push("endDate=" + endDate);
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

function viewSummary(username){
	var hr = new XMLHttpRequest();
	var url = "company-profile.php?username="+username;
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

function clearFilter(){
	document.getElementById("filtertags").innerHTML= "";
	document.getElementById("applicationStartDate").value="";
	document.getElementById("applicationEndDate").value="";
	$("#jobLocation").select2('val', 'All');
	window.location.reload();
}

function viewEmployerProfile(){
	var employerUsername = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			employerUsername = this.id;
		}
		else{
			employerUsername += "-" + this.id;
		}
		count+=1;
	});

	if(count>1){
		//display error message2
		modalTitle.innerHTML = "SELECT ONE EMPLOYER";
		modalContent.innerHTML = "<span class='text-error'>Error! You can select only one employer.</span>";
		$("#errorModal").modal('show');
	}
	else if(count===0){
		modalContent.innerHTML = "Please select an employer whose profile you want to view.";
		$("#errorModal").modal('show');
	}
	else{
		viewSummary(employerUsername);
	}
}

function showChangeAccountQuestion(){
	var count = 0;
	var employerUsernames = "";
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			employerUsernames = this.id;
		}
		else{
			employerUsernames += "-" + this.id;
		}
		count+=1;
	});

	if(count>1){
		modalTitle.innerHTML = "SELECT ONE EMPLOYER";
		modalContent.innerHTML = "<span class='text-error'>Error! You can select only one employer.</span>";
		$("#errorModal").modal('show');
	}
	else if(count===0){
		modalTitle.innerHTML = "SELECT AN EMPLOYER";
		modalContent.innerHTML = "Please select an employer to promote";
		$("#errorModal").modal('show');
	}
	else{
		showAccountOptions(employerUsernames);
	}
}

function showAccountOptions(eUsername){
	var hr = new XMLHttpRequest();
	var url = "company-profile.php?username="+eUsername+"&changeAcctType=true";
	var modalDiv = document.getElementById('changeAccountTypeModal');
	hr.open("GET", url, false);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			modalDiv.innerHTML = return_data;
			$("#changeAccountTypeModal").modal('show');
		}
	}
	hr.send(null); 
}

function changeAccountType(eUsername){
	var hr = new XMLHttpRequest();
	var url = "process-change-accttype.php?username="+eUsername;
	var modalDiv = document.getElementById('changeAccountTypeModal');
	hr.open("GET", url, false);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			modalDiv.innerHTML = return_data;
			$("#changeAccountTypeModal").modal('show');
		}
	}
	hr.send(null); 
}

function exportEmployerData(){
	var employerUsernames = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			employerUsernames = this.id;
		}
		else{
			employerUsernames += "-" + this.id;
		}
		count+=1;
	});
	
	if(employerUsernames !== ""){
		var jobPosition = "All Employers";
		var dataString = 'usernames='+ employerUsernames + "&jobPosition=" + jobPosition;
		$.ajax({
		   type: "GET",
		   url: "employer-contact-sheet.php",
		   data: dataString,
		   cache: false,
		   beforeSend: function() {
	         $(".flash").fadeIn(400).html('Downloading list... <img src="./img/ajax-loader.gif" />');
	       },
		   success: function(result){
		   	$(".flash").hide();
		      window.location.href = "employer-contact-sheet.php?usernames="+employerUsernames+"&jobPosition="+jobPosition;
		   }
		});
	}
	else{
		modalTitle.innerHTML = "Data Not Yet Filtered";
		modalContent.innerHTML = "Please select employer/s.";
		$("#errorModal").modal('show');
	}
}

function checkAll(){
	var mainCheckbox = document.getElementById('selectAll');
	var promoteUserBlock = document.getElementById('promoteBtnBlock');
	if(mainCheckbox.value==0){
		$(".checkBoxes").prop("checked", true);
		mainCheckbox.value=1;
		promoteUserBlock.style.display="none";
	}
	else{
		$(".checkBoxes").prop("checked", false);
		mainCheckbox.value=0;
		promoteUserBlock.style.display="inline";
	}
}
</script>
<!-- JS scripts -->
</body>
</html>