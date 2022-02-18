<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once( './config.php' );
require_once( __DIR__ . '/MakeWcPdf.php' );
require_once( __DIR__ . '/Patient.php' );
require_once( __DIR__ . '/MedicalCert.php' );

echo '<pre>';

$o_p = new Patient;
$o_p->id = 1;
$o_p->examdate = date('Y-m-d');
$o_p->cons_status = 'Initial';
$o_p->claimno = 'claimno';
$o_p->surname = 'surname';
$o_p->othernames = 'othernames';
$o_p->address = 'street address';
$o_p->suburb = 'suburb';
$o_p->state = 'state';
$o_p->postcode = 'postcode';
$o_p->phone = '0222222222';
$o_p->mobile = '0444333222';
$o_p->dob = '1971-07-20';
$o_p->occupation = 'occupation';
$o_p->hours_week = 'hours_week';
$o_p->emp_name = 'emp_name';
$o_p->emp_address = 'emp_address';
$o_p->emp_suburb = 'emp_suburb';
$o_p->emp_state = 'emp_state ';
$o_p->emp_postcode = 'emp_postcode';
$o_p->injury_desc = 'injury_desc';
$o_p->injury_dateof = "1010-11-22";
$o_p->doctor_name = 'doc_name';
$o_p->doctor_location = 'doc_location';
$o_p->doctor_agrees = 'No';
print_r( $o_p );

$o_m = new MedicalCert;
$o_m->id = 12;
$o_m->paitentid = 13;
$o_m->diagnosis = 'diagnosis';
//$o_m->work_is_factor = 'Unknown';
$o_m->work_is_factor = 'Yes';
$o_m->manag_plan = 'manag_plan';
//$o_m->treat_rev_date = "6666-05-25"; *REMOVE*
//$o_m->fit_for_work_status = "unfit";
$o_m->fit_for_work_status = "suitable";
$o_m->unfitfrom = "1111-01-30";
$o_m->unfitto = "2222-02-29";
$o_m->suitfrom = "3333-03-28";
$o_m->suitto = "4444-04-27";
$o_m->modfrom = "5555-04-26";
$o_m->assreq = "assreq";
$o_m->exam_date = "7777-06-24";
$o_m->has_cap_for_duration = "has_cap_for_duration";
$o_m->has_cap_for_duration_days = "has_cap_for_duration_days";
$o_m->has_cap_for_liftingupto = "has_cap_for_liftingupto ";
$o_m->has_cap_for_walkingupto = "has_cap_for_walkingupto";
$o_m->has_cap_for_sittingupto = "has_cap_for_sittingupto";
$o_m->has_cap_for_standingupto = "has_cap_for_standingupto";
$o_m->has_cap_for_travellingupto = "has_cap_for_travellingupto";
$o_m->has_cap_for_keyingupto = "has_cap_for_keyingupto";
$o_m->other_restrictions = "other_restrictions";
$o_m->other_restrictions_details = "other_restrictions_details";
$o_m->other_restrictions_other = "other_restrictions_other";
$o_m->fitness_review_date = "8888-07-23";;
print_r($o_m);

$user =  array();
$user['MPN']              = 'provider';
$user['LocationName']     = 'location';
$user['LocationAddress']  = 'LocationAddress';
$user['LocationSuburb']   = 'LocationSuburb';
$user['LocationState']    = 'LocationState';
$user['LocationPostcode'] = 'LocationPostcode';
$user['LocationPhone']    = 'LocationPhone';
$user['LocationFax']      = 'LocationFax';

$o = new MakeWcPdf;
echo $o->bootstrap('test123', $o_p, $o_m, $user, true, false);
//echo $o->makefdf( 'filename', $o_p, $o_m, $user, true );
$fdf_file = $o->makefdf( 'filename', $o_p, $o_m, $user, false );
var_dump( $o->makePDF( $fdf_file, 'test123' ) );

echo '</pre>';
