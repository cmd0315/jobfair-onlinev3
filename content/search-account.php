<?php
include './includes/functions.php';
include './includes/db.php';

/* GET SESSION DATA*/
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

session_start();
if(isset($_SESSION['SRIUsername'])){
	$sessionUser = $_SESSION['SRIUsername'];
}
else{
	$sessionUser = "PUBLIC";
}

if(isset($_SESSION['SRISearchUser'])){
	$username = $_SESSION['SRISearchUser'];
	//set Profile Box Info
	$name = getEmployeeData($username, "full-name");
	$address = getEmployeeData($username, "address");
	$profilePic = getEmployeeData($username, "profile_pic");
	$mobile = getEmployeeData($username, "mobile");
	$email = getEmployeeData($username, "email");

	if($name === "Doesn't exist!") {
		$name = getEmployerData($username, "company-name");
		$address = getEmployerData($username, "address");
		$profilePic = getEmployerData($username, "profile_pic");
		$mobile = getEmployerData($username, "mobile");
		$email = getEmployerData($username, "email");
	}
	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUser', 'SEARCH ACCOUNT', '$username')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
}
else{
	header('Location: ./index.php');
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
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 offset6"> 
				<div class="row-fluid">
					<h5 class="pull-right"><a href="./index.php"><i class="icon-circle-arrow-left"></i> Back to Home Page</a></h5>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">RESET PASSWORD</h4>
				<form id="resetPwdForm" name="resetPwdForm" method="POST" action="send-reset-code.php">
					<div class="row-fluid">
						<div class="span8"> 
							<div class="row-fluid">
								 <div class="control-group">
									<label class="control-label">How would you like to change your password?</label>
									<div class="controls">
										<?php
											if($mobile !== ""){
												echo "<label class=\"radio inline\" id=\"gender-option-label\"><input type=\"radio\" name=\"resetPwdHow\" id=\"resetPwdHow1\" value=\"code\" required>Text me a code.</label>";
											}
											if($email !== ""){
												echo "<label class=\"radio inline\" id=\"gender-option-label\"><input type=\"radio\" name=\"resetPwdHow\" id=\"resetPwdHow2\" value=\"link\" checked>Email me a code.</label>";
											}
											
										?>
									</div>
								</div>
							 </div>
						</div>
						<div class="span4">
							<div class="row-fluid">
								<ul class="thumbnails">
								  <li class="span8 offset2">
									<div class="thumbnail">
									  <img class="img-polaroid" src="<?php echo $profilePic;?>" alt="profile-pic">
									  <p style="text-align:center;"><span class="text-info" style="font-weight:bold;">Username:</span> <?php echo $username;?></p>
									</div>
								  </li>
								</ul>
							</div>
						</div>
					</div>
					<!-- hidden variable -->
					<Input type="hidden" name="username" id="username" value="<?php echo $username;?>">
					<Input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile;?>">
					<Input type="hidden" name="email" id="email" value="<?php echo $email;?>">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span2 offset7">
				<button type="submit" class="btn btn-primary job-post-add span12" id="submitReset" name="submitReset" onclick="continueReset();">CONTINUE</button>	
				</form>
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modalTitle"></h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center" id="modalMsg"></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span6 offset3">
			<button type="button" class="btn btn-primary span12" onclick="showReset();" style="font-weight:bold;">RESET PASSWORD</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<!-- Validate plugin -->
<script src="./js/jquery.validate.min.js"></script>
<script>
function continueReset() {
	var modalTitle = "";
	var modalMsg = "";
	var mobile = document.getElementById("mobile").value;
	var email = document.getElementById("email").value;

	if (document.getElementById("resetPwdHow1").checked) {
		modalTitle = "Code Texted";
		modalMsg = "A verification code has been texted at number <span class='text-info' style='font-weight:bold;'> " + mobile + "<span>.";
	}
	else{
		modalTitle = "Email Sent!";
		modalMsg = "An email has been sent to <span class='text-info' style='font-weight:bold;'> " + email + "<span>.";
	}
	document.getElementById("modalTitle").innerHTML = modalTitle;
	document.getElementById("modalMsg").innerHTML = modalMsg;
}

//ajax submit form
 $('#resetPwdForm').submit(function() { 
	   $(this).ajaxSubmit(); 
		$("#myModal").modal('show');
		return false; 
  });

function showLogIn(){
	window.location.href = "login.php?new=true";
}
function showReset(){
	var method = "";
	if (document.getElementById("resetPwdHow1").checked) {
		method = "phone";
	}
	else {
		method = "email";
	}
	window.location.href = "reset-password.php?type="+method;
}
</script>
<script>
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>