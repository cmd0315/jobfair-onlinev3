<?php
function generateProfile($status, $name, $age, $address, $picSrc){
//FORMAT PATH FOR EDIT PROFILE
if($status == "Applicant") {
	$editProfile = "employee-edit-profile.php";
}
else if($status == "Employer" || $status == "SRI Branch Manager") {
	$editProfile = "employer-edit-profile.php";
}
else{
	$editProfile = "admin-edit-profile.php";
}

if($picSrc == Null || $picSrc == "Null"){
	$picSrc = "./img/id.png";
}
$content = "";
$content = <<<EOT
	<div class="row-fluid" style="padding-top:15px;">
		<div class="span12 well">
			<h4 class="content-heading">USER INFORMATION</h4>
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<div class="span10 offset1">
							<div class="row-fluid">
								<div class="span6 offset3">
									<div class="row-fluid">
										<center><img src="$picSrc" class="img-polaroid" name="profilePicture" id="profilePicture" style="border:1px solid gray;"/></center>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<center><h6 id="employment-status">$status <a href="$editProfile" style="font-weight:bold;">(Edit Profile)</a></h6>
EOT;
							if($status == "Employee") {
								$content.=<<<EOT
									<div style="">
									<span id='user-name'>$name</span>
EOT;
									$lh = 1.2;
							}
							else{
								$content.=<<<EOT
									<div style="">
									<span id='user-name'>$name</span>
EOT;
									$lh = 1.2;
							}	
									$content.=<<<EOT
										</div>
										<div id="employee-info">
											<span id="employee-age">$age</span><br>
											<span id="employee-address" style="line-height:$lh;">$address</span>
										</div>
									</div></center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
EOT;
echo $content;
}
?>