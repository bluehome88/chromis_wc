<?php
/*
 * Copyright 2013 ian skea <ian.skea@webprogrammer.com.au>
 */
/*
    Updated by Yakov 2022
*/
if( TESTING ){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once (__DIR__ . '/fpdm/fpdm.php');

class MakeWcPdf {

    function bootstrap( $s_file_name, $a_patient, $a_medical_cert, $a_user, $b_return_status = false, $b_page_2_only = false ){

        $s_fdf_name = '';
        $s_fdf_name = $this->makefdf(
            $s_file_name,
            $a_patient,
            $a_medical_cert,
            $a_user
        );

        $s_status = $this->makePDF( $s_fdf_name, $s_file_name, true, $b_page_2_only);
        $this->downloadFile( $s_file_name );
        if( $b_return_status == true ){
            return $s_status;
        }
    }

    public function arrayLoader( $a ){
        $a_r = array();
        foreach( $a as $v ){
            if( trim($v) != '' ){
                $a_r[] = $v;
            }
        }
        return $a_r;
    }

    public function makefdf(
        $s_file_name,
        Patient $a_patient,
        MedicalCert $a_medical_cert,
        array $a_user,
        $b_testing = false
    ) {

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
        $a_patient_address = $this->arrayLoader(
            array(
                $a_patient->address,
                $a_patient->suburb,
                $a_patient->state .' '. $a_patient->postcode
            )
        );

        $a_emp_details = array();
        $a_emp_details  = $this->arrayLoader(
            array(
                $a_patient->emp_name,
                $a_patient->emp_address,
                $a_patient->emp_suburb,
                $a_patient->emp_state .' '. $a_patient->emp_postcode
            )
        );

        $a_doc_loc = array();
        $a_doc_loc  = $this->arrayLoader(
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

        $s = ''; //string
        $s = '%FDF-1.2
%âãÏÓ
1 0 obj
<<
/FDF
<</Fields[
<</V/'. ( $a_patient->cons_status == 'Initial' ? 'A' : '' ) .'/T(Check Box 1)
>><</V/'. $s_work_is_factor .'/T(Check Box 2)
>><</V/'. ( $a_medical_cert->post_desc == 'Yes' ? 'A' : 'B' ) .'/T(Check Box 3)
>><</V/'. $a_ffwork['fit'] .'/T(Check Box 4)
>><</V/'. $a_ffwork['suitable'] .'/T(Check Box 5)
>><</V/'. $a_ffwork['unfit'] .'/T(Check Box 6)
>><</V/'. $s_ass_req .'/T(Check Box 7)
>><</V/'. ( $a_patient->doctor_agrees == 'Yes' ? 'A' : '' ) .'/T(Check Box 8)
>><</V/'. ( $a_patient->doctor_agrees == 'Yes' ? 'A' : 'B' ) .'/T(Check Box 9)
>><</V//T(Check Box 10)

>><</V('. $a_patient->othernames .')/T(1)
>><</V('. $a_patient->surname .')/T(2)
>><</V('. ( isset($a_dob[2]) ? $a_dob[2] : '' ) .')/T(3)
>><</V('. ( isset($a_dob[1]) ? $a_dob[1] : '' ) .')/T(4)
>><</V('. ( isset($a_dob[0]) ? $a_dob[0] : '' ) .')/T(5)
>><</V('.  implode(', ', $a_patient_address) .')/T(6)
>><</V('. $a_patient->claimno .')/T(7)
>><</V('. $a_patient->medicare_no .')/T(8)

>><</V('. $s_occupation .')/T(10)
>><</V('. $s_emp_details .')/T(11)
>><</V('. ( isset($a_int_date[2]) ? $a_int_date[2] : '' ) .')/T(12)
>><</V('. ( isset($a_int_date[1]) ? $a_int_date[1] : '' ) .')/T(13)
>><</V('. ( isset($a_int_date[0]) ? $a_int_date[0] : '' ) .')/T(14)
>><</V('. $a_medical_cert->diagnosis .')/T(15)

>><</V('. ( isset($a_inj_date[2]) ? $a_inj_date[2] : '' ) .')/T(16)
>><</V('. ( isset($a_inj_date[1]) ? $a_inj_date[1] : '' ) .')/T(17)
>><</V('. ( isset($a_inj_date[0]) ? $a_inj_date[0] : '' ) .')/T(18)

>><</V('. ( isset($a_first_seen_date[2]) ? $a_first_seen_date[2] : '' ) .')/T(19)
>><</V('. ( isset($a_first_seen_date[1]) ? $a_first_seen_date[1] : '' ) .')/T(20)
>><</V('. ( isset($a_first_seen_date[0]) ? $a_first_seen_date[0] : '' ) .')/T(21)

>><</V('. $s_how_related .')/T(22)
>><</V('. $s_per_factors .')/T(23)
>><</V('. $a_patient->othernames .' '. $a_patient->surname .')/T(24)
>><</V('. $a_patient->claimno .')/T(25)
>><</V('. $a_medical_cert->manag_plan .')/T(26)
>><</V('. $a_medical_cert->referral .')/T(27)

>><</V('. ( isset($a_suit_from[2]) ? $a_suit_from[2] : '' ) .')/T(28)
>><</V('. ( isset($a_suit_from[1]) ? $a_suit_from[1] : '' ) .')/T(29)
>><</V('. ( isset($a_suit_from[0]) ? $a_suit_from[0] : '' ) .')/T(30)

>><</V('. ( isset($a_suit_to[2]) ? $a_suit_to[2] : '' ) .')/T(31)
>><</V('. ( isset($a_suit_to[1]) ? $a_suit_to[1] : '' ) .')/T(32)
>><</V('. ( isset($a_suit_to[0]) ? $a_suit_to[0] : '' ) .')/T(33)

>><</V('. ( isset($a_duration['h']) ? $a_duration['h'] : '' ) .')/T(34)
>><</V('. ( isset($a_duration['d']) ? $a_duration['d'] : '' ) .')/T(35)

>><</V('. ( isset($a_unfit_to[2]) ? $a_unfit_from[2] : '' ) .')/T(36)
>><</V('. ( isset($a_unfit_to[1]) ? $a_unfit_from[1] : '' ) .')/T(37)
>><</V('. ( isset($a_unfit_to[0]) ? $a_unfit_from[0] : '' ) .')/T(38)

>><</V('. ( isset($a_unfit_to[2]) ? $a_unfit_to[2] : '' ) .')/T(39)
>><</V('. ( isset($a_unfit_to[1]) ? $a_unfit_to[1] : '' ) .')/T(40)
>><</V('. ( isset($a_unfit_to[0]) ? $a_unfit_to[0] : '' ) .')/T(41)

>><</V('. $s_est_return_to_work .')/T(42)
>><</V('. $s_fact_delay .')/T(43)
>><</V('. $s_has_cap_for_liftingupto .')/T(44)
>><</V('. $s_has_cap_for_sittingupto .')/T(45)
>><</V('. $s_has_cap_for_standingupto .')/T(46)
>><</V('. $s_has_cap_for_walkingupto .')/T(47)
>><</V('. $s_has_cap_for_keyingupto .')/T(48)
>><</V('. $s_has_cap_for_travellingupto .')/T(49)
>><</V('. $s_rest .')/T(50)

>><</V('. ( isset($a_review_date[2] ) && $a_review_date[2] != '00' ? $a_review_date[2] : '' ) .')/T(51)
>><</V('. ( isset($a_review_date[1] ) && $a_review_date[1] != '00' ? $a_review_date[1] : '' ) .')/T(52)
>><</V('. ( isset($a_review_date[0] ) && $a_review_date[0] != '0000' ? $a_review_date[0] : '' ) .')/T(53)

>><</V('. $s_comment .')/T(54)

>><</V('. ( isset($a_int_date2[2]) ? $a_int_date2[2] : '' ) .')/T(55)
>><</V('. ( isset($a_int_date2[1]) ? $a_int_date2[1] : '' ) .')/T(56)
>><</V('. ( isset($a_int_date2[0]) ? $a_int_date2[0] : '' ) .')/T(57)

>><</V('. $a_patient->doctor_name .' - '. CHROMIS_STAMP .')/T(58)
>><</V('. CHROMIS_ADDRESS .')/T(59)
>><</V('. CHROMIS_PHONE .')/T(60)
>><</V('. trim($a_user['MPN']) .')/T(61)

>><</V('. $a_patient->othernames .')/T(62)
>><</V('. $a_patient->surname .')/T(63)
>><</V('. ( $a_dob[2] ? $a_dob[2] : '' ) .')/T(64)
>><</V('. ( $a_dob[1] ? $a_dob[1] : '' ) .')/T(65)
>><</V('. ( $a_dob[0] ? $a_dob[0] : '' ) .')/T(66)
>><</V('.  implode(', ', $a_patient_address) .')/T(67)
>><</V('. trim($a_patient->claimno) .')/T(68)

>>]
>>
>>
endobj
trailer
<</Root 1 0 R>>
%%EOF';
        $r = '';
        if( $b_testing == false){
            $r = $s_file_name = uniqid('wc');
            //write file
            $o_file = PDF_WRITE_FOLDER . $s_file_name . '.fdf';
            file_put_contents( $o_file, $s );
        } else {
            $r = $s;
        }
        return $r;
    }

    function makePDF( $s_fdf_name, $s_file_name, $b_visual_mod = false, $b_page_2_only = false ){

        if( $b_visual_mod === true ){
            $r = exec(PDFTK_PATH .'pdftk '. PDF_WRITE_FOLDER .'workcover-certificate-capacity-1300-edited.pdf fillform '. PDF_WRITE_FOLDER . $s_fdf_name. '.fdf output '. PDF_WRITE_FOLDER . $s_file_name. '.pdf');
        } else {
            $r = exec(PDFTK_PATH .'pdftk '. PDF_WRITE_FOLDER .'workcover-certificate-capacity-1300.pdf '. PDF_WRITE_FOLDER . $s_fdf_name. '.fdf output '. PDF_WRITE_FOLDER . $s_file_name. '.pdf');
        }

        if( $b_page_2_only === true ){
            rename(PDF_WRITE_FOLDER . $s_file_name. '.pdf', PDF_WRITE_FOLDER . $s_file_name. '.tmp.pdf');
            $r = exec(PDFTK_PATH .'pdftk '. PDF_WRITE_FOLDER . $s_file_name. '.tmp.pdf cat 2-2 output '. PDF_WRITE_FOLDER . $s_file_name. '.pdf');
        }

        unlink( PDF_WRITE_FOLDER . $s_fdf_name. '.fdf' );

        return $r;
    }

    function downloadFile( $s_file_name ){
        if (file_exists( PDF_WRITE_FOLDER .  $s_file_name .'.pdf')) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='. basename( PDF_WRITE_FOLDER . $s_file_name .'.pdf'));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize( PDF_WRITE_FOLDER .  $s_file_name .'.pdf'));
            ob_clean();
            flush();
            readfile( PDF_WRITE_FOLDER .  $s_file_name .'.pdf');
            exit;
        }
    }


    /**
     * Destructor of class MakeWcPdf.
     *
     * @return void
     */
    public function __destruct()
    {
        // ...
    }

    // ...
    public function generatePDF( $s_file_name, $a_patient, $a_medical_cert, $a_user ){
        // preprocess data 
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
                $s_work_is_factor = 'Yes';
                break;
            case 'No':
                $s_work_is_factor = '';
                break;
            default:
                $s_work_is_factor = '';
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

        $fields = array(
            'Check Box 1' => $a_patient->cons_status == 'Initial' ? 'A' : '',
            // 'Check Box 2' => $s_work_is_factor,
            //'Check Box 3' => $a_medical_cert->post_desc == 'Yes' ? 'A' : '',
            'Check Box 4' => $a_ffwork['fit'],
            'Check Box 5' => $a_ffwork['suitable'],
            'Check Box 6' => $a_ffwork['unfit'],
            // 'Check Box 7' => $s_ass_req,
            'Check Box 8' => $a_patient->doctor_agrees == 'Yes' ? 'A' : '',
            // 'Check Box 9' => $a_patient->doctor_agrees == 'Yes' ? 'A' : 'B',
            '1' => $a_patient->othernames,
            '2' => $a_patient->surname,
            '3' => isset($a_dob[2]) ? $a_dob[2] : '',
            '4' => isset($a_dob[1]) ? $a_dob[1] : '',
            '5' => isset($a_dob[0]) ? $a_dob[0] : '',
            '6' => implode(', ', $a_patient_address),
            '7' => $a_patient->claimno,
            '8' => $a_patient->medicare_no,
            '10' => $s_occupation,
            '11' => $s_emp_details,
            '12' => isset($a_int_date[2]) ? $a_int_date[2] : '',
            '13' => isset($a_int_date[1]) ? $a_int_date[1] : '',
            '14' => isset($a_int_date[0]) ? $a_int_date[0] : '',
            '15' => $a_medical_cert->diagnosis,
            '16' => isset($a_inj_date[2]) ? $a_inj_date[2] : '',
            '17' => isset($a_inj_date[1]) ? $a_inj_date[1] : '',
            '18' => isset($a_inj_date[0]) ? $a_inj_date[0] : '',
            '19' => isset($a_first_seen_date[2]) ? $a_first_seen_date[2] : '',
            '20' => isset($a_first_seen_date[1]) ? $a_first_seen_date[1] : '',
            '21' => isset($a_first_seen_date[0]) ? $a_first_seen_date[0] : '',
            '22' => $s_how_related,
            '23' => $s_per_factors,
            '24' => $a_patient->othernames .' '. $a_patient->surname,
            '25' => $a_patient->claimno,
            '26' => $a_medical_cert->manag_plan,
            '27' => $a_medical_cert->referral,
            '28' => isset($a_suit_from[2]) ? $a_suit_from[2] : '',
            '29' => isset($a_suit_from[1]) ? $a_suit_from[1] : '',
            '30' => isset($a_suit_from[0]) ? $a_suit_from[0] : '',
            '31' => isset($a_suit_to[2]) ? $a_suit_to[2] : '',
            '32' => isset($a_suit_to[1]) ? $a_suit_to[1] : '',
            '33' => isset($a_suit_to[0]) ? $a_suit_to[0] : '',
            '34' => isset($a_duration['h']) ? $a_duration['h'] : '',
            '35' => isset($a_duration['d']) ? $a_duration['d'] : '',
            '36' => isset($a_unfit_to[2]) ? $a_unfit_from[2] : '',
            '37' => isset($a_unfit_to[1]) ? $a_unfit_from[1] : '',
            '38' => isset($a_unfit_to[0]) ? $a_unfit_from[0] : '',
            '39' => isset($a_unfit_to[2]) ? $a_unfit_from[2] : '',
            '40' => isset($a_unfit_to[1]) ? $a_unfit_from[1] : '',
            '41' => isset($a_unfit_to[0]) ? $a_unfit_from[0] : '',
            '42' => $s_est_return_to_work,
            '43' => $s_fact_delay,
            '44' => $s_has_cap_for_liftingupto,
            '45' => $s_has_cap_for_sittingupto,
            '46' => $s_has_cap_for_standingupto,
            '47' => $s_has_cap_for_walkingupto,
            '48' => $s_has_cap_for_keyingupto,
            '49' => $s_has_cap_for_travellingupto,
            '50' => $s_rest,
            '51' => isset($a_review_date[2] ) && $a_review_date[2] != '00' ? $a_review_date[2] : '',
            '52' => isset($a_review_date[1] ) && $a_review_date[1] != '00' ? $a_review_date[1] : '',
            '53' => isset($a_review_date[0] ) && $a_review_date[0] != '00' ? $a_review_date[0] : '',
            '54' => $s_comment,
            '55' => isset($a_int_date2[2]) ? $a_int_date2[2] : '',
            '56' => isset($a_int_date2[1]) ? $a_int_date2[1] : '',
            '57' => isset($a_int_date2[0]) ? $a_int_date2[0] : '',
            '58' => $a_patient->doctor_name .' - '. CHROMIS_STAMP,
            '59' => CHROMIS_ADDRESS,
            '60' => CHROMIS_PHONE,
            '61' => trim($a_user['MPN']),
            '62' => $a_patient->othernames,
            '63' => $a_patient->surname,
            '64' => $a_dob[2] ? $a_dob[2] : '',
            '65' => $a_dob[1] ? $a_dob[1] : '',
            '66' => $a_dob[0] ? $a_dob[0] : '',
            '67' => implode(', ', $a_patient_address),
            '68' => trim($a_patient->claimno)
        );

        $pdf = new FPDM(PDF_WRITE_FOLDER .'workcover-certificate-capacity-1300-template.pdf');
        $pdf->useCheckboxParser = true; // Checkbox parsing is ignored (default FPDM behaviour) unless enabled with this setting
        $pdf->Load($fields, true);
        $pdf->Merge();
        $pdf->Output('D', $s_file_name. '.pdf');
        $pdf->Output('F', PDF_WRITE_FOLDER. $s_file_name. '.pdf');
    }
}
