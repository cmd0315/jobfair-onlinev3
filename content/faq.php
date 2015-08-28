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
<link rel="stylesheet" href="./leaflet/dist/leaflet.css"/>
<!-- [if lte IE 8]>
	<link rel="stylesheet" href="./leaflet/dist/leaflet.ie.css"/>
<![endif] -->
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
			<div class="span8 well offset2"> 
				<h4 class="content-heading">FAQ - Frequently Ask Question</h4>
				<div class="row-fluid">
					<div class="span12"> 
						<dl>
							<dt><i class="icon-question-sign"></i> How do I sign up for an account?</dt>
							<dd>You can sign up using your mobile phone number and email address, or your Facebook account. </dd><br/>
							<dd style="font-style:italic;">- If you have a Facebook account:</dd>
							<dd>
								<ol>
									<li>Log in to your Facebook account using the link found of the JobFair-Online.Net homepage.</li>
									<li>Type in your chosen password (x number of characters) and re-type it in the boxes provided.</li>
									<li>Fill in the applicant/employer form with the proper information. Spaces and boxes are provided for easy typing.</li>
									<li>Click the “Submit Form” button at the bottom of the applicant/employer form page.</li>
									<li>You should now be able to log in using your JobFair-Online.Net account!</li>
								</ol>
							</dd><br/>
							<dd style="font-style:italic;">- If you don't have a Facebook account:</dd>
							<dd>
								<ol>
									<li>On the sign-up page, input your mobile phone number (09xx-xxx-xxxx format).</li>
									<li>Type in your chosen password (x number of characters) and re-type it in the boxes provided.</li>
									<li>Fill up the applicant/employee form with the proper information. Spaces and boxes are provided for easy typing.</li>
									<li>Click the “Submit Form” button at the bottom of the applicant/employer form page.</li>
									<li>Choose the method of receiving the verification code:</li>
									<ul>
										<li>Receive an email with the link to the verification page</li>
										<li>Receive a text message with the verification code</li>
									</ul>
									You should be able to receive a text or email within a few minutes.
									<li>Upon receiving a verification text or email, click the “Verify Account button.”</li>
									<ul>
										<li>If you received a text message, enter the code into the box provided.</li>
										<li>If you received an email, clink the link to take you to the log-in page. </li>
									</ul>
									<li>You should now be able to log in using your JobFair-Online.Net account!</li>
								</ol>
							</dd>
							<dt><i class="icon-question-sign"></i> How do I log in to my account?</dt>
							<dd>The “Sign In” link is located at the top-right corner of the page. Clicking it will reveal a log-in box where you can input the details needed.</dd><br/>
							<dt><i class="icon-question-sign"></i> I forgot my password! What should I do?</dt>
							<dd>
								<ol>
									<li>Click the “Sign In” button on the homepage.</li>
									<li>In the “Sign In” drop-down menu, click “Forgot Password?”</li>
									<li>Input your user name (email address for Facebook registration, mobile number for email registration) and click “Search.”</li>
									<li>Choose the method of receiving the verification code for password reset:</li>
									<ul>
										<li>Receive an email with the link to the verification page</li>
										<li>Receive a text message with the verification code</li>
										You should be able to receive a new verification code within a few minutes.
									</ul>
									<li>Click “Continue.”</li>
									<li>In the dialog box that will appear, click “Reset Password.”</li>
									<li>In the box provided, enter the verification code then click “Continue.”</li>
									<li>Enter and re-type your new password, then click the “Reset” button.</li>
									<li>In the dialog box that will appear, you can choose “OK” to change the inputted password, or “Log In” to your account.  </li>
								</ol>
							</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<!-- Validate plugin -->
<script src="./js/bootstrap.min.js"></script>
</body>
</html>