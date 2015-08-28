<?php
include './includes/functions.php';
include './includes/db.php';

//GET CURRENT DATE
$year = date('Y');
$currDate = date("Y-m-d");
$minYear = $year - 50;
$minYear2= $minYear + 1;
$maxYear = $year - 1;

$minBirthDate = strtotime('-60 years');
$minBirthDate =  date('Y-m-d', $minBirthDate);
$maxBirthDate = strtotime('-18 years');
$maxBirthDate =  date('Y-m-d', $maxBirthDate);

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}


//set Profile Box Info
$status = getAcctInfo($username, "status");
$path = ""; //set dashboard destination
if($status == "Employee"){
	$path = "browse-jobs.php";
	$email = getEmployeeData($username, "email");
	$mobile = getEmployeeData($username, "mobile");
}
else if($status == "Employer") {
	$path = "add-job-post.php";
	$email = getEmployerData($username, "email");
	$mobile = getEmployerData($username, "mobile");
}
else{
	$path = "admin-reports.php";
	$email = $username;
}
//basic info
$mobile1 = substr($mobile, 0, -7);
$mobile2 = substr($mobile, 4, -4);
$mobile3 = substr($mobile, -4);
$password = getAcctInfo($username, "password");
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
	<?php echo $header=getHeader(); ?>
	<!-- END HEADER -->
	<?php 
		if($status === "Employee"){
			echo $navbar=getApplicantNavBar();
		}
		else if($status === "Employer"){
			echo $navbar=getEmployerNavBar();
		}
		else
			echo $navbar=getSearchBar();
	?>
	<div class="container-fluid" style="padding-top: 20px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span6 offset3"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">ACCOUNT DETAILS</h4>
						<div class="row-fluid">
							<div class="span9 offset2">
							<!-- START SIGNUP FORM -->
							<form class="form-horizontal" name="changePwdForm"  id="changePwdForm" method="POST" action="process-changepassword.php">
								<div class="row-fluid">
									<div class="span12">
										 <div class="control-group">
											<label class="control-label">
												<?php
													if($username === $email){
														echo "EMAIL ADDRESS";
													}
													else if($username === $mobile){
														echo "MOBILE NO.";
													}
													else{
														echo "USERNAME";
													}
												?>
											</label>
											<div class="controls controls-row">
												<?php
													if($username === $email){
														echo "<input type=\"text\" class=\"span9\" id=\"email\" name=\"email\" value=\"$email\" readonly/>";
													}
													else{
														echo "<input type=\"text\" class=\"span3\" id=\"mobile1\" name=\"mobile1\" value=\"$mobile1\" readonly/>
														<input type=\"text\" class=\"span3\" id=\"mobile2\" name=\"mobile2\" value=\"$mobile2\" readonly/>
														<input type=\"text\" class=\"span3\" id=\"mobile3\" name=\"mobile3\" value=\"$mobile3\" readonly/>";
													}
												?>
											  <p class="help-block" style="font-size:12px;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">OLD PASSWORD</label>
									<div class="controls">
										<input type="password" class="span9" id="oldPassword" name="oldPassword" minlength="8" onchange="checkPassword(this.value, '<?php echo $password; ?>');" required />
										<p class="help-block" id="answer" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">NEW PASSWORD</label>
									<div class="controls">
										  <input type="password" class="span9" id="newPassword" name="newPassword" minlength="8" disabled="disabled" required />
										  <p class="help-block" id="pwd1" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
								 <div class="control-group">
									<label class="control-label">CONFIRM NEW PASSWORD</label>
									<div class="controls">
									  <input type="password" class="span9" id="newPassword2" name="newPassword2" data-validation-match-match="newPassword" disabled="disabled"/>
									  <p class="help-block" id="pwd2" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								 </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="status" name="status" value="<?php echo $status;?>" />
		<input type="hidden" id="email" name="email" value="<?php echo $username;?>">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span3 offset6">
						<button type="button" class="btn span6" onclick="editProfile();">CANCEL</button>
						<button type="submit" class="btn btn-primary job-post-add span6" id="submitApply" name="submitApply">SAVE CHANGES</button>				
						</form>
						<!--END SIGNUP FORM-->
					</div>
				</div>
			</div>
		</div>
	</div><br/>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Account Information Saved</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center">You have successfully updated your account details.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span6 offset1" style="margin-left:80px;">
			<button class="btn btn-primary input-medium" onclick="reload();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
$(document).ready(function() { 
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
		// bind to the form's submit event 
		 $('#changePwdForm').submit(function() { 
			var result = document.getElementById("pwd2").innerHTML;
			if(result == "") {
				$(this).ajaxSubmit(); 
				$("#myModal").modal('show');
				return false; 
			}
		});
});
</script>

<script>
	function checkPassword(password1, password2){
		var hr = new XMLHttpRequest();
		var url = "verify-password.php?pwd1="+password1+"&pwd2="+password2;
		hr.open("GET", url, true);
		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				var return_data = hr.responseText;
				document.getElementById("answer").innerHTML = return_data;
				var ans = document.getElementById("answer").innerHTML;
				if(ans.indexOf("Matched") != -1){
					document.getElementById("newPassword").disabled = "";
					document.getElementById("newPassword2").disabled = "";
				}
			}
		}
		hr.send(null); 
	}

	function editProfile() {
		var path = "<?php
			if($status == "Employee"){
				echo "employee-edit-profile.php";
			}
			else if($status == "Employer"){
				echo "employer-edit-profile.php";
			}
			else
				echo "admin-edit-profile.php";
		?>";
		window.location.href= path;
	}
	
	function reload() {
		window.location.href = "change-password.php";
	}
</script>
<!-- JS scripts -->
</body>
</html>