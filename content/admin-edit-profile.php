<?php
include './includes/functions.php';
include './includes/db.php';
//GET CURRENT DATE
$year = date('Y');
$minYear = $year - 50;
$minYear2= $minYear + 1;
$maxYear = $year - 1;

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}


//GET ALL LOCATIONS LISTED
$availableLocations= getJobLocations();

//get Web Admin Info
$status = getAcctInfo($username, "status");
$coName = getWebAdminData($username, "full-name");
$street1 = getWebAdminData($username, "street1");
$city1 = getWebAdminData($username, "city1");
$province1 = getWebAdminData($username, "province1");
$firstName = getWebAdminData($username, "first_name");
$middleName = getWebAdminData($username, "middle_name");
$lastName = getWebAdminData($username, "last_name");
$mobile = getWebAdminData($username, "mobile");
$mobile1 = substr($mobile, 0, -7);
$mobile2 = substr($mobile, 4, -4);
$mobile3 = substr($mobile, -4);

$email = getWebAdminData($username, "email");
$area = $city1 . ", " . $province1;

//FOR PROFILE SAVING
$profileSaved = 0;
if(isset($_GET['saved'])){
	$profileSaved = $_GET['saved'];
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
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
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
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span6 offset1">
				<h3>Edit Profile</h3>
			</div>
			<div class="span3 offset1">
				<p class="text-info pull-right" style="font-weight:bold; padding-top:30px;">*Required fields</p>
			</div>
		</div>
		<div class="row-fluid" id="content-apply-job">
			<div class="span10 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">WEB ADMIN INFORMATION</h4>
						<!-- START SIGNUP FORM -->
						<form name="edit-form" id="edit-form" class="form-horizontal" method="POST" action="process-editprofile.php">
							 <div class="control-group form-horizontal">
								<label class="control-label">*FULL NAME<br><span id="formLabel" style="font-weight:normal; color:#404040;">( First Name, Middle Name, Last Name )</span></label>
								<div class="controls controls-row">
								  <input type="text" class="span4" id="firstName" name="firstName" value="<?php echo $firstName;?>" minlength="2" required />
								   <input type="text" class="span4" id="middleName" name="middleName" value="<?php echo $middleName;?>" minlength="2" required />
								   <input type="text" class="span4" id="lastName" name="lastName" value="<?php echo $lastName;?>" minlength="2" required />
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							  <div class="control-group">
									<label class="control-label">*ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( Street Address, City and Provincial Address )</span></label>
									<div class="controls controls-row">
									   <input type="text" class="span4" id="street1" name="street1" value="<?php echo $street1;?>" minlength="5" required />
									   <select class="span8" id="area1" name="area1" required>
											<?php
												foreach($availableLocations as $aL) {
													if($aL == $area){
														echo "<option value=\"$aL\" selected=\"selected\">$aL</option>";
													}
													else {
														echo "<option value=\"$aL\">$aL</option>";
													}
												}
											?>
										</select>
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								
						</div>
							<div class="row-fluid">
								<div class="span6">
									<div class="row-fluid">
										<div class="control-group">
											<label class="control-label">*MOBILE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. 0915-111-1111)</span></label>
											<div class="controls contols-row">
												<input type="text" class="span3" id="mobile1" name="mobile1"  value="<?php echo $mobile1;?>" pattern="[0-9]*" minlength="4" maxlength="4" required /> -
												<input type="text" class="span3" id="mobile2" name="mobile2"  value="<?php echo $mobile2;?>" pattern="[0-9]*" minlength="3" maxlength="3" required /> -
												<input type="text" class="span3" id="mobile3" name="mobile3"  value="<?php echo $mobile3;?>" pattern="[0-9]*" minlength="4" maxlength="4"  required />
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										</div>
									</div>
								</div>
								<div class="span6">
									<div class="row-fluid">
										<div class="control-group">
											<label class="control-label">EMAIL ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. jdelacruz@sri.com)</span></label>
											<div class="controls contols-row">
												<input type="email" class="span10" id="email" name="email" style="margin-right:80px;" value="<?php echo $email; ?>" > 
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										</div>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
		 <!-- hidden variables -->
		<input type="hidden" id="status" name="status" value="<?php echo $status;?>" />
		<input type="hidden" id="username" name="username" value="<?php echo $username;?>">
		<input type="hidden" id="profileSaved" name="profileSaved" value="<?php echo $profileSaved;?>">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span5 offset1">
						<p class="text-info" style="font-weight:bold;">The page displays the contact information of the person in-charge as the web administrator.</p>
					</div>
					<div class="span5">
						<button type="button" class="btn btn-primary span6" id="changeAcctInfo" name="changeAcctInfo" onclick="changeAccountInfo();">CHANGE PASSWORD</button>
						<button type="submit" class="btn btn-primary job-post-add span6" id="submitApply" name="submitApply">SAVE CHANGES</button>				
						</form>
						<!--END SIGNUP FORM-->
					</div>
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
    <h4 class="content-heading4">Profile Changes Saved!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			   <p style="text-align:center">You have successfully updated your profile.</p>
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
<!-- END MODAL-->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
$(document).ready(function() { 
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
});
</script>
<script>
$(document).ready(function(){ 
	var profileSaved = document.getElementById('profileSaved').value;
	if(profileSaved == 1){
		$("#myModal").modal('show');
	}
	
	$("#area1").select2(); 
	
	// bind to the form's submit event 
		$('#edit-form').submit(function() { 
		//validate inputs
		var errors = 0;
		var fields = new Array();
		var requiredFields = new Array();
		var errorMsg = "";
		var street = document.getElementById('street1').value;
		var firstName = document.getElementById('firstName').value;
		var middleName = document.getElementById('middleName').value;
		var lastName = document.getElementById('lastName').value;
		
		fields.push(street, firstName, middleName, lastName);
		requiredFields.push(1,1,1,1);
		
		for (var f = 0; f < fields.length; f++) {
			var fElem = fields[f];
			var fReq = requiredFields[f];
			if(fReq > 0 && fElem.match(/^\s*$/)){
				errors = 1;
				errorMsg = "<li>Please make sure that all required fields are filled.</li>";
			}
		}
		
		if(errors>0){
			document.getElementById('errorContent').innerHTML = errorMsg;
			$("#errorModal").modal('show');
		}
		else{
			// $(this).ajaxSubmit(); 
			// $("#myModal").modal('show');
			return true;
		}
		return false;
    });
});
function changeAccountInfo() {
	window.location.href="change-password.php";
}
</script>
<!-- JS scripts -->
</body>
</html>