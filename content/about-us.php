<?php
include './includes/functions.php';
$t = $_GET['t'];
$t1Active="";
$t2Active="";
$t3Active="";
if($t=='t1'){
	$t1Active="active";
	$t2Active="";
	$t3Active="";
}
else if($t=='t2'){
	$t2Active="active";
	$t1Active="";
	$t3Active="";
}
else if($t=='t3'){
	$t3Active="active";
	$t1Active="";
	$t2Active="";
}
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
				<h4 class="content-heading">ABOUT US</h4>
				<div class="row-fluid">
					<div class="tabbable"> <!-- Only required for left/right tabs -->
					  <ul class="nav nav-tabs">
						<li class="<?php echo $t1Active;?>"><a href="#tab1" data-toggle="tab">JobFair-Online.Net, Inc.</a></li>
						<li class="<?php echo $t2Active;?>"><a href="#tab2" data-toggle="tab">Services Resources, Inc.</a></li>
						<li class="<?php echo $t3Active;?>"><a href="#tab3" data-toggle="tab">Central Services Integrated Cooperative</a></li>
					  </ul>
					  <div class="tab-content">
						<div class="tab-pane <?php echo $t1Active;?>" id="tab1">
						  <p>JobFair-Online.Net is a website to help match applicants looking for jobs with companies looking for workers. Its goal is to help the unemployed get a job as quickly as possible, so that they can start becoming productive members of society. This website is free of charge, and is operated as a public service by Service Resources Inc and CSI Cooperative.</p><br>
						  <p>Send us your inquiries: <strong><a href="./contact-us.php">support@jobfair-online.net</a></strong></p>
						</div>
						<div class="tab-pane <?php echo $t2Active;?>" id="tab2">
							<p>Established in August 1979, our company continues to be one of the leading manpower service companies in the country. With clients in many local and multinational corporations for various job levels, our steady guiding principle is to serve our client firms in their manpower outsourcing needs as efficiently and economically as possible consistent with all existing laws and regulations. Our success throughout these years demonstrates the soundness of our goals.</p> 
							<p>Founder of SRI is Atty. Jose T. Alberto with over twenty years experience in corporate management-labor relations. Management counts with it a tightly knit team of dedicated individuals, each an expert in his or her field such as in marketing, recruitment and selection of manpower, worker supervision, labor cost accounting, payroll management, client relations, and labor regulations. </p>
							<p>At SRI, we listen to our clients’ needs to further enhance our business partnership. We develop systems and procedures that suit the unique requirements of each individual client, translating into an integrated and flexible approach to all our business solutions. </p>
							<p>We believe in excellent customer service. It has always been our culture to deliver conscientious quality assistance, professional expertise and support in satisfying our clients’ manpower needs with personnel that meet their work standards when and where needed at the most economical rates. </p>
							<p>To further galvanize our commitment in bringing quality service and deliver much more than the expectations of our clients, we are happy to announce that Service Resources, Inc. now has an ISO 9001:2008 Certified Quality Management System in place to further assure the quality of services rendered. </p>
							<p>With continued positive outlook at the Philippine economy, SRI looks forward with confidence in contributing to the national economic prosperity and provide meaningful employment opportunities to thousands of Filipinos by continuing to serve the manpower outsourcing industry.</p>
							<br>
							<p><strong>Special Features of Our Manpower Outsourcing Service</strong></p>
							<ol>
								<li>We spare our clients from the normal routine of recruitment which may begin with expensive advertisement to rigorous interviews, Psychological testing and selections;</li>
								<li>We conduct/administer preliminary qualifying tests for short listing of candidates, which is the main part of the selection procedure. Candidates who do not have the required level of intellectual ability or who possess personality traits that are inconsistent with the kind of work specified are disqualified based on results of psychological testing, interviews and background investigation;</li>
								<li>We adopt rigid and comprehensive medical examinations for our employees conducted by very competent medical practitioners to assure our clients that the employee is physically fit to work;</li>
								<li>Our clients shall be free from keeping and maintaining employment records of the contractual employee and shall likewise be spared from assuming the responsibility for payroll administration and processing of employee benefits and compensation claims from SSS, PhilHealth and Pag-Ibig.</li>
								<li>We, as the employer, are responsible for personnel supervision over our contractual employees. Our clients need not worry about timekeeping, monitoring of personnel demeanor, dealing with violations and issuance of the corresponding sanctions.</li>
								<li>During the assignment of our contractual employee to our clients, they are given a window to further observe his/her capabilities. Our client has the option to absorb the contractual employee at a minimal service fee equivalent to One (1) month salary that the contractual employee is receiving. However, hiring of the contractual employee on permanent basis shall be “free of charge” upon completion of his/her contract with us.</li>
								<li>We charge very minimal administrative fee in exchange for unburdening Your Company of tedious human resource and administrative processes that you may very well opt to outsource.</li>
							</ol><br>
							<iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.ph/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Service+Resources+Inc.,+Pasig+City,+Metro+Manila&amp;aq=0&amp;oq=Service+Res&amp;sll=12.07895,121.95925&amp;sspn=31.869313,53.569336&amp;ie=UTF8&amp;hq=Service+Resources+Inc.,&amp;hnear=Pasig+City,+Metro+Manila&amp;t=m&amp;ll=14.577104,121.06307&amp;spn=0.039873,0.054932&amp;z=14&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com.ph/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Service+Resources+Inc.,+Pasig+City,+Metro+Manila&amp;aq=0&amp;oq=Service+Res&amp;sll=12.07895,121.95925&amp;sspn=31.869313,53.569336&amp;ie=UTF8&amp;hq=Service+Resources+Inc.,&amp;hnear=Pasig+City,+Metro+Manila&amp;t=m&amp;ll=14.577104,121.06307&amp;spn=0.039873,0.054932&amp;z=14" style="color:#0000FF;text-align:left">View Larger Map</a></small>
							<h5 style="color:black; text-decoration:none;">Visit our <a href="http://serviceresourcesinc.com/">website.</a></h5>
						</div>
						<div class="tab-pane <?php echo $t3Active;?>" id="tab3">
							<p>Our cooperative aims to provide a two-fold option – better quality of life to our member-employees through sustainable, progressive & dignified source of livelihood and hassle free work arrangements, focus on service values and cost effectiveness to our clients.</p>
							<p>It may be worthwhile to know that we, the organizers behind CSI COOPERATIVE are regular employees of our sister company, Service Resources, Inc., who boasts of 30 years of excellent manpower service to its client companies. </p>
							<br>
							<p><strong>Address:</strong>
								FIRST CAPITOL PLACE G,F FIRST ST., COR. PHIL AM STS.
								PASIG METRO MANILA</p>
						</div>
					  </div>
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