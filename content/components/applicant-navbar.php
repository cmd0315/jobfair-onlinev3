<?php
function generateApplicantNavBar($username){
	$content .=<<<EOT
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="navbar">
				<div class="navbar-inner" id="home-sign-buttons">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand dropdown-toggle" style="padding-left:46px;" href="employee-edit-profile.php">
EOT;
						$fullName = getEmployeeData($username, "full-name");
						$profilePic = getEmployeeData($username,"profile_pic");
						$profilePicDisplay = "<img src='" . $profilePic . "' class=\"img-circle navbar-image\">";
						$content .= $profilePicDisplay . $fullName;
	$content .=<<<EOT
						</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav">
								<li id="resume" name="resume"><a href="#" rel="tooltip" data-placement="bottom" title="View PDF copy of resume" onclick="viewResumeEmp();"><i class="icon-file icon-white"></i> VIEW RESUME</a></li>
								<li><a href="browse-jobs.php"><i class="icon-briefcase icon-white"></i> JOB POSTS</a></li>
							</ul>
							<form class="navbar-form pull-right" method="GET" action="applicant-search-page.php" style="margin-top:-1px;">
								<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Job Posts/ Job Location" style="height: 20px !important;">
								<button type="submit" class="btn btn-info btn-small"><i class="icon-search icon-white"></i></button>
							</form>
							<ul class="nav pull-right">
								<li><a href="applicant-sitemap.php"><i class="icon-home icon-white"></i></a></li>
									<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i><b class="caret" ></b></a>
									<ul class="dropdown-menu" style="color:white; background-color: #0081c2;">
									  <li><a href="employee-edit-profile.php"><i class="icon-user"></i> Edit Profile</a></li>
									  <li><a href="change-password.php"><i class="icon-lock"></i> Change Password</a></li>
									   <li><a href="sign-out.php"><i class="icon-off"></i> Sign Out</a></li>
									</ul>
								</li>
							</ul>
						</div><!-- /.nav-collapse -->
					</div>
				</div>
			</div>
		</div>
	</div>
EOT;

return $content;
}
?>