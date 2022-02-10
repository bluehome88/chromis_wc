<?php 
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

	
	$a_patient->ResetEmptyDates();

	list($l6, $l5, $l4) = formatDateForPrint( $a_patient->examdate);
	list($doi2, $doi1, $doi) = formatDateForPrint( $a_patient->injury_dateof);
	
	$familyname=$a_patient->surname;
	$claimno=$a_patient->claimno;
	$othernames=$a_patient->othernames;
	$address=$a_patient->address;
	$address.= ", ".$a_patient->suburb;
	$address.= ", ".$a_patient->state;
	$postcode=$a_patient->postcode;
	
	list($db3, $db2, $db) = formatDateForPrint( $a_patient->dob);
	$phoneno=$a_patient->phone;
	$phoneno.= " (w)/ ".$a_patient->mobile;
	$occupation=$a_patient->occupation;
	
	$employername=$a_patient->emp_name;
	$employerpostcode=$a_patient->emp_postcode;
	$employeraddress=$a_patient->emp_address;
	$employeraddress.= ", ".$a_patient->emp_suburb;
	$employeraddress.= ", ".$a_patient->emp_state;
	$hrsweek = $a_patient->hours_week;
	
	$today = getdate();
	$TDday = $today['mday'];
	$TDmonth = $today['mon'];
	$TDyear = $today['year'];
	
	
	$p1=$a_patient->doctor_name;
	if( $a_patient->examdate != '' ) list($EXyear, $EXmonth, $EXday) = split('[/.-]', $a_patient->examdate);
	$p2=$EXday;
	$p3=$EXmonth;
	$p4=$EXyear;
	
	
	
	$today = getdate();
	$TDday = $today['mday'];
	$TDmonth = $today['mon'];
	$TDyear = $today['year'];
	
	$CIRT_NAME = "WCMC $TDyear $TDmonth $TDday $familyname.pdf";
?>