<?php
// no direct access
//defined( '_VALID_MOS' ) or die( 'Restricted access' );

    function getDBConnection() {
        //Connect to Database Link
        $link = new mysqli('localhost',
            DB_USER,
            DB_PASS,
            DB_NAME
        ) or die('There was a problem connecting to the database.');

        if (!$link) exit("Error: Couldn't connect to MySQL server.");

        return $link;
    }


    //$mailto = "steve@taylorinitiatives.com.au,";
    //$subject = "Workcover NSW Medical Certificate" ;
    //$todayis = date("l, F j, Y, g:i a") ;
    //$formurl = "http://www.chromis.com.au/contact/WorkersComp_form.html" ;
    //$errorurl = "http://www.chromis.com.au/error.htm" ;
    //$thankyouurl = "http://www.chromis.com.au/contact/WCover_thanks.html" ;
    //$formurl = "WorkersComp_form.html" ;
    //$errorurl = "error.htm" ;
    //$thankyouurl = "WCover_thanks.html" ;
