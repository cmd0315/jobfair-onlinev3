<?php
function generateEmployerNavBar($username){
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
						<a class="brand dropdown-toggle" data-toggle="dropdown" style="padding-left:46px;" href="employer-edit-profile.php">
EOT;
						$status = getAcctInfo($username, "status");
						$companyName = getEmployerData($username, "company-name");
						$profilePic = getEmployerData($username,"profile_pic");
						$profilePicDisplay = "<img src='" . $profilePic . "' class=\"img-circle navbar-image\">";
						$content .= $profilePicDisplay . $companyName;
	$content .=<<<EOT
						</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-briefcase icon-white"></i> JOB POSTS <b class="caret" ></b></a>
									<ul class="dropdown-menu" style="color:white;">
										  <li><a href="employer-manage-jobposts.php">View Job Posts</a></li>
										  <li><a href="add-job-post.php">Add Job Post</a></li>
									</ul>
								</li>
EOT;
								if($status === "SRI Branch Manager"){
									$content .= "<li id=\"uploaddata\" name=\"uploaddata\"><a href=\"upload-csv.php\" rel=\"tooltip\" data-placement=\"bottom\" title=\"Upload .csv File to Website Database\"><i class=\"icon-hdd icon-white\"></i> IMPORT DATA</a></li>";
								}
	$content .=<<<EOT
							</ul>
							<form class="navbar-form pull-right" method="GET" action="employer-search-page.php" style="margin-top:-1px;">
								<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Applicant Name/ Applicant Location" style="height: 20px !important;">
								<button type="submit" class="btn btn-info btn-small"><i class="icon-search icon-white"></i></button>
							</form>
							<ul class="nav pull-right">
								<li>
									<a href="employer-sitemap.php"><i class="icon-home icon-white"></i></a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i><b class="caret" ></b></a>
									<ul class="dropdown-menu" style="color:white;">
									  <li><a href="employer-edit-profile.php"><i class="icon-user"></i> Edit Profile</a></li>
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