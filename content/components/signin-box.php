<div class="row-fluid">
	<div class="span10 offset1">
		<h6 style="color:white;"><center>Your information will be private and will not be posted without your permission.</center></h6>
	</div>
</div>
<div class="row-fluid">
	<div class="span12" style="margin-top:5px;">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span10 offset1">
					<!-- START LOGIN FORM-->
					<form name="login-form" id="login-form" method="POST" action="./login.php">
						<div class="control-group">
							<label class="control-label" for="username"></label>
							<div class="controls">
								<input type="text"  class="span12" id="username" name="username" onfocus="removeMsg();" placeholder="USERNAME" style="background-color:white; font-weight:bold" required />
								<p class="help-block" style="font-size:11.5px; background-color:skyblue;"></p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="password"></label>
							<div class="controls">
								<input type="password"  class="span12" id="password" name="password" onfocus="removeMsg();" minlength="8" placeholder="PASSWORD" style="background-color:white; font-weight:bold" required />
								<p class="help-block" style="font-size:11.5px; background-color:skyblue;"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12" >
						<div class="row-fluid">
							<div class="span12">
								<div class="row-fluid">
									<div class="span5 offset1">
										<h6><a id="resetPasswordLink" style="cursor:pointer; color:white;">Forgot password?</a></h6>
									</div>
									<div class="span3 offset2">
										<button type="submit" class="btn btn-primary span12" id="go-btn"name="go-btn">GO</button> 
									</div>
								</div>
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