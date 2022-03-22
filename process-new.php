<?php
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

//  Form 2 - Processing WC form from doctor
// patient record -- medicare number
// [date_first_seen] => // In patient area

// [pre_existing_factors] //Detail any pre-existing factors which may be relevant to this condition
// [post_desc] => Yes // Do you require a copy of the position description/work duties?
// [est_return_to_work] => 3 weeks
// [fact_delay] =>  //factors dlaying return to work
// [comment] => test //comment
// referral

if (isset($_POST['mode']) && $_POST['mode'] == 'form2') {
    $a_patient =  new Patient;
    $a_patient->id = (int)$_POST['paitentid'];
    $a_patient->Load();

    $a_patient->cons_status     = isset($_POST['Consult']) ? $_POST['Consult'] : '';
    $a_patient->injury_desc     = isset($_POST['InjuryDetails']) ? $_POST['InjuryDetails'] : '';
    $a_patient->injury_dateof   = isset($_POST['InjuryDate']) ? re_format_date($_POST['InjuryDate']) : NULL;
    //$a_patient->examdate        = re_format_date($_POST['EXDate']);
    $a_patient->doctor_name     = isset($_POST['Doctor']) ? $_POST['Doctor'] : '';
    $a_patient->doctor_location = isset($_POST['Location']) ? $_POST['Location'] : '';
    $a_patient->doctor_agrees   = isset($_POST['Commit']) ? $_POST['Commit'] : '';
    $a_patient->date_first_seen = isset($_POST['date_first_seen']) ? re_format_date($_POST['date_first_seen']) : NULL;
    //$a_patient->medicare_no

    $a_patient->Save();

    $a_medical_cert = new MedicalCert;
    $a_medical_cert->paitentid           = (int)$a_patient->id;
    $a_medical_cert->diagnosis           = isset($_POST['diagnosis']) ? $_POST['diagnosis'] : '';
    $a_medical_cert->work_is_factor      = isset($_POST['Contribute']) ? $_POST['Contribute'] : '';
    $a_medical_cert->fit_for_work_status = isset($_POST['WFit']) ? $_POST['WFit'] : '';
    $a_medical_cert->exam_date           = isset($_POST['exam_date']) ? re_format_date($_POST['exam_date']) : NULL;
    $a_medical_cert->manag_plan          = isset($_POST['MPlan']) ? $_POST['MPlan'] : '';
    // $a_medical_cert->treat_rev_date      = re_format_date($_POST['TReview']);
    $a_medical_cert->unfitfrom           = isset($_POST['UnfitFrom']) && $_POST['UnfitFrom'] ? re_format_date($_POST['UnfitFrom']) : NULL;
    $a_medical_cert->unfitto             = isset($_POST['UnfitTo']) && $_POST['UnfitTo'] ? re_format_date($_POST['UnfitTo']) : NULL;
    $a_medical_cert->suitfrom             = isset($_POST['SuitFrom']) && $_POST['SuitFrom'] ? re_format_date($_POST['SuitFrom']) : NULL;
    $a_medical_cert->suitto             = isset($_POST['SuitTo']) && $_POST['SuitTo'] ? re_format_date($_POST['SuitTo']) : NULL;
    $a_medical_cert->modfrom             = isset($_POST['ModFrom']) && $_POST['ModFrom'] ? re_format_date($_POST['ModFrom']) : NULL;
    $a_medical_cert->assreq              = isset($_POST['AssReq']) ? $_POST['AssReq'] : '';
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
    if (is_array($_POST['OTH'])) {
        $a_medical_cert->other_restrictions_details = implode(", ", $_POST['OTH']);
    }
    $a_medical_cert->other_restrictions_other = isset($_POST['OTH_TXT']) ? $_POST['OTH_TXT'] : '';
    $a_medical_cert->fitness_review_date      = isset($_POST['FReview']) && $_POST['FReview'] ? re_format_date($_POST['FReview']) : NULL;
    $a_medical_cert->pre_existing_factors     = isset($_POST['pre_existing_factors']) ? $_POST['pre_existing_factors'] : '';
    $a_medical_cert->post_desc                = isset($_POST['post_desc']) ? $_POST['post_desc'] : '';
    $a_medical_cert->est_return_to_work       = isset($_POST['est_return_to_work']) ? $_POST['est_return_to_work'] : '';
    $a_medical_cert->fact_delay               = isset($_POST['fact_delay']) ? $_POST['fact_delay'] : '';
    $a_medical_cert->comment                  = isset($_POST['comment']) ? $_POST['comment'] : '';
    $a_medical_cert->referral                 = isset($_POST['referral']) ? $_POST['referral'] : '';

    $a_medical_cert->Save();

    $_SESSION['output'] = '';
    $_SESSION['output'] = $_POST['output'];

    header('Location: ' . SECURE_URL . DOC_PROCESSED . '?i=' . $a_medical_cert->id);
    exit();
}
