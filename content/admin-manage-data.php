<?php
include './includes/functions.php';
include './includes/db.php';

/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

$maxFileSize = 350;//in KB

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
<style type="text/css">
	.filterOption, input[type="number"]{
		font-size: 11.5px;
	}
	select[size] {
		height: auto;
		width: 100px;
	}
	#filterLabel{
		color: black;
		font-size: 11.5px;
		font-weight: normal;
	}
</style>
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
	<!-- START SEARCH NAVBAR  -->
	<?php echo $searchBar=getSearchBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">DATABASE</h4>
						<div class="tabbable">
							<ul class="nav nav-tabs nav-pills">
								<li class="active"><a href="#lA" data-toggle="tab">Export Database</a></li>
								<li><a href="#lB" data-toggle="tab">Import Database</a></li>
								<li><a href="#lC" data-toggle="tab">Activity Logs</a></li>
							</ul>
							<div class="tab-content" style="overflow:hidden;">
								<div class="tab-pane active" id="lA">
									<div class="row-fluid">
										<div class="span12 form-horizontal">
											<div class="control-group">
												<label class="control-label">Table Name</label>
												<div class="controls">
													<select class="span8" id="tableName" name="tableName">
														<option>Applicant</option>
														<option>Employer</option>
													</select>
													<button type='button' class='btn btn-primary span2' name='exportBtn' id='exportBtn' onclick='exportTable();'>Export</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<span class="flash"></span>
										<div class="span12" id="exportResults"><!-- print results here from process-export.php--></div>
									</div>
								</div>
								<div class="tab-pane" id="lB">
									<form name="import-form" id="import-form" class="form-horizontal" method="post" action="process-import-csv.php" enctype="multipart/form-data">
										<div class="row-fluid">
											<p>Upload downloaded csv file:</p>
											<div class="span8">
												<div class="control-group">
													<label class="control-label">File Name</label>
													<div class="controls">
														<input type="file" name="importedFile" id="importedFile" required/>
													</div>
												</div>
											</div>
											<div class="span2">
												Max. size: <?php echo $maxFileSize . "KB"; ?>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span2 offset2">
												<input type='submit' class='btn btn-primary span12' name='importBtn' id='importBtn' value='Import'/>
											</div>
										</div><br/>
										<div class="row-fluid">
											<div class="span8 offset2">
												<div class="percent"></div>
												<div class="progress progress-striped">
												  <div class="bar" style="width: 0%;"></div>
												</div>
												<div class="span12" id="importResults"><!-- print results here from self--></div>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane" id="lC">
									<div class="row-fluid">
										<div class="span12">
											<span class="flash3"></span>
											<div id="logResults"><!-- print results here from process-export.php--></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $webAdminName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
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
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/jquery.form.js"></script>
<script>
purgingExportFile = 0;
$(document).ready(function(){
	$("#tableName").select2();
	$("#tableName2").select2();

	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#importResults');

	$('#import-form').ajaxForm( { 
		beforeSend: function(){
			status.empty();
			var percentVal = '0%';
			bar.show();
			bar.width(percentVal)
			percent.html(percentVal);
		},
		beforeSubmit: validateForm,
		uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    success: function() {
	        var percentVal = '100%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
		complete: function(xhr) {
			status.html(xhr.responseText);
			//$('#csvUploadedModal').modal('show');
		}
	}); 

	$('#importedFile').change(function(){
		status.empty();
		bar.hide();
		percent.empty();
	});

	changePagination('0');
	if(purgingExportFile == 1){
		alert("yes!");
	}
	
});

/*modified from http://malsup.com/jquery/form/#validation */
function validateForm(formData, jqForm, options) { 
    // jqForm is a jQuery object which wraps the form DOM element 
    var form = jqForm[0]; 
    if (!form.importedFile.value) { 
        alert('Please submit a file'); 
        return false; 
    } 
}

//export table function
function exportTable(){
	var tableNameList = document.getElementById("tableName");
	var tableName = tableNameList.options[tableNameList.selectedIndex].text;
	var dataString = 'tableName='+ tableName;
	$.ajax({
	   type: "GET",
	   url: "process-export-csv.php",
	   data: dataString,
	   cache: false,
	     beforeSend: function() {
         $(".flash").fadeIn(400).html('Exporting database table ... <img src="./img/ajax-loader.gif" />');
       },
	   success: function(result){
			$(".flash").hide();
			$("#exportResults").html(result);
	   }
	});
}

//import table function
function importTable(){
	$("input:file").change(function (){
       var fileName = $(this).val();
       $("#importedFile").html(fileName);

    });
    alert(fileName);
}

//for activity logs
function changePagination(pageNum){
	var resultsFileURL = "reports-admin-logs.php";
	$(".flash3").show();
	$(".flash3").fadeIn(400).html
	        ('Please wait while data is being processed. <img src="./img/ajax-loader.gif" />');
	var dataString = 'pageNum='+ pageNum;
	$.ajax({
	   type: "GET",
	   url: resultsFileURL,
	   data: dataString,
	   cache: false,
	   success: function(result){
	   		$(".flash3").hide();
	        $("#logResults").html(result);
	   }
	});
}

function exportLog(){
	$.ajax({
	   type: "GET",
	   url: "activity-logs-sheet.php",
	   cache: false,
	   beforeSend: function() {
         $(".flash3").fadeIn(400).html('Downloading list... <img src="./img/ajax-loader.gif" />');
       },
	   success: function(result){
	   	$(".flash3").hide();
	      window.location.href = "activity-logs-sheet.php";
	   }
	});
}

function downloadExportedFile(link){
	window.location.href = link;
	purgingExportFile = 1;
}
</script>
<!-- JS scripts -->
</body>
</html>