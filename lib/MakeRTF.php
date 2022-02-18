<?php 

require_once( __DIR__ . '/PHPRtfLite.php');
PHPRtfLite::registerAutoloader();

function generateRTF( $s_file_name, $a_patient, $a_medical_cert, $a_user ){
	$font_name = 'Helvetica';
	$output_file = RTF_WRITE_FOLDER . $s_file_name. '.rtf'; 

	$rtf = new PHPRtfLite();
	PHPRtfLite_Unit::setGlobalUnit(PHPRtfLite_Unit::UNIT_INCH);
	$rtf->setPaperWidth(8.27);
	$rtf->setPaperHeight(11.69);
	$rtf->setMargins(0.36, 0.15, 0.36, 0.39);

	// Heading Section
	$sect = $rtf->addSection();
	$sect->writeText('<br><br>Certificate of capacity/certificate of fitness<br>', new PHPRtfLite_Font(25, $font_name));

	$sect->writeText('For use with workers compensation and Compulsory Third Party (CTP) motor accident injury claims.<br>', new PHPRtfLite_Font(13, $font_name));

	$sect->writeText('For CTP claims: ‘Certificate of fitness’ means ‘certificate of fitness for work’. This certificate should be completed whether the person was employed at the time of the accident or not.<br>', new PHPRtfLite_Font(8, $font_name));

	$checkbox = $sect->addCheckbox();
	$checkbox->setChecked();
	$sect->writeText('Tick if this is the initial certificate for this claim.<br><br>', new PHPRtfLite_Font(10, $font_name));

	// Section 1
	$sect->writeText('Section 1: To be completed by the injured person or treating medical practitioner<br>', new PHPRtfLite_Font(14, $font_name));
	$sect->writeText('<br>First Name', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Last Name<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Date of Birth(DD/MM/YYYY)', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>Telephone Number<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Address (must be residential address – not PO Box)', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>Suburb<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('State', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>PostCode', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>Claim Number', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Medicare Number<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('Occupation/job title', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>Employer’s name and contact details (if applicable)<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('Injured person’s consent<br>', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('I consent to my treating medical practitioner, my employer (optional for CTP claims), the insurer, other medical practitioners or health related practitioners (whether consulting, treating or examining), workplace rehabilitation providers and SIRA exchanging information for the purpose of managing my injury and workers compensation/motor accident injury claim.<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('I understand this information will be used by SIRA and insurers to fulfill their functions under the motor accident insurance and workers compensation legislation.<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Signature', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Date(DD/MM/YYYY)<br><br>', new PHPRtfLite_Font(10, $font_name));

	// Section 2
	$sect->writeText('Section 2: To be completed by treating medical practitioner<br>', new PHPRtfLite_Font(14, $font_name));
	$sect->writeText('Medical certification<br>', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('Diagnosis of work related injury/disease or motor accident related injury(ies)<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Person’s stated date of injury/accident (DD/MM/YYYY)<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('Management plan for this period<br>', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('Treatment/medication type and duration<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Referral to another health service or rehabilitation provider (include details of provider type and service requested, duration and frequency when relevant)<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('Capacity for activities – ', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('If the person has capacity for pre-injury work this section does not need to be completed. For all others please consider activities of daily living currently being performed.<br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('Lifting/carrying capacity', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Sitting tolerance<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Standing tolerance', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Pushing/pulling ability<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Bending/twisting/squatting ability', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab><tab>Driving ability<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Other (please specify) eg psychological considerations, keep wound clean and dry<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Comments<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->insertPageBreak();
	

	$sect->writeText('<br><br>Capacity for Work', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('(please consider the health benefits of good work when completing this section).<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Where the word ‘capacity’ appears below it should be read as ‘fitness for work’ when the certificate is completed in a motor accident injury claim.<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Do you require a copy of the position description/work duties?<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>Yes', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>No<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('is fit for pre-injury work from<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('has capacity for some type of work from<tab>to<tab> for<tab> hours/day <tab> days/week<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('has no current capacity for any work from<tab> to <tab><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('If no current work capacity, estimated time to return to any type of employment<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Factors delaying recovery<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Do you recommend referral to workplace rehabilitation provider?<tab>Yes<tab>No<br><br>', new PHPRtfLite_Font(10, $font_name));

	$sect->writeText('TREATING MEDICAL PRACTITIONER DETAILS<br>', new PHPRtfLite_Font(12, $font_name));
	$sect->writeText('I certify that I am the nominated treating doctor or treating specialist (please tick) and I have examined this patient. The information and medical opinions contained in this certificate of capacity are, to the best of my knowledge, true and correct.<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Signature<tab>Date(DD/MM/YYYY)<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Name<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Address<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Suburb<tab>State<tab>Postcode<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Telephone Number<tab>Provide Number<br><br>', new PHPRtfLite_Font(10, $font_name));

	$checkbox = $sect->addCheckbox();
	$sect->writeText('I agree to be the nominated treating doctor for the ongoing management of this person’s injury, treatment and recovery at/return to work (tick if you consent).<br><br>', new PHPRtfLite_Font(10, $font_name));

	// Section 3
	$sect->writeText('Section 3: Employment declaration', new PHPRtfLite_Font(14, $font_name));
	$sect->writeText('(not to be completed by the treating medical practitioner)<br><br>', new PHPRtfLite_Font(9, $font_name));
	$sect->writeText('This section is to be completed by the person prior to sending to the insurer (or employer).<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('First Name<tab>LastName<br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('<tab>I have<tab>I have not(tick appropriate box)<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('engaged in any form of paid employment, self employment or voluntary work for which I have received or am entitled to receive payment in money or otherwise since the last certificate was provided, that I have not yet declared to the insurer.<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('If so, please provide details below.<br><br><br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('I declare that the details I have given on this declaration are true and correct, knowing that false declarations are punishable by law.<br>', new PHPRtfLite_Font(10, $font_name));
	$sect->writeText('Signature <tab>Date (DD/MM/YYYY)<br><br><br>', new PHPRtfLite_Font(10, $font_name));

	// Footer
	$sect->writeText('Catalogue No. SIRA08719<br>', new PHPRtfLite_Font(7, $font_name));
	$sect->writeText('State Insurance Regulatory Authority, 92–100 Donnison Street, Gosford, NSW 2250 Locked Bag <br>', new PHPRtfLite_Font(7, $font_name));
	$sect->writeText('2906, Lisarow, NSW 2252 | Customer Experience 13 10 50<br>', new PHPRtfLite_Font(7, $font_name));
	$sect->writeText('Website www.sira.nsw.gov.au<br>', new PHPRtfLite_Font(7, $font_name));
	$sect->writeText('© Copyright State Insurance Regulatory Authority 0318<br>', new PHPRtfLite_Font(7, $font_name));
	

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


// new PHPRtfLite_ParFormat()