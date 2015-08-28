<?php
include './includes/functions.php';
include './includes/db.php';

/**get session data**/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}
/**--get session data**/

/**get current date**/
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
/**--get current date**/

/**set profile box info**/
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";
/**--set profile box info**/

/**get filter values**/
$jobLocations = getJobLocations(); //get all available locations
$educationalAttainments = getEducationalAttainments(); //get all available educational attainments
/**--get filter values**/
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
			<div class="span2 offset1" id="filterDiv"> 
				<h4 id="e-dash-tag">FILTER BY:</h4>
				<div class="row-fluid form-inline">
					<div class="span12 well" id="filterContent">
						<div class="row-fluid">
							<div class="span12" id="userBlock" name="userBlock" style="margin-left: 5px;">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Applicant Name:</label>
									<div class="controls">
										<input type="text" id="nameUser" name="nameUser" class="span12"/>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="ageBlock" name="ageBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel" style="padding-bottom:6px;">Age Range:</label>
									<div class="controls controls-row">
									  <input type="number" class="span6" id="minAge" name="minAge" minlength="2" placeholder="min" min="18" max="60"/>
									  <input type="number" class="span6" id="maxAge" name="maxAge" minlength="2" placeholder="max" min="18" max="60" />
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="genderBlock" name="genderBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Gender:</label>
									<div class="controls">
										<select class="span12" id="gender" name="gender" style="margin-top:-10px;">
											<option class="filterOption" value=""></option>
											<option class="filterOption" value="M">Male</option>
											<option class="filterOption" value="F">Female</option>
											<option class="filterOption" value="">Any</option>
										</select>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="heightBlock" name="heightBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Height:</label>
									<div class="controls controls-row">
										<input class="span6" type="number" id="heightFt" name="heightFt" min="4" max="10" placeholder="ft." onchange="enableHeightIn();">
										<input class="span6" type="number" id="heightIn" name="heightIn" min="0" max="59" placeholder="in." readonly>
									</div>
								 </div>
							</div>
							<div class="span12" id="weightBlock" name="weightBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Weight:</label>
									<div class="controls controls-row">
										<input class="span12" type="number" id="weight" name="weight" min="60" max="300" placeholder="lbs.">
									</div>
								 </div>
							</div>
							<div class="span12" id="civilStatusBlock" name="civilStatusBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Status:</label>
									<div class="controls">
										<select class="span12" id="civilStat" name="civilStat">
											<option class="filterOption" value=""></option>
											<option class="filterOption" value="Single">Single</option>
											<option class="filterOption" value="Married">Married</option>
											<option class="filterOption" value="">Any</option>
										</select>
									  <p class="help-block"></p>
									</div>
								 </div>
							</div>
							<div class="span12" id="educationBlock" name="educationBlock">
								<div class="control-group">
									<label class="control-label" id="filterLabel">Education:</label>
									<div class="controls">
										<select class="span12" id="educAttainment" name="educAttainment">
											<?php
												echo "<option></option>";
												foreach($educationalAttainments as $eA) {
													echo "<option class=\"filterOption\" value=\"$eA\">$eA</option>";
												}
												echo "<option class=\"filterOption\" value=''>All Levels</option>";
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
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12 well" id="filterBtnGroup">
						<button type="button" class="btn btn-info extraBtn span6" id="clearBtn" name="clearBtn" onclick="clearFilter();">CLEAR</button>
						<button type="button" class="btn btn-info extraBtn span6" id="showBtn" name="showBtn" onclick="showTable();">SHOW</button>
					</div>
				</div>
			</div>
			<div class="span8" id="filterResults">
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">ADMIN REPORTS</h4>
						<div class="tabbable">
							<ul class="nav nav-tabs nav-pills">
								<li class="active"><a href="#lA" data-toggle="tab">Sign-ups (Applicant)</a></li>
								<li><a href="admin-report-sers.php" >Sign-ups (Employer)</a></li>
								<li><a href="admin-report-jposts.php">Jobs Posted</a></li>
								<li><a href="admin-report-japps.php">Job Applications</a></li>
							</ul>
							<div class="tab-content" >
								<div class="tab-pane active" id="lA">
									<span class="flash"></span>
									<span id="filtertags" name="filtertags" style="display:none;"></span>
									<div id="adminReportsResults">
										<!-- Print results from reports-admin-applicants.php-->
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
			  <div id="modalContent" style="text-align:center;">Please select applicant/s.</div>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" id="errorOKBtn"  data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END ERROR MODAL -->
<!-- START DELETE MODAL-->
<div id="deleteQuestionModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="deleteQModalTitle">Delete Applicant?</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <div id="deleteQModalContent" style="text-align:center;">Are you sure you want to delete selected applicants?</div>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span6 offset3">
			<button class="btn btn-primary span6" onclick="deleteApplicant();">OK</button>
			<button class="btn btn-primary span6" data-dismiss="modal">Cancel</button>
		</div>
	</div>
  </div>
</div>
<!-- END DELETE MODAL -->
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
	$("#gender").select2();
	$("#civilStat").select2();
	$("#educAttainment").select2();
	$("#jobLocation").select2();
	$("#applicationStartDate").datepicker();
	$("#applicationEndDate").datepicker();
	changePagination('0'); //start displaying results starting from first page
});

var resultsFileURL = "reports-admin-applicants.php";
var deleteApplicantUsernames = "";

function enableHeightIn(){
	var heightFt = document.getElementById("heightFt").value;
	if(heightFt !== ""){
		document.getElementById("heightIn").readOnly=false;
	}
	else{
		document.getElementById("heightIn").readOnly=true;
	}
}

function showTable(){
	var nameOfUser = document.getElementById("nameUser").value;
	var minAge = document.getElementById("minAge").value* 1;
	var maxAge = document.getElementById("maxAge").value* 1;
	var gender = document.getElementById("gender").value;
	var heightFt = document.getElementById("heightFt").value;
	var heightIn= document.getElementById("heightIn").value;
	var weight = document.getElementById("weight").value;
	var civilStatus = document.getElementById("civilStat").value
	var educAttainment = document.getElementById("educAttainment").value;;
	var location = document.getElementById("jobLocation").value;
	var startDate = document.getElementById("applicationStartDate").value;
	var endDate = document.getElementById("applicationEndDate").value;

	var url = "reports-admin-applicants.php";
	var linkVariables = "";
	var linksSuffixes = new Array();
	var filters = new Array();

	if(nameOfUser !== ""){
		filters.push(nameOfUser + " (Applicant's name)");
		linksSuffixes.push("applicantName=" + nameOfUser);
	}
	if(minAge > 17 && minAge < 61){
		filters.push(minAge + " (min. age)");
		linksSuffixes.push("minAge=" + minAge);
	}
	if(maxAge > 17 && maxAge < 61){
		filters.push(maxAge + " (max. age)");
		linksSuffixes.push("maxAge=" + maxAge);
	}
	if(gender !== ""){
		filters.push(gender + " (gender)");
		linksSuffixes.push("gender=" + gender);
	}
	if(heightFt !== "" && (heightFt >= 4 && heightFt <= 10)){
		if(heightIn !== ""){
			filters.push(heightFt + "'" + heightIn +'" (height)');
			linksSuffixes.push("height=" + heightFt + "'" + heightIn + '"');
		}
		else{
			filters.push(heightFt + "' (height)");
			linksSuffixes.push("height=" + heightFt);
		}
	}
	if(weight !== "" && weight >= 60){
		filters.push(weight +'" (weight)');
		linksSuffixes.push("weight=" + weight);
	}
	if(civilStatus !== ""){
		filters.push(civilStatus + " (civil status)");
		linksSuffixes.push("civilStatus=" + civilStatus);
	}
	if(educAttainment !== ""){
		filters.push(educAttainment + " (educ. attainment)");
		linksSuffixes.push("educationalAttainment=" + educAttainment);
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

function viewApplicantResume(){
	var applicantUsername = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			applicantUsername = this.id;
		}
		else{
			applicantUsername += "-" + this.id;
		}
		count+=1;
	});

	if(count>1){
		//display error message2
		modalTitle.innerHTML = "SELECT ONE APPLICANT";
		modalContent.innerHTML = "<span class='text-error'>Error! You can select only one applicant.</span>";
		$("#errorModal").modal('show');
	}
	else if(count===0){
		modalContent.innerHTML = "Please select an applicant whose resume you want to view.";
		$("#errorModal").modal('show');
	}
	else{
		viewResumeEmp(applicantUsername);
	}
}

function clearFilter(){
	document.getElementById("filtertags").innerHTML= "";
	document.getElementById("minAge").value="";
	document.getElementById("maxAge").value="";
	document.getElementById("heightFt").value="";
	document.getElementById("heightIn").value="";
	document.getElementById("weight").value="";
	document.getElementById("applicationStartDate").value="";
	document.getElementById("applicationEndDate").value="";
	$("#gender").select2('val', 'All');
	$("#civilStat").select2('val', 'All');
	$("#educAttainment").select2('val', 'All');
	$("#jobLocation").select2('val', 'All');
	window.location.reload();
}

function deleteApplicantQuery(){
	var applicantUsername = "";
	var count = 0;
	var deleteQModalContent = document.getElementById('deleteQModalContent');
	deleteApplicantUsernames = "";
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			applicantUsername = this.id;
		}
		else{
			applicantUsername += "-" + this.id;
		}
		count+=1;
	});

	if(count>0){
		$("#deleteQuestionModal").modal('show');
		deleteQModalContent.innerHTML = "Are you sure you want to delete selected applicants? (<span class='text-info'>" + count + "</span>)";	
		deleteApplicantUsernames = applicantUsername;
	}
	else{
		$("#errorModal").modal('show');
	}
}

function deleteApplicant(){
	$("#deleteQuestionModal").modal('hide');
	var dataString = 'usernames='+ deleteApplicantUsernames;
	var deleteSuccessfulModalTitle = document.getElementById('modal-title');
	var deleteSuccessfulModalContent = document.getElementById('modalContent');
	var deleteSuccessful = 1;
	$.ajax({
	   type: "GET",
	   url: "admin-delete-applicants.php",
	   data: dataString,
	   cache: false,
	   beforeSend: function() {
         $(".flash").fadeIn(400).html('Deleting applicants ... <img src="./img/ajax-loader.gif" />');
       },
	   success: function(result){
	   		$(".flash").hide();
	   		changePagination('0');
	   		deleteSuccessful = 0;
	   }
	});
}

function exportApplicantData(){
	var applicantUsername = "";
	var count = 0;
	var modalTitle = document.getElementById('modal-title');
	var modalContent = document.getElementById('modalContent');
	$("input:checkbox[name=checkBoxes]:checked").each(function()
	{
		if(count === 0){
			applicantUsername = this.id;
		}
		else{
			applicantUsername += "-" + this.id;
		}
		count+=1;
	});
	
	if(applicantUsername !== ""){
		var jobPosition = "All Applicants";
		var dataString = 'usernames='+ applicantUsername;
		$.ajax({
		   type: "GET",
		   url: "applicant-contact-sheet.php",
		   data: dataString,
		   cache: false,
		   beforeSend: function() {
	         $(".flash").fadeIn(400).html('Downloading list... <img src="./img/ajax-loader.gif" />');
	       },
		   success: function(result){
		   	$(".flash").hide();
		      window.location.href = "applicant-contact-sheet.php?usernames="+applicantUsername;
		   }
		});
		//window.location.href = "applicant-contact-sheet.php?usernames="+applicantUsername+"&jobPosition="+jobPosition;
	}
	else{
		modalTitle.innerHTML = "Data Not Yet Filtered";
		modalContent.innerHTML = "Please select applicant/s.";
		$("#errorModal").modal('show');
	}
}

function checkAll(){
	var mainCheckbox = document.getElementById('selectAll');
	var viewBtn = document.getElementById('viewBtnBlock');
	var deleteBtn = document.getElementById('deleteBtn');
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