<?php
    require_once('../config.php');

    // if( $_SERVER['HTTPS'] != "on"
    //     && TESTING == false
    // ) {
    //     header( 'Location: '. SECURE_URL . LOGIN_FORM );
    //     exit;
    // }

    function processDate( $v ){
        $o_date = null;
        $o_date = new DateTime( $v );
        return $o_date->format('d/m/Y');
    }

    function arrayLoader( $a ){
        $a_r = array();
        foreach( $a as $v ){
            if( trim($v) != '' ){
                $a_r[] = $v;
            }
        }
        return $a_r;
    }

    function getHeader( $s_heading, $s_menue = '', $js = '', $html5 = false, $s_meta = '' ){
        if( $html5 === false ){
            $r ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    '. $s_meta .'
    <title>CHROMIS Workcover NSW Medical Certificate: '. $s_heading .'</title>
    <link href="chromisWCstyle.css" rel="stylesheet" type="text/css" />
    <link href="chromisGED.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="scripts/dates.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/search.js"></script>
    '. $js .'
</head>

<body>
    <div id="line"></div>
    <div id="page-wrap">
        <div id="inside">
            <div id="container">
                <div id="header">
                    <div id="logo">
                        <img src="images/logo.png" width="277" height="126" alt="Chromis Medical Health Services" />
                    </div>
                    '. $s_menue .'
                </div>
                <div id="content">
                    <h4>workcover nsw</h4>
                    <h1>'. $s_heading .'</h1>
                    <p>&nbsp;</p>';
        } else {

            $r ='<!DOCTYPE HTML>
<html>
<head>
    <title>CHROMIS Workcover NSW Medical Certificate: '. $s_heading .'</title>
    <link href="./scripts/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="./scripts/html5.js"></script>
    <![endif]-->
    <script src="./scripts/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
    <script src="./scripts/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.js"></script>
    '. $js .'
    <script src="./scripts/bootstrap/js/bootstrap.min.js"></script>
    <link href="chromisWCstyle.css" rel="stylesheet" type="text/css" />
    <link href="chromisGED.css" rel="stylesheet" type="text/css" />
    <link href="./scripts/jquery-ui-1.10.0/css/ui-lightness/jquery-ui-1.10.0.custom.css" rel="stylesheet">
</head>

<body>
    <div id="line"></div>
    <div id="page-wrap">
        <div id="inside">
            <div id="container">
                <div id="header">
                    <div id="logo">
                        <img src="images/logo.png" width="277" height="126" alt="Chromis Medical Health Services" />
                    </div>
                    '. $s_menue .'
                </div>
                <div id="content">
                    <h4>workcover nsw</h4>
                    <h1>'. $s_heading .'</h1>';
        }
        return $r;
    }

    function getFooter(){
        $r = '
                    <p><img src="images/fish.png" width="104" height="20" alt="CHROMIS medical health services" /></p>
                </div>
                <div id="footer">
                    <div class="copyright">&copy; CHROMIS</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
        return $r;
    }

    session_start();
    include ("include/dbconnect.php");
    $whereto = null;
    $user = array();
    if( isset($_SESSION['User']) ){
        $user = $_SESSION['User'];
    }

    //  If this is a posted login form, authenticate the user using username, password.
    //  User record must be active to be successful
    if( isset($_POST['authprocess'])
        && $_POST['authprocess'] == "login"
    ) {
        $user = array();
        session_unset();
        $username=$_POST['uname'];
        $password=$_POST['pword'];
        $link = getDBConnection();
        $stmt = $link->prepare("SELECT UserId, Username, FirstName, LastName, UserType FROM `users` WHERE `Username`=? and `Password`=PASSWORD(?) and `Active`='Y'");
        $stmt->bind_param('ss', $username, $password );
        $stmt->execute();
        $stmt->bind_result($user['UserId'], $user['Username'], $user['Firstname'], $user['Lastname'], $user['UserType']);
        $stmt->fetch();
        $stmt->close();
        $link->close();
        $_SESSION['User'] = $user;

        //  Redirect to the start page for this user type
        $whereto = "";
        if( $user['UserType'] == 'DOCTOR' ){
            $whereto = SECURE_URL . DOC_LOCATION;
        } else {
            $whereto = getStartPage( $user );
        }

        if( $user['UserId'] == 0 ) {
            $_REQUEST['errorMsg'] = "Invalid username or password";
            $whereto = SECURE_URL . LOGIN_FORM;
        }

        header('Location: '. $whereto);
        exit;
    }

    if(isset($_POST['authprocess']) && $_POST['authprocess'] == "location") {
        if( isset($_POST['LocationId'])) {
            $locationId = $_POST['LocationId'];
            $user['LocationId'] = $locationId;
            $link = getDBConnection();
            $stmt = $link->prepare(
                "SELECT
                L.LocationName,
                L.LocationAddress,
                L.LocationSuburb,
                L.LocationState,
                L.LocationPostcode,
                L.LocationPhone,
                L.LocationFax,
                M.MPN
                FROM `medicare_details` M
                inner join `locations` L on ( L.LocationId = M.LocationId )
                WHERE M.UserId=? and M.LocationId=?"
            );
            $stmt->bind_param("ii", $user['UserId'], $locationId );
            $stmt->execute();
            $stmt->bind_result(
                $user['LocationName'],
                $user['LocationAddress'],
                $user['LocationSuburb'],
                $user['LocationState'],
                $user['LocationPostcode'],
                $user['LocationPhone'],
                $user['LocationFax'],
                $user['MPN']
            );
            $stmt->fetch();
            $stmt->close();
            $link->close();
            $_SESSION['User'] = $user;
            $whereto = getStartPage( $user );
        } else {
            $whereto = SECURE_URL . LOGIN_FORM;
        }

        header('Location: '.$whereto);
        exit();
    }

    //  Check the logged in user's type to see if they match the required types
    function isAuthenticated() {
        $numargs = func_num_args(); // Variable arguments
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            if( $_SESSION['User']['UserType'] == $arg_list[$i] ) return true;
        }
        return false;
    }

    function isDoctor() {
        return( isset( $_SESSION['User']) && $_SESSION['User']['UserType'] == "DOCTOR" );
    }

    function isAdmin() {
        return( isset( $_SESSION['User']) && $_SESSION['User']['UserType'] == "ADMIN" );
    }

    function isLocationSet() {
        if( $_SESSION['User']['UserType'] == "DOCTOR" ) {
            return isset($_SESSION['User']['LocationId']);  // Dr's must specify a location
        } else {
            return true;   //  Admins don't need a location
        }
    }

    //  Determine the start page based on the user type
    function getStartPage( $user ) {
        $s_return = SECURE_URL . LOGIN_FORM;
        switch( $user['UserType'] ){
            case 'USER':
                $s_return = SECURE_URL . SEARCH_USER;
                break;

            case 'DOCTOR':
                $s_return = SECURE_URL . SEARCH_DOCTOR;
                break;

            case 'ADMIN':
                $s_return = SECURE_URL . SEARCH_ADMIN;
                break;
        }
        return $s_return;
    }
