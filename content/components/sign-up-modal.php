<!-- START MODAL-->
<div id="signUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
   <!-- <h3 id="myModalLabel">Sign Up For Account</h3>-->
   <h4 class="content-heading4">Sign up for Account</h4>
  </div>
  <div class="modal-body">
  <!-- START CREATE ACCOUNT -->
    <form name="signup-form" id="signup-form" class="form-horizontal" name="sign-up-form" method="POST" action="sign-up.php">
		<div class="row-fluid">
			<div class="span12" style="padding-left:20px;">
				<div class="control-group">
					<label class="control-label" for="inputEmployee">Status</label>
					<div class="controls" style="margin-left:-5px;">
						<label class="radio inline" id="gender-option-label" style="margin-left:20px;"><input type="radio" name="status" id="inputEmployee" name="status" value="Employee" required>Applicant</label>
						<label class="radio inline" id="gender-option-label"><input type="radio" name="status" id="inputEmployer" name="status" value="Employer" required>Employer</label>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span10">
						<div class="control-group">
							<label class="control-label" for="inputMobile">Mobile No <br><span style="font-weight:normal;">(e.g. 0915-111-1111)</span></label>
							<div class="controls control-row">
								<input class="span4" type="text" id="inputMobile1" name="inputMobile1" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required><span style="font-weight:bold;"> -</span>
								<input class="span3" type="text" id="inputMobile2" name="inputMobile2" maxlength="3" minlength="3" pattern="[0-9]*" onfocus="removeMsg();" required><span style="font-weight:bold;"> -</span>
								<input class="span4" type="text" id="inputMobile3" name="inputMobile3" maxlength="4" minlength="4" pattern="[0-9]*" onfocus="removeMsg();" required>
								<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
							</div>
						</div>
					</div>
				</div>
				  <div class="control-group">
					<label class="control-label" for="inputPassword">Password</label>
					<div class="controls">
					  <input type="password" id="inputPassword" name="inputPassword" minlength="8" placeholder="Password" required>
					  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputRepeatPassword">Re-type Password</label>
					<div class="controls">
					  <input type="password" id="inputRepeatPassword" name="inputRepeatPassword" placeholder="Re-type Password" data-validation-match-match="inputPassword" required>
					  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span10 offset2" style="margin-top:-10px;">
						<a id="resendVCodeLink" class="text-error" style="font-size:14px; cursor:pointer;" onclick="resendVCode();">Re-send verification code</a>
					</div>
				</div>
			</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span8 offset2">
			<button class="btn span6" data-dismiss="modal" aria-hidden="true">CANCEL</button>
			<input type="submit" class="btn btn-primary span6" id="signupBtn" name="signupBtn" value="CREATE ACCOUNT"/>
		</div>
	</div>
  </div>
  </form>
  <!-- END CREATE ACCOUNT -->
</div>
<!-- END MODAL -->