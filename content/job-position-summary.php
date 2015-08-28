<?php
include './includes/db.php';

$jobPositionId = $_GET['jobPositionId'];
$jobPosition = getJobPositionName($jobPositionId);
$content = "";

$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Job Position</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid\">
							<div class=\"span12\">
								<input type=\"text\" id=\"editJobPosition\" name=\"editJobPosition\" class=\"span12\" value=\"$jobPosition\" />
							</div>";
$content.="	</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">
							<div class=\"span4 offset2\">
								<button class=\"btn btn-primary extraBtn span12\" id=\"editBtn\" name=\"editBtn\" onclick=\"saveJobPosition('$jobPositionId');\">SAVE</button>
							</div>
							<div class=\"span4\">
								<button class=\"btn btn-primary extraBtn span12\" id=\"deleteBtn\" name=\"deleteBtn\" onclick=\"deleteJobPosition('$jobPosition');\">DELETE</button>
							</div>
						</div>
					</div>";
echo $content;
?>