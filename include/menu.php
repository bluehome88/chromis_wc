<?php
    $user = null;
    if ( isset($_SESSION['User'] )){
        $user = $_SESSION['User'];
    }
    $r = '<div id="nav"><ul>';
    if( isset( $user ) && $user != null ) {
        switch ( $user['UserType'] ){
            case 'ADMIN':
                $r .= '<li><a href="'. SECURE_URL . SEARCH_ADMIN .'">Search</a></li>
                    <li><a href="'. SECURE_URL . SEARCH_ADMIN_ALL .'">All Users</a></li>
                    <li><a href="'. SECURE_URL . ADMIN_LOCATIONS .'">Locations</a></li>
                    ';
                break;
            case 'DOCTOR':
                $r .= '<li><a href="'. SECURE_URL . SEARCH_DOCTOR .'">Search</a></li>
                    <li><a href="'. SECURE_URL . DOC_SET_PWD .'">Set Password</a></li>';
                break;
            default:
                $r .= '<li><a href="'. SECURE_URL . SEARCH_USER .'">Search</a></li>
                    <li><a href="'. SECURE_URL . DOC_SET_PWD .'">Set Password</a></li>';
                break;
        }
        $r .= '<li><a href="'. SECURE_URL . LOGOUT .'">LOGOUT</a></li><li><span class="nav_ur_here">Logged in as '. $user['Firstname'].' '.$user['Lastname'].'</span></li>';
    }
    $r .= '</ul></div>';
    return $r;
