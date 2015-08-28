<?php
include './includes/functions.php';

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
	<!-- START NAVBAR -->
	<?php echo $navbar=getGeneralNavBar(); ?>
	<!-- END NAVBAR -->
	<div class="container-fluid" style="padding-top:30px;">
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">Contact Us</h4>
				<p class="text-info" style="font-weight:bold;">*Required fields</p>
				<div class="row-fluid">
					<!-- START ENQUIRY FORM -->
					<form name="enquiry-form" id="enquiry-form" method="POST" action="process-send-enquiry.php">
						<div class="span12">
							<div class="row-fluid">
								<div class="span10 offset1">
									<div class="control-group">
										<label class="control-label" for="enquiry">*HOW MAY WE HELP YOU?</label>
										<div class="controls" >
											<select class="span12" id="enquiry" name="enquiry" required>
												<option>General Inquiries</option>
												<option>Job Availability</option>
												<option>Business Opportunity</option>
											</select>
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									  </div><br/>
									  <div class="control-group">
											<label class="control-label" for="name" style="padding-top:10px;">*NAME</label>
											<div class="controls">
												<input class="span12" type="text" id="name" name="name" placeholder="Name" minlength="2" required>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
									  </div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span5 offset1">
									   <div class="control-group">
											<label class="control-label" for="contact_num">*MOBILE NO.</label>
											<div class="controls">
												<input type="text" class="span3" id="mobile1" name="mobile1" pattern="[0-9]*" minlength="4" maxlength="4" required /> -
												<input type="text" class="span3" id="mobile2" name="mobile2"  pattern="[0-9]*" minlength="3" maxlength="3" required /> -
												<input type="text" class="span3" id="mobile3" name="mobile3" pattern="[0-9]*" minlength="4" maxlength="4"  required />
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
									  </div>
									</div>
									<div class="span5 offset1">
									   <div class="control-group">
											<label class="control-label" for="landline1">LANDLINE NO.</label>
											<div class="controls">
												<input type="text" class="span3" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" /> -
												<input type="text" class="span3" id="landline2" name="landline2"  pattern="[0-9]*" minlength="2" maxlength="2" /> -
												<input type="text" class="span3" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" />
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
									  </div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span10 offset1">
									  <div class="control-group">
											<label class="control-label" for="email">*EMAIL ADDRESS</label>
											<div class="controls">
												<input class="span12" type="email" id="email" name="email" placeholder="Email Address" required>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
									  </div>
									  <div class="control-group">
											<label class="control-label" for="msg">*MESSAGE</label>
											<div class="controls">
												<textarea class="input-block-level" id="msg" name="msg" minlength="10" value="" required></textarea>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
									  </div>
								</div>
							</div><br/>
						</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span2 offset7">
				<button type="submit" class="btn btn-primary job-post-add span12" id="submitMsg" name="submitMsg">SEND MESSAGE</button>			
				</form>
				<!-- END ENQUIRY FORM -->
			</div>
		</div>
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
  <div class="modal-header">
    <h4 class="content-heading4">Message Sent!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span10 offset1">
			   <p style="text-align:center">Thank you! Your email has been sent. We will contact you immediately.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="spa8 offset4">
			<button class="btn btn-primary span6" id="resendVCode" name="resendVCode" data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
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
	//$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	$("#enquiry").select2(); 
});
</script>
<script>
$(document).ready(function() { 
	// bind to the form's submit event 
	$('#enquiry-form').on('submit', function(e) {
		e.preventDefault(); // <-- important
		$(this).ajaxSubmit();
		$("#myModal").modal('show');
    });
});
</script>
</body>
</html>