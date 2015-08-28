<!-- START MODAL-->
<div id="resetPasswordModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height:150px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
   <!-- <h3 id="myModalLabel">Sign Up For Account</h3>-->
   <h4 class="content-heading4">SEARCH ACCOUNT</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12" style="padding-top:20px;">
			<div class="row-fluid">
				<div class="span11 offset1">
					<small><span class="text-info" style="font-weight:bold; margin-left:70px;">EMAIL ADDRESS or MOBILE NUMBER</span></small>
					  <input type="text" class="span9 search-query" id="searchAcct">
					  <button type="submit" class="btn btn-primary" onclick="findAccount();" required>SEARCH</button>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span11 offset1">
					
					<span class="text-error" id="noAccountResult" style="display:none;"><strong>Error!</strong>  Please enter a valid account information.</span>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->