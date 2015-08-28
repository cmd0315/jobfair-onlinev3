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


//set Profile Box Info (if session variables not available)
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");

$maxFileSize = 350; //in KB
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
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getEmployerNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span7 offset1">
				<div class="row-fluid">
					<h4>To import data, upload a formatted <em>.csv</em>  file.</h4><br>
				</div>
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">IMPORT DATA</h4>
						<form name="import-form" id="import-form" class="form-horizontal" method="post" action="process-import-csv.php" enctype="multipart/form-data">
							<div class="row-fluid">
								<p>Upload the downloaded csv file:</p>
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
				</div>
			</div>
			<div class="span3">
				<div class="row-fluid" style="height:30px;"></div>
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $coName, $location, $profilePic);?>
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
<script src="./js/jquery.form.js"></script>
<script>
$(document).ready(function() { 
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
});

/*modified from http://malsup.com/jquery/form/#validation */
function validateForm(formData, jqForm, options) { 
    // jqForm is a jQuery object which wraps the form DOM element 
    // 
    // To validate, we can access the DOM elements directly and return true 
    // only if the values of both the username and password fields evaluate 
    // to true 
 
    var form = jqForm[0]; 
    if (!form.importedFile.value) { 
        alert('Please submit a file'); 
        return false; 
    } 
}

//import table function
function importTable(){
	$("input:file").change(function (){
       var fileName = $(this).val();
       $("#importedFile").html(fileName);

    });
    alert(fileName);
}
</script>
<!-- JS scripts -->
</body>
</html>