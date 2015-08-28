<?php

function getMeta() {
	if(file_exists("components/meta.php")){
		return $content = file_get_contents('components/meta.php', true);
	}
	else
		return "Error: Unable to load the meta file!";
}

// function getHeader() {
// 	if(file_exists("components/header.php")){
// 		return $content = file_get_contents('components/header.php', true);
// 	}
// 	else
// 		return "Error: Unable to load the header file!";
// }

function getHeader($userType) {
	include 'components/header.php';
	if(file_exists("components/header.php")){
		echo generateHomePageHeader($userType);
	}
	else
		echo "Error: Unable to load the header file!";
}

function getFooter() {
	if(file_exists("components/footer.php")){
		return $content = file_get_contents('components/footer.php', true);
	}
	else
		return "Error: Unable to load the footer file!";
}

function getApplicantTestimonialSlider() {
	if(file_exists("components/testimonial-slider-applicant.php")){
		return $content = file_get_contents('components/testimonial-slider-applicant.php', true);
	}
	else
		return "Error: Unable to load the testimonial-slider-applicant file!";
}

function getEmployerTestimonialSlider() {
	if(file_exists("components/testimonial-slider-employer.php")){
		return $content = file_get_contents('components/testimonial-slider-employer.php', true);
	}
	else
		return "Error: Unable to load the testimonial-slider-employer file!";
}

function getSlider() {
	if(file_exists("components/slider.php")){
		return $content = file_get_contents('components/slider.php', true);
	}
	else
		return "Error: Unable to load the slider file!";
}

function getLogInBox() {
	if(file_exists("components/login-box.php")){
		return $content = file_get_contents('components/login-box.php', true);
	}
	else
		return "Error: Unable to load the login-box file!";
}

function getSignInBox() {
	if(file_exists("components/signin-box.php")){
		return $content = file_get_contents('components/signin-box.php', true);
	}
	else
		return "Error: Unable to load the signin-box file!";
}

function getProfileBox($status, $fullName, $age, $address, $picSrc) {
	include 'components/profile-box.php';
	$age = $age . " yrs. old";
	if($status === "Employee"){
		$status = "Applicant";
	}
	if(file_exists("components/profile-box.php")){
		echo generateProfile($status, $fullName, $age, $address, $picSrc);
	}
	else
		echo "Error: Unable to load the profile-box file!";
}

function getEmployerProfileBox($status, $coName, $address, $picSrc) {
	include 'components/profile-box.php';
	if(file_exists("components/profile-box.php")){
		echo generateProfile($status, $coName, "", $address, $picSrc);
	}
	else
		echo "Error: Unable to load the profile-box file!";
}

function getInterestedInBox() {
	if(file_exists("components/interested-in.php")){
		return $content = file_get_contents('components/interested-in.php', true);
	}
	else
		return "Error: Unable to load the interested-in file!";
}

function getSignUpModal() {
	if(file_exists("components/sign-up-modal.php")){
		return $content = file_get_contents('components/sign-up-modal.php', true);
	}
	else
		return "Error: Unable to load the sign-up-modal file!";
}

function getSignUpBox() {
	if(file_exists("components/signup-box.php")){
		return $content = file_get_contents('components/signup-box.php', true);
	}
	else
		return "Error: Unable to load the signup-box file!";
}

function getSignUpBoxEmployer() {
	if(file_exists("components/signup-box-employer.php")){
		return $content = file_get_contents('components/signup-box-employer.php', true);
	}
	else
		return "Error: Unable to load the signup-box-employer file!";
}

function getResetPasswordModal() {
	if(file_exists("components/reset-password-modal.php")){
		return $content = file_get_contents('components/reset-password-modal.php', true);
	}
	else
		return "Error: Unable to load the interested-in file!";
}

function getTermsModal() {
	if(file_exists("components/terms-modal.php")){
		return $content = file_get_contents('components/terms-modal.php', true);
	}
	else
		return "Error: Unable to load the terms-modal file!";
}

function getInvalidAcctTypeModal() {
	if(file_exists("components/invalid-account-type-modal.php")){
		return $content = file_get_contents('components/invalid-account-type-modal.php', true);
	}
	else
		return "Error: Unable to load the invalid-account-type-modal file!";
}

function getHomeLink() {
	if(file_exists("components/home-back.php")){
		return $content = file_get_contents('components/home-back.php', true);
	}
	else
		return "Error: Unable to load the home-back file!";
}

function getReligions() {
	$religions = array("Aglipay", "Buddhist", "Catholic", "Iglesia ni Cristo", "Muslim", "Protestant", "Others");
	return $religions;
}

function getContactPersonPositions() {
	$departments = array("Chief", "Head", "Manager", "Member", "Project Leader", "Supervisor", "Others");
	return $departments;
}

function getDepartments() {
	$departments = array("Accounting", "Creatives", "Finance", "Human Resources", "Information Technology", "Logistics", "Marketing", "Others");
	return $departments;
}

function getEducationalAttainments() {
	$educAttainments = array("High School Undergraduate", "High School Graduate", "High School Graduate with Honors",  "College Graduate", "College Graduate with Honors", "College Level", "Vocational School Graduate", "Vocational School Graduate with Honors",  "Vocational School Undergraduate");
	return $educAttainments;
}

function getEmployerDashboardModals(){
	if(file_exists("components/employer-dashboard-modals.php")){
		return $content = file_get_contents('components/employer-dashboard-modals.php', true);
	}
	else
		return "Error: Unable to load the employer-dashboard-modals file!";
}

function getFormsErrorModal(){
	if(file_exists("components/forms-error-modal.php")){
		return $content = file_get_contents('components/forms-error-modal.php', true);
	}
	else
		return "Error: Unable to load the forms-error-modal file!";
}

function getGTM(){
	if(file_exists("components/gtm.php")){
		return $content = file_get_contents('components/gtm.php', true);
	}
	else
		return "Error: Unable to load the gtm file!";
}

function getSearchBar($username){
	include 'components/admin-navbar.php';
	if(file_exists("components/admin-navbar.php")){
		echo generateAdminNavBar($username);
	}
	else
		echo "Error: Unable to load the admin-navbar file!";
}

function getApplicantNavBar($username){
	include 'components/applicant-navbar.php';
	if(file_exists("components/applicant-navbar.php")){
		echo generateApplicantNavBar($username);
	}
	else
		echo "Error: Unable to load the applicant-navbar file!";
}

function getEmployerNavBar($username){
	include 'components/employer-navbar.php';
	if(file_exists("components/employer-navbar.php")){
		echo generateEmployerNavBar($username);
	}
	else
		echo "Error: Unable to load the employer-navbar file!";
}

function getGeneralNavBar(){
	if(file_exists("components/general-navbar.php")){
		return $content = file_get_contents('components/general-navbar.php', true);
	}
	else
		return "Error: Unable to load the general-navbar file!";
}

function getApplicantStartNavBar(){
	include 'components/applicant-start-navbar.php';
	if(file_exists("components/applicant-start-navbar.php")){
		echo generateNavBar();
	}
	else
		echo "Error: Unable to load the applicant-start-navbar file!";
}

function getEmployerStartNavBar(){
	include 'components/employer-start-navbar.php';
	if(file_exists("components/employer-start-navbar.php")){
		echo generateNavBar();
	}
	else
		echo "Error: Unable to load the employer-start-navbar file!";
}

function getApplicantFormNavBar(){
	if(file_exists("components/applicant-form-navbar.php")){
		return $content = file_get_contents('components/applicant-form-navbar.php', true);
	}
	else
		return "Error: Unable to load the applicant-form-navbar file!";
}

function in_array_case_insensitive($needle, $haystack) {
	return in_array( strtolower($needle), array_map('strtolower', $haystack) );
}

function getSriAds(){
	if(file_exists("components/sri-ads.php")){
		return $content = file_get_contents('components/sri-ads.php', true);
	}
	else
		echo "Error: Unable to load the sri-ads file!";
}

function getFilters($userType){
	include 'components/filters.php';
	if(file_exists("components/filters.php")){
		echo generateFilters($userType);
	}
	else
		echo "Error: Unable to load the filters file!";
}

function outputCSV($data) {
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

function getJobFairBanner(){
	include 'components/jobfair-banner.php';
	if(file_exists("components/jobfair-banner.php")){
		echo generateBanner();
	}
	else
		echo "Error: Unable to load the jobfair-banner file!";
}

?>