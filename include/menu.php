<?php
    $user = null;
    if (isset($_SESSION['User'])) {
        $user = $_SESSION['User'];
    }
    $r = '<nav id="nav" class="navbar navbar-expand-md navbar-light">
            <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
            ';

    if (isset($user) && $user != null) {
        switch ($user['UserType']) {
            case 'ADMIN':
                $r .= '<li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . SEARCH_ADMIN . '">Search</a></li>
                        <li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . SEARCH_ADMIN_ALL . '">All Users</a></li>
                        <li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . ADMIN_LOCATIONS . '">Locations</a></li>
                        ';
                break;
            case 'DOCTOR':
                $r .= '<li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . SEARCH_DOCTOR . '">Search</a></li>
                        <li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . DOC_SET_PWD . '">Set Password</a></li>';
                break;
            default:
                $r .= '<li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . SEARCH_USER . '">Search</a></li>
                        <li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . DOC_SET_PWD . '">Set Password</a></li>';
                break;
        }
        $r .= '<li class="navbar-item"><a class="nav-link" href="' . SECURE_URL . LOGOUT . '">LOGOUT</a></li><li class="navbar-item"><a class="nav-link nav_ur_here" href="#">Logged in as ' . $user['Firstname'] . ' ' . $user['Lastname'] . '</a></li>';
    }

    $r .= '</ul></div></div></nav>';
    return $r;
