<?php 
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$user =  ( isset($_SESSION['User']) ? $_SESSION['User'] : array());

$Provider = $user['MPN'];
$Location = $user['LocationName'];
$DRAdd = $user['LocationAddress'].", ".$user['LocationSuburb'].", ".$user['LocationState'];   
$DRPCode = $user['LocationPostcode'];
$DRPhone = $user['LocationPhone'];
$DRFax = $user['LocationFax'];


list($trd2, $trd1, $trd) = formatDateForPrint( $a_medical_cert->treat_rev_date);
list($d3, $d2, $d1) = formatDateForPrint( $a_medical_cert->unfitfrom);
list($c3, $c2, $c1) = formatDateForPrint( $a_medical_cert->unfitto);
list($e3, $e2, $e1) = formatDateForPrint( $a_medical_cert->suitfrom);
list($f3, $f2, $f1) = formatDateForPrint( $a_medical_cert->suitto);
list($g3, $g2, $g1) = formatDateForPrint( $a_medical_cert->modfrom);
list($h3, $h2, $h1) = formatDateForPrint( $a_medical_cert->exam_date);
list($k3, $k2, $k1) = formatDateForPrint( $a_medical_cert->fitness_review_date);

$diagnosis=  $a_medical_cert->diagnosis;

$words=split(" ",$a_patient->injury_desc);
	$count1=0;
	$line1="";
	$line2="";
	foreach($words as $aword) {
			$count1 += strlen($aword)+1;
			if($count1<85) $line1 .= " ".$aword; else  $line2 .= " ".$aword; 
	}
	
	$howtheinjuryoccured_line1=$line1;
	$howtheinjuryoccured_line2=$line2;
	
$p1 = $a_patient->doctor_name;
$Location = $a_patient->doctor_location;
$ini = "";

if ( $a_patient->cons_status == "Initial" ) 
	{
		$ini = "x";
	} else {
		$ch_Initial = "";
	}
	
	$prog = "";
	if ( $a_patient->cons_status == "Progress" ) 
	{
		$prog = "x";
	} else {
		$ch_Progress = "";
	}
	
	$fin = "";
	if ( $a_patient->cons_status == "Final" ) 
	{
		$fin= "x";
	} else {
		$ch_Final = "";
	}
	

	$j1=$a_patient->doctor_name;

	$j3=$DRAdd;
	$j4=$DRPCode;
	$j5="";
	$j6=$DRPhone;
	$j7=$DRFax;
	$j2=$Provider;
	
	
	
	if ( $a_patient->doctor_agrees  == "Yes" ) 
	{
		$l1 = "x";
	} else {
		$l1 = "";
	}
	
	if ( $a_patient->doctor_agrees == "No" ) 
	{
		$l2 = "x";
	} else {
		$l2 = "";
	}	
	
	$l3 = "";
	
$WFit =  $a_medical_cert->work_is_factor;
$AssReq =  $a_medical_cert->assreq;
$Surname =  $a_patient->surname;
$OTH_TXT =  $a_medical_cert->other_restrictions_other;
$check_OTH =  $a_medical_cert->other_restrictions_details;
	


if ($check_OTH == "")
{
	$check_Avoid = "";
	} else {
	$check_Avoid = "Avoid/minimise";
}


if ( ($a_medical_cert->other_restrictions == "No") && ( $a_patient->cons_status != "Final" )  ) 
{
	$OTH_LIMS = "N/A";
} else {
	$OTH_LIMS = "$check_Avoid $check_OTH. $OTH_TXT";
}
	





$OTHwords=split(" ",$OTH_LIMS);
$OTHcount1=0;
$OTHline1="";
$OTHline2="";
$OTHline3="";
$OTHlineY = "";
foreach($OTHwords as $OTHaword) {
		$OTHcount1 += strlen($OTHaword)+1;
		if($OTHcount1<95) $OTHline1 .= " ".$OTHaword; else  $OTHlineY .= " ".$OTHaword; 
	
}
	

	if($OTHlineY == "")
{
$OTHline2 .= "";
$OTHline3 .= "";
	} else {
	
		$OTHwords2=split(" ",$OTHlineY);
		$OTHaword2 = "";
		$OTHcount2 = 0;
		foreach($OTHwords2 as $OTHaword2) 
		{
			$OTHcount2 += strlen($OTHaword2);
			if($OTHcount2<65) 
				$OTHline2 .= " ".$OTHaword2; 
			else $OTHline3 .= " ".$OTHaword2; 
		}
	}

$i8=$OTHline1;
$i8b=$OTHline2;
$i8c=$OTHline3;


$MPwords=split(" ",$a_medical_cert->manag_plan);
$MPcount1=0;
$MPline1="";
$MPline2="";
foreach($MPwords as $MPaword) {
		$MPcount1 += strlen($MPaword)+1;
		if($MPcount1<130) $MPline1 .= " ".$MPaword; else  $MPline2 .= " ".$MPaword; 
}

$managementplan=$MPline1;
$managementplan_line2=$MPline2;


$today = getdate();
$TDday = $today['mday'];
$TDmonth = $today['mon'];
$TDyear = $today['year'];

$CIRT_NAME = "WCMC $TDyear $TDmonth $TDday $Surname.pdf";

$i1 = $a_medical_cert->has_cap_for_duration;
$i2 = $a_medical_cert->has_cap_for_duration_days;
$i3 = $a_medical_cert->has_cap_for_liftingupto;
$i4 = $a_medical_cert->has_cap_for_walkingupto;
$i5 = $a_medical_cert->has_cap_for_sittingupto;
$i9 = $a_medical_cert->has_cap_for_standingupto;
$i7 = $a_medical_cert->has_cap_for_travellingupto;
$i6 = $a_medical_cert->has_cap_for_keyingupto;











if ( $a_medical_cert->work_is_factor == "Yes" ) 
{
	$y = "x";
} else {
	$y = "";
}

if ( $a_medical_cert->work_is_factor == "No" ) 
{
	$n = "x";
} else {
	$n = "";
}

if ( $a_medical_cert->work_is_factor == "Unknown" ) 
{
	$u= "x";
} else {
	$u = "";
}




if ( $a_medical_cert->fit_for_work_status == "fit" ) 

{
	$box1 = "x";
} else {
	$box1 = "";
}

if ( $a_medical_cert->fit_for_work_status == "unfit" ) 
{
	$box2 = "x";
} else {
	$box2 = "";
}

if ( $a_medical_cert->fit_for_work_status == "suitable" ) 
{
	$box3= "x";
} else {
	$box3 = "";
	}
	
if ( $a_medical_cert->fit_for_work_status == "modified" ) 
{
	$box4= "x";
} else {
	$box4 = "";
}




if ( $a_medical_cert->assreq == "Yes" ) 

{
	$h1b = "xxxx";
} else {
	$h1b = "";
}

if ( $a_medical_cert->assreq == "No" || $a_patient->cons_status == "Final" ) 
{
	$h1a = "xx";
} else {
	$h1a = "";
}



$signature = "";


?>