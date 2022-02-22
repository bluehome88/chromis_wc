<?php 
if( 0 ){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
require_once( __DIR__ . '/PHPRtfLite.php');
PHPRtfLite::registerAutoloader();

global $value_font, $label_font, $sect;
function renderDate( $arrDate ){
	global $value_font, $label_font, $sect;

	if( isset($arrDate) && count($arrDate) ){
		$textField = $sect->addTextField($value_font);
		$textField->setDefaultValue($arrDate[2]);
		$sect->writeText('/', $label_font);
		$textField = $sect->addTextField($value_font);
		$textField->setDefaultValue($arrDate[1]);
		$sect->writeText('/', $label_font);
		$textField = $sect->addTextField($value_font);
		$textField->setDefaultValue($arrDate[0]);
	}
}

function breakLine( $times = 1 ){
	global $value_font, $label_font, $sect;
	for( $i = 0; $i < $times; $i++)
		$sect->writeText( '<br>', $label_font );
}

function renderTextField( $txt_value, $break = true ){
	global $value_font, $label_font, $sect;
	if( $break )
		breakLine();

	$textField = $sect->addTextField( $value_font );
	if( !$txt_value )
		$txt_value = "___";

	$textField->setDefaultValue( $txt_value );

	if( $break)
		breakLine();
}

function generateRTF( $s_file_name, $a_patient, $a_medical_cert, $a_user ){
	
	global $value_font, $label_font, $sect;
	/* Preprocess data */
	
	$a_dob = array();
    if( $a_patient->dob ){
        $a_dob = explode('-', $a_patient->dob);
    }

    $a_inj_date = array();
    if( $a_patient->injury_dateof ){
        $a_inj_date = explode('-', $a_patient->injury_dateof);
    }

    $a_int_date = array();
    if( $a_medical_cert->exam_date && $a_patient->cons_status == 'Initial' ){
        $a_int_date = explode('-', $a_medical_cert->exam_date);
    }

    $a_int_date2 = array();
    if( $a_medical_cert->exam_date ){
        $a_int_date2 = explode('-', $a_medical_cert->exam_date);
    }

    $a_first_seen_date = array();
    if( $a_patient->date_first_seen && $a_patient->cons_status == 'Initial' ){
        $a_first_seen_date = explode('-', $a_patient->date_first_seen);
    }

    $a_patient_address = array();
    $a_patient_address = arrayLoader(
        array(
            $a_patient->address,
            $a_patient->suburb,
            $a_patient->state .' '. $a_patient->postcode
        )
    );

    $a_emp_details = array();
    $a_emp_details  = arrayLoader(
        array(
            $a_patient->emp_name,
            $a_patient->emp_address,
            $a_patient->emp_suburb,
            $a_patient->emp_state .' '. $a_patient->emp_postcode
        )
    );

    $a_doc_loc = array();
    $a_doc_loc  = arrayLoader(
        array(
            $a_user['LocationName'],
            $a_user['LocationAddress'],
            $a_user['LocationSuburb'],
            $a_user['LocationState'] .' '. $a_user['LocationPostcode']
        )
    );

    $a_review_date = array();
    if( $a_medical_cert->fitness_review_date ){
        $a_review_date = explode('-', $a_medical_cert->fitness_review_date);
    }

    $s_work_is_factor = '';
    $s_occupation = '';
    $s_emp_details = '';
    $s_how_related = '';
    $s_per_factors = '';

    switch( $a_medical_cert->work_is_factor ){
        case 'Yes':
            $s_work_is_factor = 'A';
            break;
        case 'No':
            $s_work_is_factor = 'B';
            break;
        default:
            $s_work_is_factor = 'C';
    }

    $s_occupation = $a_patient->occupation;
    $s_emp_details = implode(', ', $a_emp_details);
    $s_how_related = $a_patient->injury_desc;
    $s_per_factors = $a_medical_cert->pre_existing_factors;

    $a_ffwork = array();
    $a_ffwork['fit']      = '';
    $a_ffwork['unfit']    = '';
    $a_ffwork['suitable'] = '';

    $a_ffwork[ $a_medical_cert->fit_for_work_status ] = 'A';

    $a_unfit_from = array();
    $a_unfit_to = array();
    $a_suit_from = array();
    $a_suit_to = array();
    $a_mod_from = array();
    $a_duration = array();
    $s_est_return_to_work = '';

    switch( $a_medical_cert->fit_for_work_status ){
        case 'unfit':
            $a_unfit_from = explode('-', $a_medical_cert->unfitfrom);
            $a_unfit_to   = explode('-', $a_medical_cert->unfitto);
            $s_est_return_to_work = $a_medical_cert->est_return_to_work;
            break;
        case 'suitable':
            $a_suit_from = explode('-', $a_medical_cert->suitfrom);
            $a_suit_to   = explode('-', $a_medical_cert->suitto);
            $a_duration['h'] = $a_medical_cert->has_cap_for_duration;
            $a_duration['d'] = $a_medical_cert->has_cap_for_duration_days;
            break;
    }

    $s_rest = '';
    $s_ass_req = '';
    $s_has_cap_for_liftingupto = '';
    $s_has_cap_for_sittingupto = '';
    $s_has_cap_for_standingupto = '';
    $s_has_cap_for_walkingupto = '';
    $s_has_cap_for_keyingupto = '';
    $s_has_cap_for_travellingupto = '';
    $s_comment = '';

    $s_ass_req = 'B';
    if($a_medical_cert->assreq == 'Yes'){
        $s_ass_req = 'A';
    }

    $s_rest = 'n/a';
    if( $a_medical_cert->other_restrictions == 'OTHER' ){
        $s_rest = $a_medical_cert->other_restrictions_other;
    }

    $s_fact_delay = $a_medical_cert->fact_delay;
    $s_has_cap_for_liftingupto    = $a_medical_cert->has_cap_for_liftingupto;
    $s_has_cap_for_sittingupto    = $a_medical_cert->has_cap_for_sittingupto;
    $s_has_cap_for_standingupto   = $a_medical_cert->has_cap_for_standingupto;
    $s_has_cap_for_walkingupto    = $a_medical_cert->has_cap_for_walkingupto;
    $s_has_cap_for_keyingupto     = $a_medical_cert->has_cap_for_keyingupto;
    $s_has_cap_for_travellingupto = $a_medical_cert->has_cap_for_travellingupto;
    $s_comment                    = $a_medical_cert->comment;



    /* Render RTF file */
	$font_name = 'Helvetica';
	$output_file = RTF_WRITE_FOLDER . $s_file_name. '.rtf'; 

	$section_font 	= new PHPRtfLite_Font(14, $font_name);
	$subtitle_font 	= new PHPRtfLite_Font(12, $font_name);
	$footer_font 	= new PHPRtfLite_Font(7, $font_name);
	$label_font 	= new PHPRtfLite_Font(10, $font_name);
	$value_font 	= new PHPRtfLite_Font(11, $font_name);
	$value_font->setBold();

	$rtf = new PHPRtfLite();
	PHPRtfLite_Unit::setGlobalUnit(PHPRtfLite_Unit::UNIT_INCH);
	$rtf->setPaperWidth(8.27);
	$rtf->setPaperHeight(11.69);
	$rtf->setMargins(0.36, 0.15, 0.36, 0.39);

	// Heading Section
	$sect = $rtf->addSection();
	breakLine(2);
	$sect->writeText('Certificate of capacity/certificate of fitness', new PHPRtfLite_Font(25, $font_name));

	breakLine(2);
	$sect->writeText('For use with workers compensation and Compulsory Third Party (CTP) motor accident injury claims.<br>', new PHPRtfLite_Font(13, $font_name));

	breakLine();
	$sect->writeText('For CTP claims: ‘Certificate of fitness’ means ‘certificate of fitness for work’. This certificate should be completed whether the person was employed at the time of the accident or not.', new PHPRtfLite_Font(8, $font_name));

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	if( $a_patient->cons_status == 'Initial' )
		$checkbox->setChecked();
	$sect->writeText('Tick if this is the initial certificate for this claim.<br><br>', $label_font);

	// Section 1
	$sect->writeText('Section 1: To be completed by the injured person or treating medical practitioner<br>', $section_font);
	$sect->writeText('<br>First Name    ', $label_font);
	renderTextField( $a_patient->othernames, false );

	$sect->writeText('<tab>Last Name    ', $label_font);
	renderTextField( $a_patient->surname, false );
	breakLine(2);

	$sect->writeText('Date of Birth(DD/MM/YYYY)    ', $label_font);
	renderDate( $a_dob );

	$sect->writeText('    Telephone Number    ', $label_font);
	renderTextField( $a_patient->phone, false );

	breakLine(2);
	$sect->writeText('Address (must be residential address – not PO Box)  ', $label_font);
	renderTextField( $a_patient->address, false );

	$sect->writeText('    Suburb    ', $label_font);
	renderTextField( $a_patient->suburb, false );

	breakLine(2);
	$sect->writeText('State    ', $label_font);
	renderTextField( $a_patient->state, false );

	$sect->writeText('<tab>PostCode    ', $label_font);
	renderTextField( $a_patient->postcode, false );

	breakLine(2);
	$sect->writeText('Claim Number    ', $label_font);
	renderTextField( $a_patient->claimno, false );

	$sect->writeText('<tab>Medicare Number    ', $label_font);
	renderTextField( $a_patient->medicare_no, false );

	breakLine(2);
	$sect->writeText('Occupation/job title    ', $label_font);
	renderTextField( $s_occupation, false );

	breakLine(2);
	$sect->writeText('Employer’s name and contact details (if applicable)    ', $label_font);
	renderTextField( $s_emp_details, false );

	breakLine(2);
	$sect->writeText('Injured person’s consent', $subtitle_font);

	breakLine(2);
	$sect->writeText('I consent to my treating medical practitioner, my employer (optional for CTP claims), the insurer, other medical practitioners or health related practitioners (whether consulting, treating or examining), workplace rehabilitation providers and SIRA exchanging information for the purpose of managing my injury and workers compensation/motor accident injury claim.<br><br>', $label_font);
	$sect->writeText('I understand this information will be used by SIRA and insurers to fulfill their functions under the motor accident insurance and workers compensation legislation.<br><br>', $label_font);
	$sect->writeText('Signature', $label_font);
	$sect->writeText('<tab><tab>Date(DD/MM/YYYY)<br><tab><tab>', $label_font);
	renderDate( $a_int_date );
	breakLine(3);


	// Section 2
	breakLine();
	$sect->writeText('Section 2: To be completed by treating medical practitioner', $section_font);
	breakLine(2);
	$sect->writeText('Medical certification', $subtitle_font);

	breakLine(2);
	$sect->writeText('Diagnosis of work related injury/disease or motor accident related injury(ies)', $label_font);
	renderTextField( $a_medical_cert->diagnosis );
	
	$sect->writeText('<br>Person’s stated date of injury/accident (DD/MM/YYYY)', $label_font);
	$textField = $sect->addTextField($value_font);
	renderDate( $a_inj_date);

	breakLine(2);
	$sect->writeText('Shaded areas to be completed for initial certificate only', $label_font);

	breakLine();
	$sect->writeText('Patient was first seen at this practice/hospital for this injury/disease on', $label_font);
	$textField = $sect->addTextField($value_font);
	renderDate( $a_first_seen_date );

	breakLine(2);
	$sect->writeText('Injury/disease is consistent with patient’s description of cause', $label_font);
	$checkbox = $sect->addCheckbox();
	if( $s_work_is_factor == 'A')
		$checkbox->setChecked();
	$sect->writeText('Yes', $label_font);
	$checkbox = $sect->addCheckbox();
	$sect->writeText('No', $label_font);
	if( $s_work_is_factor == 'B')
		$checkbox->setChecked();
	$checkbox = $sect->addCheckbox();
	if( $s_work_is_factor == 'C')
		$checkbox->setChecked();
	$sect->writeText('Unknown', $label_font);
	
	breakLine(2);
	$sect->writeText('How is the injury/disease related to work?', $label_font);
	renderTextField( $s_how_related );

	breakLine();
	$sect->writeText('Detail any pre-existing factors which may be relevant to this condition', $label_font);
	renderTextField( $s_per_factors );
	$sect->insertPageBreak();

	breakLine(2);
	$sect->writeText('Management plan for this period', $subtitle_font);
	breakLine(2);
	$sect->writeText('Treatment/medication type and duration', $label_font);
	renderTextField( $a_medical_cert->manag_plan );

	breakLine();
	$sect->writeText('Referral to another health service or rehabilitation provider (include details of provider type and service requested, duration and frequency when relevant)', $label_font);
	renderTextField( $a_medical_cert->referral );

	breakLine();
	$sect->writeText('Capacity for activities – ', $subtitle_font);
	$sect->writeText('If the person has capacity for pre-injury work this section does not need to be completed. For all others please consider activities of daily living currently being performed.', $label_font);

	breakLine(2);
	$sect->writeText('Lifting/carrying capacity<tab>', $label_font);
	renderTextField( $s_has_cap_for_liftingupto, false );

	breakLine();
	$sect->writeText('Sitting tolerance<tab>', $label_font);
	renderTextField( $s_has_cap_for_sittingupto, false );

	breakLine();
	$sect->writeText('Standing tolerance<tab>', $label_font);
	renderTextField( $s_has_cap_for_standingupto, false );
	
	breakLine();
	$sect->writeText('Pushing/pulling ability<tab>', $label_font);
	renderTextField( $s_has_cap_for_walkingupto, false );
	
	breakLine();
	$sect->writeText('Bending/twisting/squatting ability<tab>', $label_font);
	renderTextField( $s_has_cap_for_keyingupto, false );

	breakLine();
	$sect->writeText('Driving ability<tab>', $label_font);
	renderTextField( $s_has_cap_for_travellingupto, false );
	
	breakLine(2);
	$sect->writeText('Other (please specify) eg psychological considerations, keep wound clean and dry', $label_font);
	renderTextField( $s_rest );
	
	breakLine();
	$sect->writeText('Comments', $label_font);
	renderTextField( $s_comment );

	breakLine(2);
	$sect->writeText('Capacity for Work ', $subtitle_font);
	$sect->writeText('(please consider the health benefits of good work when completing this section).', $label_font);

	breakLine(2);
	$sect->writeText('Where the word ‘capacity’ appears below it should be read as ‘fitness for work’ when the certificate is completed in a motor accident injury claim.', $label_font);

	breakLine(2);
	$sect->writeText('Do you require a copy of the position description/work duties?', $label_font);
	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->post_desc == 'Yes')
		$checkbox->setChecked();
	$sect->writeText('Yes', $label_font);

	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->post_desc != 'Yes')
		$checkbox->setChecked();
	$sect->writeText('No', $label_font);

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->fit_for_work_status == 'fit')
		$checkbox->setChecked();
	$sect->writeText('is fit for pre-injury work from', $label_font);

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->fit_for_work_status == 'suitable')
		$checkbox->setChecked();
	$sect->writeText('has capacity for some type of work from ___ to ___ for ___ hours/day ___ days/week', $label_font);

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->fit_for_work_status == 'unfit')
		$checkbox->setChecked();
	$sect->writeText('has no current capacity for any work from ___ to ___', $label_font);

	breakLine(2);
	$sect->writeText('If no current work capacity, estimated time to return to any type of employment', $label_font);
	renderTextField( $s_est_return_to_work );
		
	breakLine();
	$sect->writeText('Factors delaying recovery', $label_font);
	renderTextField( $s_fact_delay );

	breakLine();
	$sect->writeText('Do you recommend referral to workplace rehabilitation provider?', $label_font);
	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->assreq == 'Yes')
		$checkbox->setChecked();
	$sect->writeText('Yes', $label_font);

	$checkbox = $sect->addCheckbox();
	if( $a_medical_cert->assreq != 'Yes')
		$checkbox->setChecked();
	$sect->writeText('No', $label_font);


	breakLine(3);
	$sect->writeText('TREATING MEDICAL PRACTITIONER DETAILS<br>', $subtitle_font);

	breakLine();
	$sect->writeText('I certify that I am the', $label_font);
	$checkbox = $sect->addCheckbox();
	if( $a_patient->doctor_agrees == 'Yes')
		$checkbox->setChecked();
	$sect->writeText('nominated treating doctor or ', $label_font);
	$checkbox = $sect->addCheckbox();
	if( $a_patient->doctor_agrees == 'No')
		$checkbox->setChecked();
	$sect->writeText('treating specialist (please tick) and I have examined this patient. The information and medical opinions contained in this certificate of capacity are, to the best of my knowledge, true and correct.', $label_font);

	breakLine(2);
	$sect->writeText('Signature<tab><tab>Date(DD/MM/YYYY)<br><tab><tab>', $label_font);
	renderDate( $a_int_date2 );

	breakLine(4);
	$sect->writeText('Name    ', $label_font);
	renderTextField( $a_patient->doctor_name .' - '. CHROMIS_STAMP, false );

	breakLine(2);
	$sect->writeText('Address (must be residential address – not PO Box)  ', $label_font);
	renderTextField( CHROMIS_ADDRESS, false );

	breakLine(2);
	$sect->writeText('Suburb    ', $label_font);
	renderTextField( $a_user['LocationSuburb'], false );

	$sect->writeText('    State    ', $label_font);
	renderTextField( $a_user['LocationState'], false );

	$sect->writeText('    PostCode    ', $label_font);
	renderTextField( $a_user['LocationPostcode'], false );

	breakLine(2);
	$sect->writeText('Telephone Number    ', $label_font);
	renderTextField( CHROMIS_PHONE, false );

	$sect->writeText('    Provide Number    ', $label_font);
	renderTextField(trim($a_user['MPN']), false );

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	if( $a_patient->doctor_agrees == 'Yes')
		$checkbox->setChecked();
	$sect->writeText('I agree to be the nominated treating doctor for the ongoing management of this person’s injury, treatment and recovery at/return to work (tick if you consent).<br><br>', $label_font);

	// Section 3
//	$sect->insertPageBreak();
	breakLine(2);
	$sect->writeText('Section 3: Employment declaration ', $section_font);
	$sect->writeText('(not to be completed by the treating medical practitioner)', new PHPRtfLite_Font(8, $font_name));
	breakLine(2);
	$sect->writeText('This section is to be completed by the person prior to sending to the insurer (or employer).<br>', $label_font);

	breakLine();
	$sect->writeText('First Name    ', $label_font);
	renderTextField( $a_patient->othernames, false );
	$sect->writeText('<tab>LastName    ', $label_font);
	renderTextField( $a_patient->surname, false );
	
	breakLine(2);
	$sect->writeText('Date of Birth(DD/MM/YYYY)    ', $label_font);
	renderDate( $a_dob );
	
	breakLine(2);
	$sect->writeText('Worker`s Address    ', $label_font);
	renderTextField( implode(', ', $a_patient_address),false );

	breakLine(2);
	$checkbox = $sect->addCheckbox();
	$sect->writeText('I have', $label_font);
	$checkbox = $sect->addCheckbox();
	$sect->writeText(' I have not(tick appropriate box)', $label_font);

	breakLine(2);
	$sect->writeText('engaged in any form of paid employment, self employment or voluntary work for which I have received or am entitled to receive payment in money or otherwise since the last certificate was provided, that I have not yet declared to the insurer.<br>', $label_font);
	$sect->writeText('If so, please provide details below.', $label_font);
	breakLine(10);
	
	$sect->writeText('I declare that the details I have given on this declaration are true and correct, knowing that false declarations are punishable by law.', $label_font);
	breakLine(2);
	$sect->writeText('Signature <tab>Date (DD/MM/YYYY)', $label_font);
	breakLine(5);

	// Footer
	$sect->writeText('Catalogue No. SIRA08719<br>', new $footer_font);
	$sect->writeText('State Insurance Regulatory Authority, 92–100 Donnison Street, Gosford, NSW 2250 Locked Bag <br>', new $footer_font);
	$sect->writeText('2906, Lisarow, NSW 2252 | Customer Experience 13 10 50<br>', new $footer_font);
	$sect->writeText('Website www.sira.nsw.gov.au<br>', new $footer_font);
	$sect->writeText('© Copyright State Insurance Regulatory Authority 0318<br>', new $footer_font);
	

	$rtf->save($output_file);

	downloadRTF( $s_file_name );
	exit;
}

function downloadRTF( $s_file_name ){
	$output_file = RTF_WRITE_FOLDER . $s_file_name. '.rtf'; 

	if (file_exists( $output_file)) {
        header('Content-Description: File Transfer');
		header("Content-type: application/rtf");
		header('Content-Disposition: attachment; filename='. basename( $output_file ));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize( $output_file));
        ob_clean();
        flush();
        readfile( $output_file);
        exit;
    }
}

