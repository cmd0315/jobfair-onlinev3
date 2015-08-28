<?php
	include './includes/db.php';
	$jobPositions = getJobPositions();
?>

<div class="row-fluid" style="margin-top:10px;">
	<div class="span12 well">
		<h4 class="content-heading">What job are you interested in?</h4>
		<div class="row-fluid">
			<div class="span10 offset1">
					<select class="span12" id="jobTitle" name="jobTitle" data-placeholder="Choose a job title" onchange="displayJobs();">
						<?php
							foreach($jobPositions as $jP) {
								echo "<option value=\"$jP\">$jP</option>";
							}
						?>
					</select>
				</div>
		</div>
	</div>
</div>