<?php
include './includes/functions.php';
include './includes/db.php';

// $code = $_GET['code'];
// $username = getUserInfo($code, "username");

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRISearchUser'];

//set Profile Box Info
$status = getAcctInfo($username, "status");

if ($status == "Employer") {
	$name = getEmployerData($username, "company-name");
	$address = getEmployerData($username, "address");
	$profilePic = getEmployerData($username, "profile_pic");
}
else {
	$name = getEmployeeData($username, "full-name");
	$address = getEmployeeData($username, "address");
	$profilePic = getEmployeeData($username, "profile_pic");
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
	<?php echo $header=getHeader(); ?>
	<!-- END HEADER -->
	<!-- START NAVBAR -->
	<?php echo $navbar=getGeneralNavBar(); ?>
	<!-- END NAVBAR -->
	<div class="container-fluid clear-top">
		<div class="row-fluid">
			<div class="span3 offset6"> 
				<div class="row-fluid">
					<h5 class="pull-right"><a href="./index.php"><i class="icon-circle-arrow-left"></i> Back to Home Page</a></h5>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">CHANGE PASSWORD</h4>
					<form name="resetPwdForm" id="resetPwdForm" method="POST" action="process-resetpassword.php">
						<div class="row-fluid">
							<div class="span7 offset1">
								 <div class="control-group">
									<label class="control-label">NEW PASSWORD</label>
									<div class="controls">
									  <input type="password" class="span11" id="newPassword" name="newPassword" minlength="8" required />
									  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								 </div>
								 <div class="control-group">
									<label class="control-label">RE TYPE NEW PASSWORD</label>
									<div class="controls">
									  <input type="password" class="span11" id="newPassword2" name="newPassword2" data-validation-match-match="newPassword"/>
									  <p class="help-block" id="pwd2" style="font-size:11.5px; background-color:lightgrey;"></p>
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
										  <p style="text-align:center;"><span class="text-info" style="font-weight:bold;">Status:</span> <?php echo $status;?></p>
										</div>
									  </li>
									</ul>
								</div>
							</div>
					</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span3 offset6">
				<button type="button" class="btn btn-primary span6" id="logIn" name="logIn" onclick="showHomePage();">CANCEL</button>		
				<button type="submit" class="btn btn-primary job-post-add span6" id="submitReset" name="submitReset">RESET</button>
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
	<h4 class="content-heading4" id="modal-title">Password Changed!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center">You have successfully changed your password!</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span10 offset2" style="padding-left:30px;">
			<button class="btn btn-primary input-medium" data-dismiss="modal">OK</button>
			<button class="btn btn-primary input-medium" onclick="showLogIn();">LOG IN</button>
			</form>
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
$(document).ready(function() { 	
		// bind to the form's submit event 
	 $('#resetPwdForm').submit(function() { 
		var result = document.getElementById("pwd2").innerHTML;
		if(result == "") {
			$(this).ajaxSubmit(); 
			$("#myModal").modal('show');
			return false; 
		}
	});
});
function showLogIn(){
	window.location.href = "login.php?new=true";
}

function showHomePage(){
		window.location.href = "index.php";
}
</script>
<script>
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>