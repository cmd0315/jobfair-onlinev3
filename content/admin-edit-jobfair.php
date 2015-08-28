<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/generate-code.php';

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

$jobPositions = getJobPositions(); // job positions
$availableLocations= getJobLocationsWithID();//job locations

if(isset($_GET['jobFairCode'])){
	$jobFairCode = $_GET['jobFairCode'];
	$getJobFairQuery = "SELECT * FROM job_fair WHERE code='$jobFairCode'";
	$getJobFair = mysql_query($getJobFairQuery) or die(mysql_error());
	$jobFairCount = mysql_num_rows($getJobFair);

	if($jobFairCount > 0){
		while($jobFairRow = mysql_fetch_assoc($getJobFair)){
			$jobFairCode = $jobFairRow['code'];
		
			$title = $jobFairRow['title'];
			$title = ucwords((strtolower($title)));
			
			$venue = $jobFairRow['establishment_name'];
			$street = $jobFairRow['street'];
			$street = ucwords((strtolower($street)));
			$jobFairLocationID = $jobFairRow['location_id'];

			$dateScheduled = $jobFairRow['date_scheduled'];
			$dateScheduled = date('Y-m-d', strtotime($dateScheduled));
			$duration = $jobFairRow['duration'];


			$openingTime = $jobFairRow['start_time'];
			$openingTime = date('g:i:a', strtotime($openingTime));
			$closingTime = $jobFairRow['end_time'];
			$closingTime = date('g:i:a', strtotime($closingTime));

			$numVacancies = $jobFairRow['num_vacancies'];
			$websiteLink = $jobFairRow['website_link'];

			$firstName = $jobFairRow['first_name'];
			$middleName = $jobFairRow['middle_name'];
			$lastName = $jobFairRow['last_name'];
			$mobile = $jobFairRow['mobile'];
			$mobile1 = substr($mobile, 0, -7);
			$mobile2 = substr($mobile, 4, -4);
			$mobile3 = substr($mobile, -4);

			$email = $jobFairRow['email'];

			$landline = $jobFairRow['landline'];
			$landline1 = substr($landline, 0, -4);
			$landline2 = substr($landline, 3, -2);
			$landline3 = substr($landline, -2);
		}
	}
}
else{
	header('Location: admin-jobfairs.php');
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
<link rel="stylesheet" href="./css/datepicker.css"/>
<link rel="stylesheet" href="./css/bootstrap-timepicker.min.css"/>
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
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $webAdminName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
			</div>
			<div class="span8"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">JOB FAIRS</h4>
						<div class="tabbable">
							<ul class="nav nav-tabs nav-pills">
								<li><a href="admin-jobfairs.php">Manage Job Fairs</a></li>
								<li><a href="admin-add-jobfair.php">Add a Job Fair</a></li>
								<li class="active"><a href="#lC" data-toggle="tab"><i class="icon-remove" onclick="window.location.href='admin-jobfairs.php';" style="cursor:pointer;"></i>Edit Job Fair</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="lC">
									<div class="row-fluid">
										<div class="span11">
											<!-- FORM -->
											<form method="POST" name="jobFairForm" id="jobFairForm" class="form-horizontal" action="process-edit-jobfair.php">
												<h4>Job Fair Details</h4>
												<div class="control-group">
													<label class="control-label" for="jobFairCode">Code #</label>
													<div class="controls">
														<input class="span12" type="text" id="jobFairCode" name="jobFairCode" value="<?php echo $jobFairCode; ?>" readonly>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" for="title">Title</label>
													<div class="controls">
														<input class="span12" type="text" id="title" name="title" value="<?php echo $title; ?>" required>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" for="establishmentName">Establishment Name</label>
													<div class="controls">
														<input class="span12" type="text" id="establishmentName" name="establishmentName" value="<?php echo $venue;?>" required>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" for="street">Street Address</label>
													<div class="controls">
														<input class="span12" type="text" id="street" name="street" value="<?php echo $street;?>" required>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" for="location">Location Address</label>
													<div class="controls">
														<select class="span12" id="location" name="location" required>
															<option></option>
															<?php
																foreach($availableLocations as $aLKey => $aL) {
																	if($aLKey > 0){
																		if($aLKey == $jobFairLocationID){
																			echo "<option value=\"$aLKey\" selected>$aL</option>";
																		}
																		else{
																			echo "<option value=\"$aLKey\">$aL</option>";
																		}
																	}
																}
															?>
														</select>
													</div>
												</div>
												<div class="row-fluid">
													<div class="span5">
														<div class="control-group">
															<label class="control-label">DATE<br><span id="formLabel" style="font-weight:normal; color:#404040;">(YYYY-MM-DD)</span></label>
															<div class="controls">
																<input type="text" class="span12" id="jobFairDate" name="jobFairDate" size="16" data-date-format="yyyy-mm-dd" value="<?php echo $dateScheduled;?>" required>
															</div>
														</div>
													</div>
													<div class="span7">
														<div class="control-group">
															<label class="control-label">Duration<br><span id="formLabel" style="font-weight:normal; color:#404040;">(Number of days)</span></label>
															<div class="controls">
																<input type="number" class="span12" id="numDays" name="numDays" min="1" max="30" value="<?php echo $duration;?>" required>
															</div>
														</div>
													</div>
												</div>
												 <div class="row-fluid">
													<div class="span6">
														<div class="control-group">
															<label class="control-label">TIME START</label>
															<div class="controls">
																<div class="input-append bootstrap-timepicker">
															        <input id="timeStart" name="timeStart" class="span12" type="text" value="<?php echo $openingTime;?>" required><span class="add-on"><i class="icon-time"></i></span>
															    </div>
															</div>
														 </div>
													</div>
													<div class="span6">
														<div class="control-group">
															<label class="control-label">TIME END</label>
															<div class="controls">
																<div class="input-append bootstrap-timepicker">
															        <input id="timeEnd" name="timeEnd" class="span12" type="text" value="<?php echo $closingTime; ?>" required><span class="add-on"><i class="icon-time"></i></span>
															    </div>
															</div>
														 </div>
													</div>
												</div>
												 <div class="row-fluid">
													<div class="span4">
														<div class="control-group">
															<label class="control-label" for="numVacancies">Number of Vacancies</label>
															<div class="controls">
																<input type="number"  class="span12" id="numVacancies" name="numVacancies" min="1" max="10000" value="<?php echo $numVacancies;?>">
															</div>
														</div>
													</div>
													<div class="span8">
														<div class="control-group">
															<label class="control-label" for="websiteLink">Website Link:</label>
															<div class="controls">
																<input type="text"  class="span12" id="websiteLink" name="websiteLink" value="<?php echo $websiteLink;?>">
															</div>
														</div>
													</div>
												</div></br>
												<h4>Contact Person's Information</h4>
												<div class="control-group">
													<label class="control-label">Complete Name</label>
													<div class="controls control-row">
														<input class="span4" type="text" id="firstName" name="firstName" placeholder="First Name" value="<?php echo $firstName; ?>" required>
														<input class="span4" type="text" id="middleName" name="middleName" placeholder="Middle Name" value="<?php echo $middleName; ?>" required>
														<input class="span4" type="text" id="lastName" name="lastName" placeholder="Last Name" value="<?php echo $lastName;?>" required>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Mobile Number</label>
													<div class="controls control-row">
														<input class="span4" type="text" id="mobile1" name="mobile1" pattern="[0-9]*" minlength="4" maxlength="4" value="<?php echo $mobile1;?>"> -
														<input class="span4" type="text" id="mobile2" name="mobile2" pattern="[0-9]*" minlength="3" maxlength="3" value="<?php echo $mobile2;?>"> -
														<input class="span4" type="text" id="mobile3" name="mobile3" pattern="[0-9]*" minlength="4" maxlength="4" value="<?php echo $mobile3;?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label" for="email">Email</label>
													<div class="controls">
														<input class="span12" type="text" id="email" name="email" placeholder="Email Address" value="<?php echo $email;?>">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Landline Number</label>
													<div class="controls control-row">
														<input class="span4" type="text" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" value="<?php echo $landline1;?>"> -
														<input class="span4" type="text" id="landline2" name="landline2" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline2;?>"> -
														<input class="span4" type="text" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline3;?>">
													</div>
												</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset9">
						<!-- hidden variables -->
						<button type="submit" class="btn btn-primary job-post-add span12" id="submitApply" name="submitApply">SUBMIT</button>	
					</div>
					</form>
					<!--/FORM -->
				</div>
			</div>
		</div>
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Fair Added!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			  <p style="text-align:center;">Congratulations! Job Fair <span class="text-info"># <?php echo $jobFairCode;?></span> is successfully added.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" onclick="window.location.reload();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->

<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script src="./js/bootstrap-timepicker.min.js"></script>
<script src="./js/jquery.form.js"></script>
<script>
$(document).ready(function(){
	$("#location").select2(); 
	$("#jobFairDate").datepicker();
	$('#timeStart').timepicker();
	$('#timeEnd').timepicker();

	$('#jobFairForm').ajaxForm( { 
		complete: function(xhr) {
			var response = xhr.responseText;
			if(response === "1"){
				$('#myModal').modal('show');
			}
			else{
				alert(response);
			}
		}
	}); 
});
</script>
<!-- JS scripts -->
</body>
</html>