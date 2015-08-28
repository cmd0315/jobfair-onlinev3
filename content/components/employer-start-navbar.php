<?php
function generateNavBar(){
$content = "";

$content =<<<EOT
<div class="row-fluid">
	<div class="span10 offset1">
		<div class="navbar">
			<div class="navbar-inner" id="home-sign-buttons">
				<div class="container">
					<a class="brand" href="./index.php" style="padding-left:46px;">Hi, Employer!</a>
					<ul class="nav pull-right">
						<li class="dropdown" style="border-right:1px solid #C2CAD8;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">SIGN IN</a>
							<ul class="dropdown-menu">
							 <!-- START LOGIN BOX  -->
EOT;
							$content .= getSignInBox();
$content .=<<<EOT
							<!-- END LOGIN BOX-->
							</ul>
						  </li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">SIGN UP</a>
								<ul class="dropdown-menu" style="margin: 0px -11px 0; padding:20px;">
								  <!-- START LOGIN BOX  -->
EOT;
									$content .= getSignUpBoxEmployer();
$content .=<<<EOT
									<!-- END LOGIN BOX-->
								</ul>
						  </li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
EOT;

echo $content;

}
?>