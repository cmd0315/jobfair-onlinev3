<?php
include './includes/functions.php';
/* FOR DB CONNECTION*/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/* FOR DB CONNECTION*/

date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

extract($_POST);
$errorMsg = "";
$infoMsg = "";
$sucessMsg = "";
$isNew = $_GET['new'];
$sessionExists = $_GET['session'];
$verified = $_GET['verified'];
$applyingJob = $_GET['applyingjob'];
$accttype = $_GET['accttype'];

if($applyingJob=="true") {
	session_start();
	$_SESSION['SRIEmployeeApplying'] = "true";
}
$applicantStatus = "required";
$employerStatus = "required";

if($accttype == 1 || $accttype == 3){
	$applicantStatus = "readonly";
	$employerStatus = "checked";
}
else if($accttype == 2){
	$applicantStatus = "checked";
	$employerStatus = "readonly";
}

if($isNew != "true"){
	if(isset($username) && isset($password)) {
		$sql = mysql_query("Select * from account WHERE username='$username' AND status='1' ");	
		$numrows = mysql_num_rows($sql);
		
		if($numrows !=0){
			while ($row = mysql_fetch_assoc($sql))
			{
				$dbId = $row['id'];
				$dbUsername = $row['username'];
				$dbPassword = $row['password'];
				$password = md5($password);
				$dbAcctType = $row['acct_type'];
			}
			//check to see if they martch
			if($username == $dbUsername && $password== $dbPassword){
				session_start();
				$_SESSION['SRIUsername'] = $username;
				session_regenerate_id();

				//add log activity
				$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$username', 'SIGN IN')";
				$addLog = mysql_query($addLogQuery) OR die(mysql_error());

				//go to specific dashboard pages
				if($dbAcctType == 1 || $dbAcctType == 3){
					if($_SESSION['SRIEmployeeApplying'] == "true"){
						$errorMsg = "<strong>An error occured:</strong> Invalid account type. Login as an employee to apply to job posts.";
					}
					else {
						if(isset($_SESSION['SRIJobPosition'])){
							header("location: add-job-post.php");
						}
						else{
							header("location: employer-sitemap.php");
						}
					}
				}
				else if($dbAcctType == 2) {
					header("location: applicant-sitemap.php");
				}
				else{
					header("location: admin-sitemap.php");
				}
			}
			else{
				$errorMsg = "<strong>An error occured:</strong> Please check username or password if correct.";
			}
		}
		else {
			$errorMsg = "<strong>An error occured:</strong> No account for mobile number $mobile exists!";
		}
	}
	else {
		if($sessionExists != "false" && $verified != "true"){
			$errorMsg = "Please input correct  registered mobile number and password.";
		}
		else
			$errorMsg = "";
	}
	
	//FOR SUCCESSFUL msg
	if($verified == "true" && $applyingJob == "false") {
		$successMsg = "<strong>CONGRATULATIONS! Profile creation is completed.</strong>";
	}
	else
		$successMsg = "";
	
	//FOR INFO MSG
	if($sessionExists != "false" && $verified != "true"){
		$infoMsg = "<strong>Please try relogging in.</strong>";
	}
	else if($verified == "true" || $applyingJob == "true") {
		$infoMsg = "<strong>Sign in to start using your account.</strong>";
	}
	else
		$infoMsg = "<strong>Please sign in first to post a job listing.</strong>";
}

mysql_close($link_id);
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
			<div class="span4 well offset4"> 
				<h4 class="content-heading">LOG IN</h4>
				<div class="row-fluid">
					<div class="span8 offset2" style="text-align:center;">
						<span id="error-msg" style="display:block;"> <p class="text-error"><?php echo $errorMsg; ?></p></span>
						<span id="success-msg" style="display:block;"> <p class="text-success"><?php echo $successMsg; ?></p></span>
						<span id="info-msg" style="display:block;"> <p class="text-info"><?php echo $infoMsg; ?></p></span>
					</div>
					<!-- START LOGIN BOX  -->
					<?php echo $logIn=getLogInBox(); ?>
					<!-- END LOGIN BOX-->
				</div>
			</div>
		</div>
	</div>
	<br><br>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START SIGN UP MODAL-->
<!-- START MODAL-->
<div id="signUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
   <!-- <h3 id="myModalLabel">Sign Up For Account</h3>-->
   <h4 class="content-heading4">Sign up for Account</h4>
  </div>
  <div class="modal-body">
  <!-- START CREATE ACCOUNT -->
    <form name="signup-form" id="signup-form" class="form-horizontal" name="sign-up-form" method="POST" action="sign-up.php">
		<div class="row-fluid">
			<div class="span12" style="padding-left:20px;">
				<div class="control-group">
					<label class="control-label" for="inputEmployee">Status</label>
					<div class="controls" style="margin-left:-5px;">
						<label class="radio inline" id="gender-option-label" style="margin-left:20px;"><input type="radio" name="status" id="inputEmployee" name="status" value="Employee" <?php echo $applicantStatus; ?>>Applicant</label>
						<label class="radio inline" id="gender-option-label"><input type="radio" name="status" id="inputEmployer" name="status" value="Employer" <?php echo $employerStatus; ?>>Employer</label>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span10">
						<div class="control-group">
							<label class="control-label" for="inputMobile">Mobile No <br><span style="font-weight:normal;">(e.g. 0915-111-1111)</span></label>
							<div class="controls control-row">
								<input class="span4" type="text" id="inputMobile1" name="inputMobile1" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required><span style="font-weight:bold;"> -</span>
								<input class="span3" type="text" id="inputMobile2" name="inputMobile2" maxlength="3" minlength="3" pattern="[0-9]*" onfocus="removeMsg();" required><span style="font-weight:bold;"> -</span>
								<input class="span4" type="text" id="inputMobile3" name="inputMobile3" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required>
								<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
							</div>
						</div>
					</div>
				</div>
				  <div class="control-group">
					<label class="control-label" for="inputPassword">Password</label>
					<div class="controls">
					  <input type="password" id="inputPassword" name="inputPassword" minlength="8" placeholder="Password" required>
					  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputRepeatPassword">Re-type Password</label>
					<div class="controls">
					  <input type="password" id="inputRepeatPassword" name="inputRepeatPassword" placeholder="Re-type Password" data-validation-match-match="inputPassword" required>
					  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span10 offset2" style="margin-top:-10px;">
						<a id="resendVCodeLink" class="text-error" style="font-size:14px; cursor:pointer;" onclick="resendVCode();">Re-send verification code</a>
					</div>
				</div>
			</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span8 offset2">
			<button class="btn span6" data-dismiss="modal" aria-hidden="true">CANCEL</button>
			<input type="submit" class="btn btn-primary span6" id="signupBtn" name="signupBtn" value="CREATE ACCOUNT"/>
		</div>
	</div>
  </div>
  </form>
  <!-- END CREATE ACCOUNT -->
</div>
<!-- END MODAL -->
<!-- END SIGN UP MODAL-->
<!-- START RESET PASSWORD MODAL-->
<?php echo $resetPasswordModal=getResetPasswordModal(); ?>
<!-- END RESET PASSWORD MODAL-->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<!-- Validate plugin -->
<script src="./js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() { 
		$('#signUpLink').click(function () {
			$('#signUpModal').modal('show');
		});
		$('#resetPasswordLink').click(function () {
			$('#resetPasswordModal').modal('show');
		});
	
	});
</script>
<script>
	function removeMsg() {
		document.getElementById("error-msg").style.display = 'none';
		document.getElementById("success-msg").style.display = 'none';
		document.getElementById("info-msg").style.display = 'none';
	}
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
<script>
	//search account if password is forgotten or verification code is not received
	function findAccount(){
		var hr = new XMLHttpRequest();
		var accountInfo = document.getElementById("searchAcct").value;
		var url = "check-account-exists.php?accountInfo="+accountInfo;
		hr.open("GET", url, true);
		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				var return_data = hr.responseText;
				if(return_data == "No" || accountInfo == ""){
					document.getElementById("noAccountResult").style.display="block";
				}
				else if(return_data == "Not Verified"){
					var url2 = "resend-verification-code.php"; 
					window.location.href= url2;
				}
				else {
					var url2 = "search-account.php"; 
					window.location.href= url2;
				}
			}
		}
		hr.send(null); 
	}
	
	function resendVCode() {
		$('#signUpModal').modal('hide');
		$('#resetPasswordModal').modal('show');
	}
</script>
<!-- START FB Script -->
<!-- END FB Script -->
<script src="./js/bootstrap.min.js"></script>
</body>
</html>