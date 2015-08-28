<?php
include './includes/functions.php';
include './includes/db.php';

extract($_POST);
//check if account is already verified
$checkAccountVerifiedQuery = "SELECT * FROM account WHERE username='$username' AND status='1' ";
$checkAccountVerified = mysql_query($checkAccountVerifiedQuery) or die(mysql_error());
$accountVerifiedNum = mysql_num_rows($checkAccountVerified);
if($accountVerifiedNum == 0){
	//check if code is correct
	if(isset($inputConfirmation)){
		$inputConfirmation = trim(mysql_real_escape_string($inputConfirmation));
		if(empty($inputConfirmation)){
			$errorMsg = "Please input confirmation code.";
		}else{
			$sqlSelect = mysql_query("SELECT * FROM verification_code WHERE code='$inputConfirmation'") or die(mysql_query());
			$count = mysql_num_rows($sqlSelect);		
			$row = mysql_fetch_assoc($sqlSelect);
			if($count > 0){
				$username = $row['username'];
				$sqlDelete = mysql_query("DELETE FROM verification_code WHERE code='$inputConfirmation'") or die(mysql_error());
				$sqlUpdate = mysql_query("UPDATE account SET status='1' WHERE username='$username'") or die(mysql_error());
				$userStatus = getAcctInfo($username, "status");
				//START SESSION TO REDIRECT TO APPROPRIATE LANDING PAGE
				session_start();
				$_SESSION['SRIUsername'] = $username;
				if($userStatus == "Employee"){
					header("Location: browse-jobs.php");
				}
				else if($userStatus == "Employer") {
					header("Location: add-job-post.php");
				}
				else{
					$errorMsg = "An error occured: Account status not specified.";
				}
			}else{
				$errorMsg = "An error occured: Invalid confirmation code!";
			}
		}
	}	
}
else{
	$errorMsg = "Account is already verified! Login <a href='login.php?new=true'>here</a>.";
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
	<?php echo $header=getHeader(); ?>
	<!-- END HEADER -->
	<!-- START NAVBAR -->
	<?php echo $navbar=getGeneralNavBar(); ?>
	<!-- END NAVBAR -->
	<div class="container-fluid">
		<div class="row-fluid" >
			<div class="span4 offset5" > 
				<div class="row-fluid">
					<h5 class="pull-right"><a href="./index.php"><i class="icon-home"></i>Back to Home Page</a></h5>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">Enter Verification Code</h4>
				<div class="row-fluid">
					<div class="span8 offset2" style="text-align:center;">
						<span  id="error-msg" style="display:block;"><p class="text-error"> <?php echo $errorMsg; ?></p></span>
					</div>
				</div>
				<div class="row-fluid">
					 <!-- START CREATE ACCOUNT -->
					<form name="signup-form" id="signup-form" class="form-horizontal" name="sign-up-form" method="POST" action="account-verify.php">
						<div class="span11">
							 <div class="control-group">
								<label class="control-label" for="inputConfirmation">Verification Code</label>
								<div class="controls">
									<input type="text" class="span12" id="inputConfirmation" name="inputConfirmation" onfocus="removeMsg();" required>
									<input type="hidden" class="span10" id="username" name="username" value="<?php echo $_GET['username'];?>">
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span2 offset7">
				<button type="submit" class="btn btn-primary span12" id="signup-btn" name="submit"/>CONFIRM</button>
			</div>
		</form>
		<!-- END FORM -->
		</div>
	</div>
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

function reVerify(){
	window.location.href="verify-account.php";
}

function removeMsg() {
	document.getElementById("error-msg").style.display = 'none';
	document.getElementById("info-msg").style.display = "none";
}
</script>
<script src="./js/bootstrap.min.js"></script>
<!-- JS scripts -->
</body>
</html>