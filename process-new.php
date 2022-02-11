<?php
require_once('../config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');

//  Form 2 - Processing WC form from doctor
// patient record -- medicare number
// [date_first_seen] => // In patient area

// [pre_existing_factors] //Detail any pre-existing factors which may be relevant to this condition
// [post_desc] => Yes // Do you require a copy of the position description/work duties?
// [est_return_to_work] => 3 weeks
// [fact_delay] =>  //factors dlaying return to work
// [comment] => test //comment
// referral

if( ISSET($_POST['mode']) && $_POST['mode']=='form2') {
    $a_patient =  new Patient;
    $a_patient->id= (int)$_POST['paitentid'];
    $a_patient->Load();

    $a_patient->cons_status     = $_POST['Consult'];
    $a_patient->injury_desc     = $_POST['InjuryDetails'];
    $a_patient->injury_dateof   = re_format_date($_POST['InjuryDate']);
    //$a_patient->examdate        = re_format_date($_POST['EXDate']);
    $a_patient->doctor_name     = $_POST['Doctor'];
    $a_patient->doctor_location = $_POST['Location'];
    $a_patient->doctor_agrees   = $_POST['Commit'];
    $a_patient->date_first_seen = re_format_date($_POST['date_first_seen']);
    //$a_patient->medicare_no

    $a_patient->Save();

    $a_medical_cert = new MedicalCert;
    $a_medical_cert->paitentid           = (int)$a_patient->id;
    $a_medical_cert->diagnosis           = $_POST['diagnosis'];
    $a_medical_cert->work_is_factor      = $_POST['Contribute'];
    $a_medical_cert->fit_for_work_status = $_POST['WFit'];
    $a_medical_cert->exam_date           = re_format_date($_POST['exam_date']);
    $a_medical_cert->manag_plan          = $_POST['MPlan'];
    $a_medical_cert->treat_rev_date      = re_format_date($_POST['TReview']);
    $a_medical_cert->unfitfrom           = re_format_date($_POST['UnfitFrom']);
    $a_medical_cert->unfitto             = re_format_date($_POST['UnfitTo']);
    $a_medical_cert->suitfrom            = re_format_date($_POST['SuitFrom']);
    $a_medical_cert->suitto              = re_format_date($_POST['SuitTo']);
    $a_medical_cert->modfrom             = re_format_date($_POST['ModFrom']);
    $a_medical_cert->assreq              = $_POST['AssReq'];
    $a_medical_cert->has_cap_for_duration       = $_POST['i1'];
    $a_medical_cert->has_cap_for_duration_days  = $_POST['i2'];
    $a_medical_cert->has_cap_for_liftingupto    = $_POST['i3'];
    $a_medical_cert->has_cap_for_walkingupto    = $_POST['i4'];
    $a_medical_cert->has_cap_for_sittingupto    = $_POST['i5'];
    $a_medical_cert->has_cap_for_standingupto   = $_POST['i9'];
    $a_medical_cert->has_cap_for_travellingupto = $_POST['i7'];
    $a_medical_cert->has_cap_for_keyingupto     = $_POST['i6'];
    $a_medical_cert->other_restrictions         = $_POST['OTHER_RESTRICTIONS'];
    $a_medical_cert->other_restrictions_details = '';
    if(is_array($_POST['OTH'])){
        $a_medical_cert->other_restrictions_details = implode(", ",$_POST['OTH']);
    }
    $a_medical_cert->other_restrictions_other = $_POST['OTH_TXT'];
    $a_medical_cert->fitness_review_date      = re_format_date($_POST['FReview']);
    $a_medical_cert->pre_existing_factors     = $_POST['pre_existing_factors'];
    $a_medical_cert->post_desc                = $_POST['post_desc'];
    $a_medical_cert->est_return_to_work       = $_POST['est_return_to_work'];
    $a_medical_cert->fact_delay               = $_POST['fact_delay'];
    $a_medical_cert->comment                  = $_POST['comment'];
    $a_medical_cert->referral                 = $_POST['referral'];

    $a_medical_cert->Save();

    $_SESSION['output'] = '';
    $_SESSION['output'] = $_POST['output'];

    header('Location: '. SECURE_URL . DOC_PROCESSED .'?i=' . $a_medical_cert->id );
    exit();
}
