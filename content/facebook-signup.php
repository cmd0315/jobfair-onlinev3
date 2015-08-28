<?php
/** FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/
include './includes/functions.php';
include './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

$acctType = $_GET['acctType'];
$employerChecked = "required";
$applicantChecked = "required";
if($acctType == 1){
	$employerChecked = "checked";
	$applicantChecked = "readonly";
}
else if($acctType == 2){
	$applicantChecked = "checked";
	$employerChecked = "readonly";
}

extract($_POST);
$inputMobile = trim(mysql_real_escape_string($inputMobile));
$password = trim($password);

//$applyingJob = $_GET['applyingjob'];
$errorMsg = "";
$infoMsg = "";
$infoMsg = "Create Account Using Registered Email Address on Facebook";

//start apply job session
// if($applyingJob=="true") {
	// session_start();
	// $_SESSION['SRIEmployeeApplying'] = "true";
// }


//encrypts password for security purposes
$password = md5($inputPassword);
$errorMsg = "";
if(isset($status) && isset($inputEmail) && isset($password)){
	$userExistsQuery = "SELECT * FROM account WHERE username ='$inputEmail' ";
	$checkUserExists = mysql_query($userExistsQuery); 
	$numrows = mysql_num_rows($checkUserExists);
	if($numrows == 0) {
		session_start();
		$_SESSION['SRIUPassword'] = $password;
		$_SESSION['SRIUStatus'] = $status;
		
		if($status=="Employee"){		
			header("Location:employee-sign-up.php");
		}
		else
			header("Location:employer-sign-up.php");
	}
	else
		$errorMsg = "User account already exists!";
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
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">FACEBOOK SIGN UP</h4>
				<div class="row-fluid">
					<div class="span8 offset2" style="text-align:center;">
						<span  id="error-msg" style="display:block;"><p class="text-error"> <?php echo $errorMsg; ?></p></span>
						<span id="info-msg" style="display:block;"><p class="text-info"><strong> <?php echo $infoMsg; ?></strong></p></span>
					</div>
						<div class="span8 offset1">
						  <!-- START CREATE ACCOUNT -->
							<form name="signup-form" id="signup-form" class="form-horizontal" name="sign-up-form" method="POST" action=<?php echo $_SERVER['SELF'];?>>
								<div class="control-group">
									<label class="control-label" for="inputEmployee">STATUS</label>
									<div class="controls" style="margin-left:-5px;">
										<label class="radio inline" id="gender-option-label" style="margin-left:20px;"><input type="radio" name="status" id="inputEmployee" name="status" value="Employee" onchange="removeMsg();" <?php echo $applicantChecked;?> required>Applicant</label>
										<label class="radio inline" id="gender-option-label"><input type="radio" name="status" id="inputEmployer" name="status" value="Employer" onchange="removeMsg();" <?php echo $employerChecked;?> required>Employer</label>
									</div>
								</div>
								  <div class="control-group">
									<label class="control-label" for="inputEmail">EMAIL ADDRESS</label>
									<div class="controls">
									  <input type="email" id="inputEmail" name="inputEmail" onfocus="removeMsg();" value="<?php echo $user_profile['email']?>" required>
									</div>
								  </div>
								  <div class="control-group">
									<label class="control-label" for="inputPassword">PASSWORD</label>
									<div class="controls">
									  <input type="password" id="inputPassword" name="inputPassword" minlength="8" onfocus="removeMsg();" required>
									  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div
								<div class="control-group">
									<label class="control-label" for="inputRepeatPassword">RE TYPE PASSWORD</label>
									<div class="controls">
									  <input type="password" id="inputRepeatPassword" name="inputRepeatPassword" minlength="8" data-validation-match-match="inputPassword"  onfocus="removeMsg();" required>
									  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span7 offset5">
										<input type="button" class="btn span6" id="signup-btn" name="signup-btn" onclick="window.location.href='./end-fb-connect.php';" value="CANCEL"/>
										<input type="submit" class="btn btn-primary span6" id="signup-btn" name="signup-btn" value="CONTINUE" style="font-weight:bold;"/>
									</div>
								</div>
							</form>
							<!-- END FORM -->
						 </div>
					</div>
				</div>
			</div>
		</div>
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