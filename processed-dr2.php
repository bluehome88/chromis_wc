<?php
require_once('./config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');
require_once( ROOT_FOLDER . 'lib/MakeWcPdf.php' );
require_once( ROOT_FOLDER . 'lib/MakeRTF.php' );

if( isset($_GET['i']) && isset($_GET['j']) ){
    $a_medical_cert     =  new MedicalCert;
    $a_medical_cert->id = (int)$_GET['i'];
    $a_medical_cert->Load();
    $a_patient =  new patient;
    $a_patient->id = $a_medical_cert->paitentid;
    $a_patient->Load();

    $o = new MakeWcPdf;
    $s_file_name = '';
    $a_replace = array(' ', "'", '-', '"','(',')');
    $s_file_name = 'WCCC_'. date('Y_m_d') .'_'. str_replace( $a_replace, '', $a_patient->surname) .'_'. str_replace( $a_replace, '', $a_patient->othernames);

    if( $_SESSION['output'] != 'all' ){
      // $o->bootstrap(
      //     $s_file_name,
      //     $a_patient,
      //     $a_medical_cert,
      //     $_SESSION['User'],
      //     false,
      //     false
      // );
      $o->generatePDF( 
          $s_file_name, 
          $a_patient, 
          $a_medical_cert,
          $_SESSION['User'] );
    }
    else
      generateRTF( $s_file_name, $a_patient, $a_medical_cert, $_SESSION['User'] );

    exit();
}

$s_content = getHeader('Client record search', $s_menu, $js);

$s_content .= '<p>&nbsp;</p>
  <p>This medical certificate has been updated and saved.</p>
  <h4>What would you like to do now?</h4>
  <p>
    1) <a href="'. SECURE_URL . SEARCH_DOCTOR .'">Continue</a> with another medical certificate<br />
    2) <a href="'. SECURE_URL . LOGOUT .'">Log out</a>.
</p>
<p>&nbsp;</p>';

$s_content .= getFooter();

echo $s_content;
