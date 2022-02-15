<?php
$b_user_area = 1;
require_once('../../etc/config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

if( ISSET($_POST['mode']) && $_POST['mode']=='form1') {

    $a_patient =  new patient;

    if($_POST['id']!="" && $_POST['id'] != 0) {
        $a_patient->id= (int)$_POST['id'];
        $a_patient->Load();
    }
    $a_patient->examdate=re_format_date($_POST['EXDate']);
    $a_patient->claimno=$_POST['ClaimNo'];
    $a_patient->surname=$_POST['Surname'];
    $a_patient->othernames=$_POST['OtherNames'];
    $a_patient->address=$_POST['Address'];
    $a_patient->suburb=$_POST['Suburb'];
    $a_patient->state=$_POST['State'];
    $a_patient->postcode=$_POST['PCode'];
    $a_patient->phone=$_POST['WorkPhone'];
    $a_patient->mobile=$_POST['Mobile'];
    $a_patient->dob=$_POST['Year'].'-'.($_POST['month']).'-'.$_POST['day'];
    $a_patient->occupation=$_POST['Occupation'];
    $a_patient->hours_week=$_POST['HoursPWeek'];
    $a_patient->emp_name=$_POST['Employer'];
    $a_patient->emp_address=$_POST['EmpAdd'];
    $a_patient->emp_suburb=$_POST['EmpSub'];
    $a_patient->emp_state=$_POST['EmpState'];
    $a_patient->emp_postcode=$_POST['EmpPCode'];

    $a_patient->injury_dateof = re_format_date($_POST['injury_dateof']);
    $a_patient->date_first_seen = re_format_date($_POST['date_first_seen']);
    $a_patient->medicare_no =  $_POST['medicare_no'];

    $a_patient->Save();
}

$s_content = getHeader('Client record search', $s_menu, $js);

$s_content .= '
  <p>This medical certificate has been updated and saved.</p>
  <h4>What would you like to do now?</h4>
  <p>
    1) <a href="'. SECURE_URL . SEARCH_USER .'">Continue</a> with another medical certificate<br />
    2) <a href="'. SECURE_URL . LOGOUT .'">Log out</a>.
</p>
<p>&nbsp;</p>';

$s_content .= getFooter();

echo $s_content;
