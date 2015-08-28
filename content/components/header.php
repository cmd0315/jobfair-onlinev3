<?php
$content = "";
function generateHomePageHeader($userType){
	$content = "<div class=\"row-fluid\" id=\"site-title-box\" name=\"site-title-box\">
			<div class=\"span10 offset1\" id=\"site-header\" name=\"site-header\">
				<div class=\"row-fluid\">
					<div class=\"span12\" style=\"margin-left:2.547009%\">
						<div class=\"row-fluid\">
							<div class=\"span5\">
								<div class=\"row-fluid\">
									<div class=\"span12\">
										<a href='" . $_SERVER['PHP_SELF'] . "'><img src=\"./img/jobfair-online.png\" style=\"cursor:pointer;\" onclick=\"window.location.href='./index.php';\"/></a>
									</div>
								</div>
							</div>
							<div class=\"span4 offset3\" style=\"padding-right:55px;\">
								<div class=\"row-fluid\">";

	if($userType === "Applicant"){
		$content .= "<p style=\"text-align:right;\">Employer's <strong><a href=\"employer-page.php\" style=\"text-decoration:underline;\">Page</a></strong> here</p>";
	}
	else if($userType === "Employer"){
		$content .= "<p style=\"text-align:right;\">Applicant's <strong><a href=\"index.php\" style=\"text-decoration:underline;\">Page</a></strong> here</p>";
	}
	else if($userType === "Default" || $userType === ""){
		$content .= "";
	}

		$content .=	"			</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>";
	return $content;
}
?>