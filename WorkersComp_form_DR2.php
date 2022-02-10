<?php
require_once('../../etc/config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');
use PFBC\Form;
use PFBC\Element;
use PFBC\Validation;
require_once(ROOT_FOLDER . 'lib/pfbc3.1-php5.3/PFBC/Form.php');

$a_patient = new patient;

if( isset($_GET['id'])) {
    $a_patient->id = (int)$_GET['id'];
    $a_patient->Load();
} else {
    die('PatientID missing');
}

$s_cons_status = '';
$s_cons_status = $a_patient->cons_status ? $a_patient->cons_status : 'Initial';

$js  = '    <script src="./scripts/wc-form.js"></script>' . "\n";
$js .= '    <script src="./scripts/jquery.validate.min.js"></script>' . "\n";
$js .= '    <script src="./scripts/jqEasyCharCounter/jquery.jqEasyCharCounter.min.js"></script>' . "\n";

$db = getDBConnection();
$stmt = $db->prepare("select id from medical_cert where paitentid=? order by id desc limit 1");
$stmt->bind_param('s', $a_patient->id );
$stmt->execute();
$stmt->bind_result( $certId );
$stmt->fetch();
$stmt->close();
$db->close();

$a_medical_cert =  new MedicalCert;
$a_medical_cert->id = (int)$certId;
$a_medical_cert->Load();

$user = $_SESSION['User'];

$js .= '    <script> var o_def = {consult:"'. $s_cons_status .'", wfit:"'. $a_medical_cert->fit_for_work_status .'",other:"'. $a_medical_cert->other_restrictions .'"} </script>' . "\n";

echo getHeader('Medical certificate form', $s_menu, $js, true);

$form = new Form('DR_WCMC');
$form->configure(array(
    'prevent' => array('bootstrap', 'jQuery'),
    'action' => SECURE_URL . DOC_PROCESS
));

$form->addElement(new Element\Hidden('paitentid', $a_patient->id));
$form->addElement(new Element\Hidden('mode', 'form2'));
$form->addElement(new Element\HTML('<h6>&nbsp;</h6>'));

$form->addElement(new Element\HTML('
    <table class="docForm">
    <tr>
        <td>Consultation:</td>
        <td><label for="Consult" data-generated="true" class="error"></label>'));


$a_options = array();
$a_options = array(
    'Initial',
    'First Progress',
    'Progress',
    'Final'
);

$form->addElement(new Element\Radio(
    '',
    'Consult',
    $a_options,
    array(
        'value'=> $s_cons_status,
        'required' => 'required',
        'class' => 'required'
    )
));



$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Date of examination:</td>
        <td>
'));

// set to todays date
$value = date('d/m/Y');

$form->addElement(new Element\Textbox(
    '',
    'exam_date',
    array(
        'value' => $value,
        'required' => 'required',
        'class' => 'required date',
        'placeholder' => 'dd/mm/yyyy',
        'id' => 'exam_date'
    )
));

$form->addElement(new Element\HTML('        </td>
     </tr>
</table>'));

$a_emp = arrayLoader( array(
    $a_patient->emp_name,
    $a_patient->emp_address,
    $a_patient->emp_suburb .' '. $a_patient->emp_state .' '. $a_patient->emp_postcode
));

$s_emp = implode( $a_emp, '<br />');

$o_date = null;
$o_date = new DateTime( $a_patient->dob );
$value = '';
$value = $o_date->format('d/m/Y');

$form->addElement(new Element\HTML('<h6>PART A: Retrieved patient details</h6>
<table class="docForm">
<tr>
    <td>Patient\'s name:</td>
    <td>'. $a_patient->othernames .'&nbsp;'. $a_patient->surname .'</td>
</tr>
<tr>
    <td>Date of birth:</td>
    <td>'. $value .'&nbsp;</td>
</tr>
<tr>
    <td>Patient\'s address:</td>
    <td>'. $a_patient->address .',<br />'. $a_patient->suburb .', '. $a_patient->state .' '. $a_patient->postcode .'</td>
</tr>
<tr>
    <td>Claim Number</td>
    <td>'. ($a_patient->claimno ? $a_patient->claimno : '-') .'&nbsp;</td>
</tr>
<tr>
    <td>Medicare Number</td>
    <td>'. ($a_patient->medicare_no ? $a_patient->medicare_no : '-') .'&nbsp;</td>
</tr>
<tr>
    <td>Patient\'s occupation/job title:</td>
    <td>'.$a_patient->occupation .'</td>
</tr>
<tr>
    <td>Employer\'s name and contact details:</td>
    <td>'.  $s_emp .'</td>
</tr>
</table>

<h6>Part B: Medical Certification</h6>
<table class="docForm">
  <tr>
    <td>Diagnosis of work related injury/disease:</td>
    <td>'));

$form->addElement(new Element\Textarea(
    '',
    'diagnosis',
    array(
        'cols'=>40,
        'rows'=>4,
        'value'=>$a_medical_cert->diagnosis,
        'required' => 'required',
        'class' => 'required sml'
    )
));

$form->addElement(new Element\HTML('
</td>
  </tr>
  <tr>
    <td>Patient stated date of injury</td>
    <td>'));

$value = '';
if ( $a_patient->injury_dateof != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_patient->injury_dateof );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'InjuryDate',
    array(
        'value' => $value,
        'required' => 'required',
        'class' => 'required date',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
  </tr>
</table>
<div class="Initial">
    <h6>Initial certificate only</h6>
    <table class="docForm">
    <tr>
        <td>Patient was first seen at this practice/hospital for this injury/disease on:</td>
        <td>'));

$value = '';
if ( $a_patient->date_first_seen != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_patient->date_first_seen );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'date_first_seen',
    array(
        'value' => $value,
        'required' => 'required',
        'class' => 'required date',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('</td>
    </tr>
    <tr>
        <td>Injury/disease is consistent with patient\'s description of cause?</td>
        <td><label for="Contribute" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'Yes',
    'No',
    'Unknown'
);

$form->addElement(new Element\Radio(
    '',
    'Contribute',
    $a_options,
    array(
        'value'=> $a_medical_cert->work_is_factor,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('</td>
    </tr>
    <tr>
        <td>How is the injury/disease related to work?</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'InjuryDetails',
    array(
        'cols'=>40,
        'rows'=>11,
        'value'=>$a_patient->injury_desc,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('</td>
    </tr>
    <tr>
        <td>Detail any pre-existing factors which may be relevant to this condition:</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'pre_existing_factors',
    array(
        'cols'=>40,
        'rows'=>11,
        'value'=>$a_medical_cert->pre_existing_factors
    )
));

$form->addElement(new Element\HTML('</td>
    </tr>
    </table>
</div>

<h6>Management Plan for this Period</h6>
<table class="docForm">
<tr>
    <td>Treatment/medication type and duration.<br />Duration: short term =&lt; 6 weeks,<br />medium term = 6-12 weeks,<br />long term =&gt; 12 weeks.</td>
    <td>'));

$form->addElement(new Element\Textarea(
    '',
    'MPlan',
    array(
        'cols'=>40,
        'rows'=>11,
        'value'=>$a_medical_cert->manag_plan,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('</td>
    </tr>
    <tr>
        <td>Referral to another health care provider.<br />Provide details of provider and service requested, duration and frequency when relevant:</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'referral',
    array(
        'cols'=>40,
        'rows'=>11,
        'value'=>$a_medical_cert->referral
    )
));

$form->addElement(new Element\HTML('
    </td>
</tr>
</table>'));

$form->addElement(new Element\HTML('

<h6>Capacity for Employment &ndash; please consider the health benefits of work when completing this section</h6>
<table class="docForm">
    <tr>
        <td>Do you require a copy of the position description/work duties?</td>
        <td><label for="post_desc" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'Yes',
    'No'
);

$form->addElement(new Element\Radio(
    '',
    'post_desc',
    $a_options,
    array(
        'value'=> $a_medical_cert->post_desc,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
    </tr>
    <tr>
        <td>Patient:</td>
        <td><label for="WFit" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'fit' => 'Is FIT for pre-injury duties.',
    'suitable' => 'Has capacity for SOME type of employment.',
    'unfit' => 'Has NO current WORK CAPACITY for any employment.'
);

$form->addElement(new Element\Radio('',
    'WFit',
    $a_options,
    array(
        'value'=> $a_medical_cert->fit_for_work_status,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
</table>
<div class="suitable">
    <h6>Has capacity for SOME type of employment</h6>
    <table class="docForm">
    <tr>
        <td class="title">From</td>
        <td>'));

$value = '';
if ( $a_medical_cert->suitfrom != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_medical_cert->suitfrom );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'SuitFrom',
    array(
        'value' => $value,
        'class' => 'date suit',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">To</td>
        <td>'));

$value = '';
if ( $a_medical_cert->suitto != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_medical_cert->suitto );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'SuitTo',
    array(
        'value' => $value,
        'class' => 'date suit',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
      <td>Duration</td>
      <td class="field">'));

$a_options = array();
$a_options = array(
    ''=>'Please select (hours per day)',
);
for($i=1; $i<=12; $i++) {
    $s_posfix = ' hours per day';
    if($i == 1){
        $s_posfix = ' hour per day';
    }
    $a_options[$i] = $i . $s_posfix;
}
$form->addElement(new Element\Select('',
    'i1',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_duration,
        'class' => 'suit'
    )
));

$a_options = array();
$a_options = array(
    ''=>'Please select (days per week)'
);
for($i=1; $i<=7; $i++) {
    $s_posfix = ' days per week';
    if($i == 1){
        $s_posfix = ' day per week';
    }
    $a_options[$i] = $i . $s_posfix;
}
$form->addElement(new Element\Select('',
    'i2',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_duration_days,
        'class' => 'suit'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    </table>
</div>

<div class="unfit">
    <h6>Has NO current WORK CAPACITY for any employment</h6>
    <table class="docForm">
    <tr>
    <td class="title">From</td>
        <td>'));

$value = '';
if ( $a_medical_cert->unfitfrom != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_medical_cert->unfitfrom );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'UnfitFrom',
    array(
        'value' => $value,
        'class' => 'date unfi',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">To</td>
        <td>'));

$value = '';
if ( $a_medical_cert->unfitto != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_medical_cert->unfitto );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'UnfitTo',
    array(
        'value' => $value,
        'class' => 'date unfi',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">If no current work capacity, estimated time to return to any type of employment:</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'est_return_to_work',
    array(
        'cols'=>40,
        'rows'=>2,
        'value'=>$a_medical_cert->est_return_to_work,
        'class'=> 'unfi vsml'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    </table>
</div>

<div class="unfitsuit">
    <table class="docForm">
    <tr>
        <td class="title">Factors delaying recovery:</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'fact_delay',
    array(
        'cols'=>40,
        'rows'=>3,
        'value'=>$a_medical_cert->fact_delay,
        'class'=>'exsml'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">Do you recommend referral to workplace rehabilitation provider?</td>
        <td><label for="AssReq" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'Yes' => 'Yes',
    'No' => 'No',
);
$form->addElement(new Element\Radio('',
    'AssReq',
    $a_options,
    array(
        'value'=> $a_medical_cert->assreq,
        'class'=> 'unfi'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    </table>

</div>

    <h6 class="headroom">Capacity &ndash; If the patient is fit for pre-injury duties this section does not need to be completed. For all other patients please consider activities of daily living currently being performed</h6>
    <table class="docForm">
    <tr>
        <td>Lifting/carrying capacity</td>
        <td class="field">'));

$a_options = array();
$a_options = array(
    'Unrestricted'=>'Unrestricted',
    'As tolerated' =>'As tolerated',
    'No lifting advised' =>'No lifting advised'
);
for($i=1; $i<=30; $i++) {
    $a_options[$i.'kg'] = $i.'kg';
}

$form->addElement(new Element\Select('',
    'i3',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_liftingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Sitting tolerance</td>
        <td class="field">'));

$a_options = array();
$a_options['Unrestricted']='Unrestricted';
$a_options['Minimize']='Minimize';
$a_options['As tolerated']='As tolerated';
$a_options['No Sitting']='No Sitting Advised';
$a_options['5 min']='5 min';
$a_options['10 min']='10 min';
$a_options['15 min']='15 min';
$a_options['20 min']='20 min';
$a_options['25 min']='25 min';
$a_options['30 min']='30 min';
$a_options['45 min']='45 min';
$a_options['60 min']='60 min';
$a_options['90 min']='90 min';
$a_options['2 hrs']='2 hrs';
$a_options['3 hrs']='3 hrs';
$a_options['4 hrs']='4 hrs';
$a_options['5 hrs']='5 hrs';
$a_options['6 hrs']='6 hrs';
$a_options['7 hrs']='7 hrs';
$a_options['8 hrs']='8 hrs';

$form->addElement(new Element\Select('',
    'i5',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_sittingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
    </td>
</tr>
<tr>
    <td>Standing tolerance</td>
    <td class="field">'));

$a_options = array();
$a_options['Unrestricted']='Unrestricted';
$a_options['No standing or walking advised']='No standing or walking advised';
$a_options['No standing or walking on uneven ground']='No standing or walking on uneven ground';
$a_options['As tolerated']='As tolerated';
$a_options['5 min']='5 min';
$a_options['10 min']='10 min';
$a_options['15 min']='15 min';
$a_options['20 min']='20 min';
$a_options['25 min']='25 min';
$a_options['30 min']='30 min';
$a_options['45 min']='45 min';
$a_options['60 min']='60 min';
$a_options['90 min']='90 min';

$form->addElement(new Element\Select('',
    'i9',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_standingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
    </td>
</tr>
<tr>
    <td>Pushing/pulling ability</td>
    <td  class="field">'));

$a_options = array();
$a_options = array(
    'Unrestricted'=>'Unrestricted',
    'As tolerated' =>'As tolerated',
    'No pushing/pulling advised' =>'No pushing/pulling advised'
);
for($i=1; $i<=30; $i++) {
    $a_options[$i.'kg'] = $i.'kg';
}

$form->addElement(new Element\Select('',
    'i4',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_walkingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
    </td>
</tr>
<tr>
    <td>Bending/twisting/squatting ability</td>
    <td class="field">'));

$a_options = array(
    'Unrestricted'=>'Unrestricted',
    'As tolerated' =>'As tolerated',
    'No bending/twisting/squatting advised' =>'No bending/twisting/squatting advised'
);

$form->addElement(new Element\Select('',
    'i6',
    $a_options,
    array(
        'value'=> $a_medical_cert->has_cap_for_keyingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
    </td>
</tr>
<tr>
    <td>Driving ability</td>
    <td class="field">'));

$a_options2 = array();
$a_options2['Unrestricted']= 'Unrestricted';
$a_options2['Avoid clutching/manual vehicles']= 'Avoid clutching/manual vehicles';
$a_options2['No driving advised'] = 'No driving advised';
$a_options2['No driving on uneven ground'] = 'No driving on uneven ground';
$a_options2['As tolerated'] = 'As tolerated';
$a_options2['5 min']='5 min';
$a_options2['10 min']='10 min';
$a_options2['15 min']='15 min';
$a_options2['20 min']='20 min';
$a_options2['25 min']='25 min';
$a_options2['30 min']='30 min';
$a_options2['45 min']='45 min';
$a_options2['60 min']='60 min';
$a_options2['90 min']='90 min';
$a_options2['2 hrs']='2 hrs';
$a_options2['3 hrs']='3 hrs';
$a_options2['4 hrs']='4 hrs';
$a_options2['5 hrs']='5 hrs';
$a_options2['6 hrs']='6 hrs';
$a_options2['7 hrs']='7 hrs';
$a_options2['8 hrs']='8 hrs';

$form->addElement(new Element\Select('',
    'i7',
    $a_options2,
    array(
        'value'=> $a_medical_cert->has_cap_for_travellingupto,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    </table>

    <h6>Other capabilities or restrictions</h6>
    <table class="docForm">
    <tr>
    <td>Other (please specify) eg psychological considerations, keep wound clean and dry.</td>
    <td class="field"><label for="OTHER_RESTRICTIONS" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options['OTHER']='Yes';
$a_options['No']='No';

$form->addElement(new Element\Radio('',
    'OTHER_RESTRICTIONS',
    $a_options,
    array(
        'value'=> $a_medical_cert->other_restrictions,
        'class' => 'unfisuit'
    )
));

$form->addElement(new Element\HTML('
    </td></tr>
</table>

    <div class="other">
        <h6>&nbsp;</h6>
        <table class="docForm">
        <tr>
            <td class="title">No work above</td>
            <td class="field">'));

$a_values = array();
$a_values = explode(',', $a_medical_cert->other_restrictions_details);
foreach( $a_values as $k => $v ){
    $a_values[$k] = trim($v);
}


$form->addElement(new Element\Checkbox(
    '',
    'OTH[]',
    array(
        'work above shoulder ht' => 'Shoulder height',
        'work above chest ht' => 'Chest height',
        'work above waist ht' => 'Waist height',
    ),
    array(
        'value'=>$a_values,
        'class'=> 'atext'
    )
));



$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">Avoid / minimise</td>
        <td class="field">'));

$form->addElement(new Element\Checkbox(
    '',
    'OTH[]',
    array(
        'stairs' => 'Stairs',
        'ladders' => 'Ladders',
        'steps' => 'Steps',
        'walking uneven ground' => 'Walking on uneven ground',
    ),
    array(
        'value'=>$a_values,
        'class'=> 'atext'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">Lifting restriction applies to</td>
        <td class="field">'));

$form->addElement(new Element\Checkbox(
    '',
    'OTH[]',
    array(
        'LS lifting' => 'Left side',
        'RS lifting' => 'Right side'
    ),
    array(
        'value'=>$a_values,
        'class'=> 'atext'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td class="title">Other</td>
        <td class="field">'));

$form->addElement(new Element\Textarea(
    '',
    'OTH_TXT',
    array(
        'cols'=>40,
        'rows'=>'4',
        'value'=>$a_medical_cert->other_restrictions_other,
        'class'=>'sml otext'
    )
));

if($a_medical_cert->other_restrictions_other){
    $s_historic = '<p><b>Historic information from last certificate:</b><br />&quot;'. $a_medical_cert->other_restrictions_other .'&quot;</p>';
}

$form->addElement(new Element\HTML('
            '. $s_historic .'
            </td>
        </tr>
        </table>
    </div>

<h6>Review date</h6>
<table class="docForm">
    <tr>
        <td>Next review date:<br />If greater than 28 days, please provide clinical reasoning in comments.</td>
        <td>'));

$value = '';
if ( $a_medical_cert->fitness_review_date != '0000-00-00'){
    $o_date = null;
    $o_date = new DateTime( $a_medical_cert->fitness_review_date );
    $value = '';
    $value = $o_date->format('d/m/Y');
}
$form->addElement(new Element\Textbox(
    '',
    'FReview',
    array(
        'value' => $value,
        'class' => 'date unfisuit',
        'placeholder' => 'dd/mm/yyyy'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Comments:</td>
        <td>'));

$form->addElement(new Element\Textarea(
    '',
    'comment',
    array(
        'cols'=>40,
        'rows'=>4,
        'value'=>$a_medical_cert->comment,
        'class'=>'sml'
    )
));

if( isDoctor()){
    $doctorName = "Dr ".$user['Firstname']." ".$user['Lastname'];
    $doctorLocation = $user['LocationName'];
} else {
    $doctorName = $a_patient->doctor_name;
    $doctorLocation = $a_patient->doctor_location;
}
$form->addElement(new Element\Hidden('Doctor', $doctorName));
$form->addElement(new Element\Hidden('Location', $doctorLocation));
$form->addElement(new Element\HTML('
            </td>
        </tr>
        </table>

<h6>Medical practitioner details</h6>
<table class="docForm">
  <tr>
    <td>Doctors Name:</td>
    <td class="field">'. $doctorName .'</td>
  </tr>
  <tr>
    <td>Doctors Location:</td>
    <td class="field">
        '. $doctorLocation .'
    </td>
  </tr>
  <tr>
    <td>Please tick if you agree to be the nominated treating doctor for the ongoing management of this worker\'s injury and return to work:</td>
    <td class="field"><label for="Commit" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'Yes',
    'No'
);

$form->addElement(new Element\Radio(
    '',
    'Commit',
    $a_options,
    array(
        'value'=> $a_patient->doctor_agrees,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
    </tr>
    <tr>
        <td>PDF options:</td>
        <td><label for="output" data-generated="true" class="error"></label>'));

$a_options = array();
$a_options = array(
    'all' => 'Output the whole form.',
    'page2' => 'Output just page 2 of the form.'
);

$form->addElement(new Element\Radio('',
    'output',
    $a_options,
    array(
        'value'=> 'all',
        'required' => 'required',
        'class' => 'required',
        'id' => 'output'
    )
));


$form->addElement(new Element\HTML('</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>'));

$form->addElement(new Element\Button(' Save to Form '));
$form->addElement(new Element\HTML('</td></tr></table><p>&nbsp;</p>'));

$form->render();

echo getFooter();
