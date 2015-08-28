<div class="row-fluid">
	<div class="span10 offset1">
		<h6 style="font-weight:bold;"><center>Your information will be private and will not be posted without your permission.</center></h6>
	</div>
</div>
<div class="row-fluid">
	<div class="span10" style="margin-top:5px;">
		<div class="row-fluid">
			<div class="span10 offset1">
				<div class="row-fluid">
					<div class="span9 offset3">
					<!-- START LOGIN FORM-->
					<form name="login-form" id="login-form" method="POST" action="./login.php">
						<div class="control-group">
							<label class="control-label" for="mobile">USERNAME:</label>
							<div class="controls">
								<input type="text"  class="span12" id="username" name="username" required />
								<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="password">PASSWORD:</label>
							<div class="controls">
								<input type="password"  class="span12" id="password" name="password" minlength="8" required />
								<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span9 offset3" >
						<div class="row-fluid">
							<div class="span12">
								<div class="row-fluid">
									<div class="span6">
										<a id="resetPasswordLink" class="text-error" style="font-size:12px; cursor:pointer;">Forgot password?</a>
									</div>
									<div class="span5 offset1">
										<button type="submit" class="btn btn-primary span12" id="go-btn"name="go-btn">GO</button> 
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12" style="margin-top:-10px;">
								<h5><a id="signUpLink"><span class="sign-up">Sign up!</span></a></h5>
							</div>
						</div>
					</div>
				</div>
				</form>
				<!-- END LOGIN FORM-->
			</div>
		</div>
	</div>
</div>