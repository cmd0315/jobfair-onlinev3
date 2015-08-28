<?php
function generateAdminNavBar($username){

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
					<a class="brand dropdown-toggle" style="padding-left:46px;" href="admin-edit-profile.php">
EOT;
					$fullName = getWebAdminData($username, "full-name");
					$profilePic = "./img/id.png";
					$profilePicDisplay = "<img src='" . $profilePic . "' class=\"img-circle navbar-image\">";
					$content .= $profilePicDisplay . $fullName;
$content .=<<<EOT
					</a>
					<div class="nav-collapse collapse navbar-responsive-collapse">
						<ul class="nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> REPORTS <b class="caret" ></b></a>
								<ul class="dropdown-menu" style="color:white;">
									  <li><a href="admin-reports.php">Sign-ups (Applicant)</a></li>
									  <li><a href="admin-report-sers.php">Sign-ups (Employer)</a></li>
									   <li><a href="admin-report-jposts.php">Jobs Posted</a></li>
										<li><a href="admin-report-japps.php">Job Applications</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt icon-white"></i> QUALIFIED USERS <b class="caret" ></b></a>
								<ul class="dropdown-menu" style="color:white;">
									  <li><a href="admin-search-employees.php">Applicants' Job Interests</a></li>
									  <li><a href="admin-search-employers.php">Employers' Job Positions</a></li>
								</ul>
							</li>
							<li>
								<a href="admin-jobfairs.php"><i class="icon-globe icon-white"></i> JOB FAIRS</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-briefcase icon-white"></i> JOB POSITIONS <b class="caret" ></b></a>
								<ul class="dropdown-menu" style="color:white;">
								   <li><a href="edit-job-positions.php">Manage Job Positions</a></li>
								   <li><a href="add-jobposition-requests.php">Job Position Requests</a></li>
								</ul>
							</li>
							<li>
								<a href="admin-manage-data.php"><i class="icon-hdd icon-white"></i> DATA</a>
							</li>
						</ul>
						<form class="navbar-form pull-right" method="GET" action="search-page.php" style="margin-top:-1px;">
							<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Applicants, Employers, Job Posts" style="height: 20px !important;">
							<button type="submit" class="btn btn-info btn-small"><i class="icon-search icon-white"></i></button>
						</form>
						<ul class="nav pull-right">
							<li>
								<a href="admin-sitemap.php"><i class="icon-home icon-white"></i></a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i><b class="caret" ></b></a>
								<ul class="dropdown-menu" style="color:white;">
								  <li><a href="admin-edit-profile.php"><i class="icon-user"></i> Edit Profile</a></li>
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