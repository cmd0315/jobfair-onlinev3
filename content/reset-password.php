<?php
include './includes/functions.php';
include './includes/db.php';

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

$method = $_GET['type'];
$title = "";
$msg = "";

if ($method == "email") {
	$title = "CHECK YOUR EMAIL";
	$msg = "We sent you an email with the confirmation code. Enter it below to continue resetting your password.";
}
else {
	$title = "CHECK YOUR PHONE";
	$msg = "We sent you a text with the confirmation code. Enter it below to continue resetting your password.";
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
				<h4 class="content-heading"><?php echo $title; ?></h4>
						<div class="row-fluid">
							<div class="span8"> 
								<div class="row-fluid">
									<div class="span10">
										<?php echo $msg; ?>
									</div>
								</div>
								<div class="row-fluid" style="height:20px;"></div>
								<div class="row-fluid">
									<div class="control-group">
										<label class="control-label" style="color:black;">CODE</label>
										<div class="controls controls-row">
											  <input type="text" class="span7" id="code" name="code" required />
											  <p class="help-block" id="noCodeResult" style="display:none; color:red; text-align:center;"><strong>Error!</strong>  Invalid code. Please try again.</p>
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
			<div class="span2 offset7">
				<button type="submit" class="btn btn-primary job-post-add span12" id="submitReset" name="submitReset" onclick="verifyCode();">CONTINUE</button>			
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
<script src="./js/jquery.form.js"></script>
<!-- Validate plugin -->
<script src="./js/jquery.validate.min.js"></script>
<script>
	function verifyCode(){
			var hr = new XMLHttpRequest();
			var url = "check-code.php";
			var code = document.getElementById("code").value;
			var vars = "code="+code;
			hr.open("POST", url, true);
			hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			hr.onreadystatechange = function() {
				if(hr.readyState == 4 && hr.status == 200) {
					var return_data = hr.responseText;
					if (return_data == "false") {
						document.getElementById("noCodeResult").style.display = "block";
					}
					else {
						var url2 = "reset-change-password.php?code=" + code;
						window.location.href = url2;
					}
				}
			}
			hr.send(vars);
		}
		function showLogIn(){
			window.location.href = "login.php?new=true";
		}
</script>
<script>
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>