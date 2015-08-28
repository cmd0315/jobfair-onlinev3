<?php
/** FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/
include './includes/functions.php';
include './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

$applyingJob = $_GET['applyingjob'];
$processing = $_GET['processing'];
$acctType = $_GET['acctType'];
$errorMsg = "";
$infoMsg = "";
$employeeStat = "";
$employerStat = "";

extract($_POST);
if(isset($user_profile['email'])){
	$tempEmail = mysql_real_escape_string($user_profile['email']);
	$employeeEmailExistsQuery = "SELECT * FROM employee WHERE email='$tempEmail' ";
	$checkEmployeeEmail = mysql_query($employeeEmailExistsQuery) or die(mysql_error());
	$employeeEmailCount = mysql_num_rows($checkEmployeeEmail);
	
	$employerEmailExistsQuery = "SELECT * FROM employer WHERE email='$tempEmail' ";
	$checkEmployerEmail = mysql_query($employerEmailExistsQuery) or die(mysql_error());
	$employerEmailCount = mysql_num_rows($checkEmployerEmail);

	if(($employeeEmailCount > 0 || $employerEmailCount > 0) && $applyingJob!="true"){
		$errorMsg = "An error occured: The facebook account that you are using is already registered in the website.";
		$infoMsg = "Please use another facebook account.";

	}
	else{
		if($inputPassword == ""){
			header("Location: facebook-signup.php?acctType=$acctType");
		}
	}
}



$inputMobile1 = trim(mysql_real_escape_string($inputMobile1));
$inputMobile2 = trim(mysql_real_escape_string($inputMobile2));
$inputMobile3 = trim(mysql_real_escape_string($inputMobile3));
$username = $inputMobile1 . $inputMobile2 . $inputMobile3;

//start apply job session
if($applyingJob=="true") {
	session_start();
	$_SESSION['SRIEmployeeApplying'] = "true";
}

$employeeStat = "required";
$employerStat = "required";
		

//encrypts password for security purposes
$password = md5($inputPassword);
if(isset($status) && isset($username) && isset($password)){
	$userExistsQuery = "SELECT * FROM account WHERE username ='$username' AND status='1'";
	$checkUserExists = mysql_query($userExistsQuery); 
	$numrows = mysql_num_rows($checkUserExists);
	if($numrows === 0) {
		$userHasAccountQuery = "SELECT * FROM account WHERE username ='$username'";
		$checkUserHasAccount = mysql_query($userHasAccountQuery); 
		$numrows2 = mysql_num_rows($checkUserHasAccount);
		
		session_start();
		if($numrows2 == 0) {
			$_SESSION['SRIUMobile'] = $username;
			$_SESSION['SRIUPassword'] = $password;
			$_SESSION['SRIUStatus'] = $status;
			if($status=="Employee"){		
				header("Location: employee-sign-up.php");
			}
			else
				header("Location: employer-sign-up.php");
		}
		else{
			$_SESSION['SRISearchUser'] = $username;
			header("Location: resend-verification-code.php?register=true");
		}
	}
	else{
		$_SESSION['SRISearchUser'] = $username;
		$errorMsg = "User account already exists!";
	}
}
else{
	if($processing == "true"){
		if($errorMsg == "" || $infoMsg == ""){
			if($applyingJob != "true"){
				$errorMsg = "An error occured: Provide account type.";
				$infoMsg = "Please try signing up again.";
				$employeeStat = "required";
				$employerStat = "required";
			}
			else{
				$errorMsg = "";
				$infoMsg = "Create Employee Account";
				$employeeStat = "checked";
				$employerStat = "disabled";
			}
		}
	}
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
	<!-- START NAVBAR -->
	<?php echo $navbar=getGeneralNavBar(); ?>
	<!-- END NAVBAR -->
	<div class="container-fluid" style="padding-top:30px;">
		<!-- START CREATE ACCOUNT -->
		<form name="signup-form" id="signup-form" name="sign-up-form" method="POST" action="sign-up.php?processing=true">
		<div class="row-fluid">
			<div class="span4 well offset4"> 
				<h4 class="content-heading">SIGN UP</h4>
				<div class="row-fluid">
					<div class="span8 offset2" style="text-align:center;">
						<span  id="error-msg" style="display:block;"><p class="text-error"> <?php echo $errorMsg; ?></p></span>
						<span id="info-msg" style="display:block;"><p class="text-info"><strong> <?php echo $infoMsg; ?></strong></p></span>
					</div>
				</div>
				<div class="row-fluid">
					<div class="row-fluid">
						<div class="span8 offset2">
							<div class="row-fluid">
								<div class="span12">
									<div class="control-group">
										<label class="control-label" for="inputEmployee">STATUS</label>
											<div class="controls">
												<label class="radio inline" id="gender-option-label"><input type="radio" name="status" id="inputEmployee" name="status" value="Employee" onchange="removeMsg();" <?php echo $employeeStat; ?>>Applicant</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="status" id="inputEmployer" name="status" value="Employer" onchange="removeMsg();" <?php echo $employerStat; ?>>Employer</label>
											</div>
									</div>
								</div>
							</div><br/>
							<div class="row-fluid">
								<div class="span12">
									<div class="control-group">
										<label class="control-label" for="inputMobile">MOBILE <br><span style="font-weight:normal;">(e.g. 0915-111-1111)</label>
										<div class="controls control-rows">
											<input class="span4" type="text" id="inputMobile1" name="inputMobile1" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required>
											<input class="span4" type="text" id="inputMobile2" name="inputMobile2" maxlength="3" minlength="3" pattern="[0-9]*" onfocus="removeMsg();" required>
											<input class="span4" type="text" id="inputMobile3" name="inputMobile3" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required>
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									  <div class="control-group">
										<label class="control-label" for="inputPassword">PASSWORD</label>
										<div class="controls">
										  <input type="password" class="span12" id="inputPassword" name="inputPassword" minlength="8" onfocus="removeMsg();" required>
										  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">		
									<div class="control-group">
										<label class="control-label" for="inputRepeatPassword">RE TYPE PASSWORD</label>
										<div class="controls">
										  <input type="password"class="span12"  id="inputRepeatPassword" name="inputRepeatPassword" minlength="8" data-validation-match-match="inputPassword"  onfocus="removeMsg();" required>
										  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><br/><br/>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span2 offset6">
				<div class="row-fluid">
					<div class="span12">
						<input type="submit" class="btn btn-primary span12" id="signup-btn" name="signup-btn" value="CREATE ACCOUNT"/>
					</div>
				</div>
			</div>
		</div>
		</form>
		<!-- END CREATE ACCOUNT -->
	</div>
	<br><br>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<!-- Validate plugin -->
<script src="./js/jquery.validate.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script>
$(document).ready(function() { 
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
});

function removeMsg() {
	document.getElementById("error-msg").style.display = 'none';
	document.getElementById("info-msg").style.display = "none";
}
</script>
<script src="./js/bootstrap.min.js"></script>
<!-- JS scripts -->
</body>
</html>