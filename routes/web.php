<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApplicantAuthController;
use App\Http\Controllers\Scholar\CommunityController;
use App\Http\Controllers\Scholar\HomeController;
use App\Http\Controllers\Scholar\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Scholar\ScholarController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PDFController;

Route::view('/', 'mainhome')->name('mainhome');
Route::view('roleselection', 'roleselection')->name('roleselection');

//routing for applicant page
Route::prefix('applicant')->group(function () {
    Route::view('/appinstructions', 'applicant.appinstructions')->name('appinstructions');
    Route::get('/applicationformC', [ApplicationController::class, 'showcollegeapplication'])->name('form-college');
    Route::post('/saveapplicant', [ApplicationController::class, 'saveapplicant'])->name('saveapplicant');
    Route::view('/applicationformHE', 'applicant.applicationformHE')->name('form-hselem');
    Route::get('/application-success/{casecode}/{password}', [ApplicationController::class, 'showconfirmation'])->name('showconfirmation');
    Route::get('/login', [ApplicantAuthController::class, 'showlogin'])->name('login-applicant');
    Route::post('/login', [ApplicantAuthController::class, 'login'])->name('log-applicant');
    Route::get('/logout', [ApplicantAuthController::class, 'logout'])->name('logout-applicant');
    Route::get('/applicant-portal/{casecode}', [ApplicantAuthController::class, 'showportal'])->middleware('applicant')->name('applicantportal');
});

// routing for scholars page just for viewing the page no logic used here
Route::prefix('scholar')->middleware('scholar')->group(function () {
    Route::view('/lteform', 'scholar.lteform')->name('lteform');
    Route::view('/sublteinfo', 'scholar.sublteinfo')->name('subtleinfo');
    Route::view('/screnewal', 'scholar.screnewal')->name('screnewal');
    Route::view('/subrenewal', 'scholar.subrenewal')->name('subrenewal');
    Route::view('/schome', 'scholar.schome')->name('schome');
    // Appointment system
    Route::view('/appointmentsystem', 'scholar.appointmentsystem')->name('appointment');
    Route::view('/appointmentinfo', 'scholar.appointmentinfo')->name('appointmentinfo');
    // Humanities Class
    Route::get('/schumanities', [ScholarController::class, 'showHumanitiesClass'])->name('schumanities');
});

// cs folder
Route::prefix('scholar/communityservice')->middleware('scholar')->group(function () {
    // cs
    Route::get('/csactivities', [CommunityController::class, 'showCSActivities'])->name('csactivities');
    Route::get('/csdetails/{csid}', [CommunityController::class, 'showCSDetails'])->name('csdetails');
    Route::post('/csdetails/{csid}', [CommunityController::class, 'storeCSRegistration'])->name('csdetails.post');
    Route::get('/csdashboard', [CommunityController::class, 'showCSDashboard'])->name('csdashboard');
    Route::post('/csdashboard/{csid}/cancel', [CommunityController::class, 'cancelRegistration'])->name('csdashboard.cancel');
    Route::get('/csattendance', [CommunityController::class, 'showCSAttendance'])->name('csattendance');
    Route::get('/csform', [CommunityController::class, 'showCSForm'])->name('csform');
    Route::post('/csform', [CommunityController::class, 'storeCSForm'])->name('csform.post');
});

// scholarship folder
Route::prefix('scholar/scholarship')->middleware('scholar')->group(function () {
    Route::get('/overview', [ScholarController::class, 'showScholarshipOverview'])->name('overview');
    Route::get('/gradesub', [ScholarController::class, 'showGradeSubmission'])->name('gradesub');
    Route::post('/gradesub', [ScholarController::class, 'storeGradeSubmission'])->name('gradesub.post');
    Route::get('/gradesinfo/{id}', [ScholarController::class, 'showGradeInfo'])->name('gradesinfo');
    // LTE
    Route::get('/sclte', [ScholarController::class, 'showLTE'])->name('sclte');
    Route::get('/lteinfo/{lid}', [ScholarController::class, 'showLTEinfo'])->name('lteinfo');
    Route::get('/lteinfo-absent/{lid}', [ScholarController::class, 'showLTEinfoabsent'])->name('lteinfo-absent');
    Route::get('/lteinfo-late/{lid}', [ScholarController::class, 'showLTEinfolate'])->name('lteinfo-late');
    Route::get('/lteinfo-leftearly/{lid}', [ScholarController::class, 'showLTEinfoleftearly'])->name('lteinfo-leftearly');
    // user profile
    Route::get('/manageprofile', [ScholarController::class, 'showProfile'])->name('manageprofile');
    Route::post('/manageprofile', [ScholarController::class, 'updateProfile'])->name('manageprofile.post');
    Route::get('/changepassword', [ScholarController::class, 'changePassword'])->name('changepassword');
    // sms | email notif preference
    Route::post('/update-notification-preference', [ScholarController::class, 'updateNotificationPreference'])->name('update.notification.preference');
});

Route::prefix('scholar/allowancerequest')->middleware('scholar')->group(function () {
    Route::view('/scregular', 'scholar.allowancerequest.scregular')->name('scregular');
    Route::view('/regularforminfo', 'scholar.allowancerequest.regularforminfo')->name('regularforminfo');
    Route::view('/transpoinfo', 'scholar.allowancerequest.transpoinfo')->name('transpoinfo');
    Route::view('/bookinfo', 'scholar.allowancerequest.bookinfo')->name('bookinfo');
    Route::view('/thesisinfo', 'scholar.allowancerequest.thesisinfo')->name('thesisinfo');
    Route::view('/projectinfo', 'scholar.allowancerequest.projectinfo')->name('projectinfo');
    Route::view('/uniforminfo', 'scholar.allowancerequest.uniforminfo')->name('uniforminfo');
    Route::view('/gradinfo', 'scholar.allowancerequest.gradinfo')->name('gradinfo');
    Route::view('/fieldtripinfo', 'scholar.allowancerequest.fieldtripinfo')->name('fieldtripinfo');
    // Allowance Requests : Special
    Route::get('/scspecial', [ScholarController::class, 'showspecialallowance'])->name('scspecial');
    Route::get('/special/instruction/{requesttype}', [ScholarController::class, 'showrequestinstruction'])->name('specialreqs');
    Route::get('/special/form/{formtype}', [ScholarController::class, 'showrequestform'])->name('showrequestform');
    Route::post('/book/{casecode}', [ScholarController::class, 'reqallowancebook'])->name('reqallowancebook');
    Route::post('/event/{casecode}', [ScholarController::class, 'reqallowanceevent'])->name('reqallowanceevent');
    Route::post('/graduation/{casecode}', [ScholarController::class, 'reqallowancegraduation'])->name('reqallowancegraduation');
    Route::post('/project/{casecode}', [ScholarController::class, 'reqallowanceproject'])->name('reqallowanceproject');
    Route::post('/thesis/{casecode}', [ScholarController::class, 'reqallowancethesis'])->name('reqallowancethesis');
    Route::post('/transportation_reimbursement/{casecode}', [ScholarController::class, 'reqallowancetranspo'])->name('reqallowancetranspo');
    Route::post('/uniform/{casecode}', [ScholarController::class, 'reqallowanceuniform'])->name('reqallowanceuniform');
});

Route::view('chartjs', 'chartjs');

// route to registration for existing scholars
Route::view('/registration', 'registration')->name('registration');
Route::post('/registerScholar', [HomeController::class, 'registerScholar'])->name('registerScholar');

// Login-Logout | Scholar
Route::prefix('scholar')->controller(LoginController::class)->group(function () {
    Route::get('/scholar-login', 'viewLogin')->name('scholar-login');
    Route::post('/scholar-login', 'authLogin')->name('scholar-login.post'); // For handling the form submission
    Route::post('/logout', 'logout')->name('logout');
});

Route::prefix('staff')->middleware('staff')->group(function () {
    // ANNOUNCEMENTS
    Route::get('/home', [AnnouncementController::class, 'showHome'])->name('home-sw');
    Route::post('/home', [AnnouncementController::class, 'storeAnnouncement'])->name('home-sw.post');
    // SCHOLAR OVERVIEW
    Route::get('/scholars', [StaffController::class, 'showScholarsoverview'])->name('scholars-overview');
    Route::get('/scholars-college', [StaffController::class, 'showScholarsCollege'])->name('scholars-college');
    Route::get('/scholars-elementary', [StaffController::class, 'showScholarsElem'])->name('scholars-elementary');
    Route::get('/scholars-highschool', [StaffController::class, 'showScholarsHS'])->name('scholars-highschool');
    Route::get('/scholar/{id}', [StaffController::class, 'showScholarProfile'])->name('scholar-viewinfo');
    // COMMUNITY SERVICE
    Route::get('/community-service-overview', [StaffController::class, 'showCommunityService'])->name('communityservice');
    Route::get('/community-service-open', [StaffController::class, 'showCSOpenEvents'])->name('communityservice-open');
    Route::get('/community-service-closed', [StaffController::class, 'showCSClosedEvents'])->name('communityservice-closed');
    Route::post('/createcsevent', [StaffController::class, 'createcsevent'])->name('createcsevent');
    Route::post('/updatecsevent/{csid}', [StaffController::class, 'updatecsevent'])->name('updatecsevent');
    Route::get('/community-service-info/{csid}', [StaffController::class, 'showcseventinfo'])->name('viewcseventinfo');
    // HUMANITIES CLASS
    Route::get('/humanities-class', [StaffController::class, 'showHumanitiesClass'])->name('humanitiesclass');
    Route::post('/createhc', [StaffController::class, 'createhc'])->name('createhc');
    Route::get('/humanities-class-attendance-system/{hcid}', [StaffController::class, 'showAttendanceSystem'])->name('attendancesystem');
    Route::post('/saveattendance/{hcid}', [StaffController::class, 'saveattendance'])->name('savehcattendance');
    Route::post('/viewhcattendees/{hcid}', [StaffController::class, 'viewhcattendees'])->name('viewhcattendees');
    Route::get('/humanities-class/{hcid}-attendees', [StaffController::class, 'viewattendeeslist'])->name('viewattendeeslist');
    Route::get('/humanities-class/{hcaid}', [StaffController::class, 'checkouthc'])->name('checkouthc');
    Route::get('/humanities-class/save/{hcid}', [StaffController::class, 'savehc'])->name('savehc');
    Route::post('/exitattendancesystem/{hcid}', [StaffController::class, 'exitattendancesystem'])->name('exitattendancesystem');
    // PENALTY | LTE
    Route::get('/penalty', [StaffController::class, 'showPenalty'])->name('penalty');
    Route::get('/letter-of-explanation', [StaffController::class, 'showLTE'])->name('lte');
    // ALLOWANCE REQUESTS
    Route::get('/allowance-requests-regular', [StaffController::class, 'showAllowanceRegular'])->name('allowancerequests-regular');
    Route::get('/allowance-requests-special', [StaffController::class, 'showAllowanceSpecial'])->name('allowancerequests-special');
    Route::post('/upload-transpo', [StaffController::class, 'updatetransporeimbursenment'])->name('transporeimbursement');
    Route::post('/upload-acknowledgement', [StaffController::class, 'updateacknowledgementreceipt'])->name('acknowledgement');
    Route::post('/upload-liquidation', [StaffController::class, 'updateliquidationform'])->name('liquidation');
    Route::post('/upload-certification', [StaffController::class, 'updatecertificationform'])->name('certform');
    // APPLICATION CRITERIA
    Route::get('/application-forms', [StaffController::class, 'showApplicationForms'])->name('applicationforms');
    Route::get('/application-qualification', [StaffController::class, 'showQualification'])->name('qualification');
    Route::post('/updatecriteria', [StaffController::class, 'updatecriteria'])->name('updatecriteria');
    Route::post('/addinstitution', [StaffController::class, 'addinstitution'])->name('addinstitution');
    Route::post('/updateinstitution/{inid}', [StaffController::class, 'updateinstitution'])->name('updateinstitution');
    Route::post('/deleteinstitution/{inid}', [StaffController::class, 'deleteinstitution'])->name('deleteinstitution');
    Route::post('/addcourse/{level}', [StaffController::class, 'addcourse'])->name('addcourse');
    Route::post('/updatecourse/{coid}', [StaffController::class, 'updatecourse'])->name('updatecourse');
    Route::post('/deletecourse/{coid}', [StaffController::class, 'deletecourse'])->name('deletecourse');
    // RENEWAL
    Route::get('/renewal', [StaffController::class, 'showRenewal'])->name('scholarshiprenewal');
    Route::get('/renewal-college', [StaffController::class, 'showRenewalCollege'])->name('renewal-college');
    Route::get('/renewal-elementary', [StaffController::class, 'showRenewalElem'])->name('renewal-elementary');
    Route::get('/renewal-highschool', [StaffController::class, 'showRenewalHS'])->name('renewal-highschool');
    // SYSTEM ADMIN
    Route::get('/dashboard-admin', [StaffController::class, 'showDashboard'])->name('dashboard');
    Route::get('/users-scholar', [StaffController::class, 'showUsersScholar'])->name('users-scholar');
    Route::get('/users-staff', [StaffController::class, 'showUserStaff'])->name('users-staff');
    Route::get('/users-applicants', [StaffController::class, 'showUserApplicants'])->name('users-applicant');
    // USER: STAFF
    Route::get('/account-socialworker', [StaffController::class, 'showAccountSW'])->name('account-sw');
    Route::get('/staff-account-info/{id}', [StaffController::class, 'showStaffInfo'])->name('staff.view');
    Route::post('/staff/activate/{id}', [StaffController::class, 'activateStaff'])->name('staff.activate');
    Route::post('/staff/deactivate/{id}', [StaffController::class, 'deactivateStaff'])->name('staff.deactivate');
    // USER: SCHOLAR
    Route::get('/account-admin', [StaffController::class, 'showAccountSA'])->name('account-sa');
    Route::get('/scholar-account-info/{id}', [StaffController::class, 'showScholarInfo'])->name('scholar.view');
    Route::post('/scholar/activate/{id}', [StaffController::class, 'activateScholar'])->name('scholar.activate');
    Route::post('/scholar/deactivate/{id}', [StaffController::class, 'deactivateScholar'])->name('scholar.deactivate');
    // USER: APPLICANTS
    Route::get('/applicants', [StaffController::class, 'showApplicants'])->name('applicants');
    Route::get('/applicant-info/{casecode}', [StaffController::class, 'showapplicantinfo'])->name('applicantinfo');
    Route::get('/applicant-account-info/{apid}', [StaffController::class, 'showapplicantaccount'])->name('applicant.view');
    Route::post('/applicant/activate/{apid}', [StaffController::class, 'activateapplicant'])->name('applicant.activate');
    Route::post('/applicant/deactivate/{apid}', [StaffController::class, 'deactivateapplicant'])->name('applicant.deactivate');
});

// staff login, logout, account creation
Route::prefix('staff')->controller(StaffAuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login-sw');
    Route::post('/login', 'login')->name('log-worker');
    Route::get('/logout', 'logout')->name('logout-sw');
    Route::post('/create-staff', 'createAccount')->name('staccount.create');
});

// report generation
Route::prefix('staff')->controller(PDFController::class)->middleware('staff')->group(function () {
    Route::get('/scholarship-report', 'generatescholarshipreport')->name('generatescholarshipreport');
});
