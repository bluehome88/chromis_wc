<?php
require_once('./config.php');
define( '_VALID_MOS', 1 );
require_once('secure.php');
require_once('include/functions.php');
require_once( ROOT_FOLDER . 'lib/User.php');
require_once( ROOT_FOLDER . 'lib/MedicalCert.php');
require_once( ROOT_FOLDER . 'lib/Patient.php');

if( isset($b_user_area) &&  $b_user_area == 1 ){
    if( !isAuthenticated('ADMIN','DOCTOR','USER')) {
        header('Location: '. SECURE_URL . LOGIN_FORM );
        exit;
    }
}elseif( isset($b_admin_area) &&  $b_admin_area == 1 ){
    if( !isAuthenticated('ADMIN')) {
        header('Location: '. SECURE_URL . LOGIN_FORM );
        exit;
    }
}else{
    if( !isAuthenticated('ADMIN','DOCTOR')) {
        header('Location: '. SECURE_URL . LOGIN_FORM );
        exit;
    } else if( isDoctor() && !isLocationSet()) {
        if( $_SERVER['SCRIPT_NAME'] != '/wc-dev' . DOC_LOCATION ){
            header('Location: '. SECURE_URL . DOC_LOCATION );
            exit;
        }
    }
}
$js = '';
$s_menu = include('include/menu.php');
