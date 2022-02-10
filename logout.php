<?php
define( '_VALID_MOS', 1 );
require_once('secure.php');

$_SESSION = array();                    // resets the session data for the rest of the runtime
if (isset($_COOKIES[session_name()]))   // sends as Set-Cookie to invalidate the session cookie
{
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
}
session_destroy();

header('Location: '. SECURE_URL . LOGIN_FORM );
exit;
