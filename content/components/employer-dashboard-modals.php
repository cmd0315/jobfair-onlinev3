<!-- START EDIT ERROR MODAL-->
<div id="errorModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">No Job Post Selected</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="modalContent" style="text-align:center;">Please select a job post to be edited.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END EDIT ERROR MODAL -->
<!-- START STOP POST LISTING MODAL-->
<div id="stopModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Close Job Listing</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="stopModalContent" style="text-align:center;"></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span8 offset2">
			<button class="btn span6" data-dismiss="modal">CANCEL</button>
			<button class="btn btn-primary span6" onclick="stopJP2();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END STOP POST LISTING MODAL -->
<!-- START STOPPED LISTING MODAL-->
<div id="stoppedModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Post Listing Closed!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="stopModalContent" style="text-align:center;">You have successfully closed the listing of this job post.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" onclick="reload();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END STOPPED LISTING MODAL -->
<!-- START ERROR MODAL FOR ALREADY CLOSED/REMOVED JOB POSTS-->
<div id="errorModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="errorModalTitle2"></h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="errorModalContent2" style="text-align:center;"><span class="text-info"></span></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" data-dismiss="modal" >OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END ERROR MODAL FOR ALREADY CLOSED/REMOVED JOB POSTS-->
<!-- START REMOVE POST LISTING MODAL-->
<div id="removeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Remove Job Post</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="removeModalContent" style="text-align:center;"></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span8 offset2">
			<button class="btn span6" data-dismiss="modal">CANCEL</button>
			<button class="btn btn-primary span6" onclick="removeJP2();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END REMOVE POST LISTING MODAL -->
<!-- START REMOVED JOB POST MODAL-->
<div id="removedModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Post Removed!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p id="stopModalContent" style="text-align:center;">You have successfully removed the job post.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" onclick="reload();">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END  REMOVED JOB POST MODAL -->