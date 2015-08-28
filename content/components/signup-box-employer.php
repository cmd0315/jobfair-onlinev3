<div class="row-fluid">
	<div class="span12">
		<h4 style="color:white; text-decoration:none;">Sign up for Account</h4>
	</div>
</div>
<!-- START SIGNUP FORM-->
	<form name="signup-form" id="signup-form" name="sign-up-form" method="POST" action="sign-up.php">
		<div class="control-group">
			<div class="row-fluid">
				<div class="span4">
					<label class="control-label" for="inputMobile" style="color:white; text-align:right; margin-top: 10px;">MOBILE NO.</label>
				</div>
				<div class="span8">
					<div class="controls control-rows">
						<input class="span4" type="text" id="inputMobile1" name="inputMobile1" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" style="font-weight:bold; background-color:white;" required>
						<input class="span3" type="text" id="inputMobile2" name="inputMobile2" maxlength="3" minlength="3" pattern="[0-9]*" onfocus="removeMsg();" style="font-weight:bold; background-color:white;" required>
						<input class="span4" type="text" id="inputMobile3" name="inputMobile3" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" style="font-weight:bold; background-color:white;" required>
						<p class="help-block" style="font-size:11.5px; background-color:skyblue;"></p>
					</div>
				</div>
			</div>
		</div>
	  <div class="control-group">
		<div class="row-fluid">
			<div class="span4">
				<label class="control-label" for="inputPassword" style="color:white; text-align:right; margin-top: 10px;">PASSWORD</label>
			</div>
			<div class="span8">
				<div class="controls">
				  <input type="password" class="span11" id="inputPassword" name="inputPassword" minlength="8" placeholder="PASSWORD" style="font-weight:bold; background-color:white;" required>
				  <p class="help-block" style="font-size:11.5px; background-color:skyblue;"></p>
				</div>
			</div>
		</div>
	</div>
	<div class="control-group">
		<div class="row-fluid">
			<div class="span4">
				<label class="control-label" for="inputRepeatPassword" style="color:white; text-align:right;">RE TYPE PASSWORD</label>
			</div>
			<div class="span8">
				<div class="controls">
					<input type="password" class="span11" id="inputRepeatPassword" name="inputRepeatPassword" placeholder="RE TYPE PASSWORD" data-validation-match-match="inputPassword" style="font-weight:bold; background-color:white;" required>
					<p class="help-block" style="font-size:11.5px; background-color:skyblue;"></p>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="row-fluid">
		<div class="span8 offset4" >
			<a id="resendVCodeLink" style="font-size:12px; cursor:pointer; text-decoration:underline; color:white; padding-left:46px;" onclick="resendVCode();">Re-send verification code</a>
		</div>
	</div>-->
	<input type="hidden" id="status" name="status" value="Employer"/>
	<div class="row-fluid" style="padding-left:10px;">
		<div class="span7 offset4">
			<input type="submit" class="btn btn-primary span12 pull-right" id="signupBtn" name="signupBtn" value="CREATE ACCOUNT" style="font-weight:bold;"/>
		</div>
	</div>
	<div class="row-fluid" style="padding-top:10px; margin-bottom:-15px;">
		<div class="span10 offset1">
			<center><fb:login-button v="2" size="large" scope= "email,user_birthday,user_hometown, user_religion_politics, user_relationships">Sign up with Facebook</fb:login-button>
			<div id="fb-root"></div></center>
		</div>
	</div>
  </form>
<!-- END SIGNUP FORM-->