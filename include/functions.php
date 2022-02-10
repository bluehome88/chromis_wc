<?php
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
$path = dirname( __FILE__ );
$slash = '/';
(stristr( $path, $slash )) ? '' : $slash = '\\';
date_default_timezone_set('Australia/Sydney');

// returns standard date time to keep consistant throughout system
function GetDateTime() {
    return date("y-m-d H:i:s");
}

//
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ?
    mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

function GetFieldFromTable($table,$keyfield,$valuefield,$indexid) {
    $q1="select $valuefield from $table where $keyfield='".$indexid."';";
    $r1=mysql_query($q1) or exit(mysql_error().": $q1");
    if($rec1 = mysql_fetch_assoc($r1)){
        return $rec1[$valuefield];
    }
    return -1;
}

function DisplayOptionSelect($num,$option,$sel) {
    echo "<option value=$num";
    if($sel==$num) echo " selected ";
    echo ">".$option."</option>\n";
}

// fieldtype is
//  "S" for string,
//  "P" for password,
//  "N" for number,
//  "D" for date
//  "PH" for Phone Number as string
//  "PC" for Postcode as string
//  "EM" for Emails as string
function DoTextBox ( $fieldtype, $title, $name, $value, $type="text", $mandatory=FALSE)
{

        if ( $type<>"hidden" )
        {
            echo "<td>".$title;
        }
        if ( $mandatory==TRUE )
        {
            echo "(*)";
        }
        echo "</td>\n<td>";
        echo "<input type='".$type."' name='".$name."' size='25' value='";
        if ($fieldtype=="D")
        {
            echo substr($value, 0, 10);
        }
        else
        {
            echo $value;
        }
        echo "' ";
        //
        switch ($fieldtype)
        {
            case "N":
                $s .= " onBlur='CheckNumber(this)' ";
                break;
            case "S":
                $s .= " onBlur='CheckString(this)' ";
                break;
            case "P":
                $s .= " onBlur='CheckPassword(this)' ";
                break;
            case "PC":
                $s .= " onBlur='CheckPostcode(this)' ";
                break;
            case "PH":
                $s .= " onBlur='CheckPhone(this)' ";
                break;
            case "EM":
                $s .= " onBlur='CheckEmail(this)' ";
                break;
            case "D":
                $s .= " onBlur='CheckDate(this)' ";
                break;
            default:
        }
        //
        echo ">\n";
        if ( $type<>"hidden" )
        {
            echo "</td>\n";
        }

}


function DoYesNoOptions2($name,$value) {
    $e="<select size='1' name='".$name."'>\n";
    $e.="<option value='1'";
    if($value=="1") $e.=" selected ";
    $e.=">Yes</option>\n";
    $e.="<option value='0'";
    if($value=="0")$e.=" selected ";
    $e.= ">No</option>\n";
    $e.= "</select>\n";
    return $e;
}

function MsgBox($msg) {
    echo "<script>alert('".$msg."');</script>";
}


// render a formated data table with $data
// $data is array [title, data],[title,data]
// if cols>1 then the are added ever n times
function RenderTable($cols,$data) {
    $t  = "";
    if ( isset($data) && ($data <> null) )
    {
        $t .= "<table cellspacing=\"3\" cellpadding=\"3\" class=\"section\">\n";
        $t .= "<tr class=\"fieldrow\">\n";
        $v = 1;
        foreach ( $data as $a )
        {
            $name  = $a[0];
            $value = $a[1];

            $t .= "<td class=\"fieldname\">";
            $t .= $name;
            $t .= "</td>\n";

            $t .= "<td class=\"fielddata\">";
            $t .= $value;
            $t .= "</td>";

            if ( $v>=$cols )
            {
                $v = 1;
                $t .= "</tr>\n";
                $t .= "<tr>";
            }
            else
            {
                $v++;
            }
        }
        $t .= "</tr>\n";
        $t .= "</table>\n";
    }

    return $t;
}


// render a formated data table with $data
// $data is 2d array with each row containg an array for cell data [c1,c2,c3,c4,..],[c1,c2,c3,c4,..]
// if cols>1 then the are added ever n times
function RenderTableList($cols,$headings,$data,$class="section",$more="") {
    $t = "";
    $t .= "<table  class=\"$class\" $more >";
    $t .=  "<tr  class='section_subheading'>";
    for($n=0;$n<$cols;$n++) {
        if(isset($headings[$n])) $a=$headings[$n]; else $a="";
        $t .=  "<td class='section_subheading'>$a</td>";
    }
    $t .=  "</tr>";
    $v=1;
    if ( isset($data) && ($data != null) )
    {
        foreach($data as $row) {
            if ( $v == 1 )
            {
                $v = 2;
            }
            else
            {
                $v = 1;
            }
            $t .=  "<tr  class='section_field_$v'>";
            for ( $n = 0; $n < $cols; $n++ )
            {
                // START: JDH20071010: Define variables before using them.
                //$a = $row[$n];
                $a = "";
                if ( isset($row[$n]) )
                {
                    $a = $row[$n];
                }
                // END:   JDH20071010: Define variables before using them.

                $t .=  "<td class=\"section_field_". $v ."\">". $a ."</td>";
            }
            $t .=  "</tr>";
        }
    }

    $t .=  "</table>";
    return $t;
}



// render a formated data table with $data
// $data is 2d array with each row containg an array for cell data [c1,c2,c3,c4,..],[c1,c2,c3,c4,..]
// if cols>1 then the are added ever n times
function RenderTableList_fixedheader($cols,$headings,$data) {
    $widths=array(5,70,150,70,70,70,85,50,50,50);
    $t = "";
    $t .= "<table  id=\"budget_table_header\">";
    $t .=  "<tr  class=\"section_subheading\">";
    for($n=0;$n<$cols;$n++) {
        if(isset($headings[$n])) $a=$headings[$n]; else $a="";
        $t .=  "<td class=\"section_subheading\" width=\"".$widths[$n]."px\" style=\" max-width:".$widths[$n]."px;\">$a</td>\n";
    }
    $t .=  "</tr></table>\n\n";
    $t .= "<div id=\"budget_table_scroll\"><table  id=\"budget_table_body\">\n";
    $v=1;
    if ( isset($data) && ($data != null) )
    {
        foreach($data as $row) {
            if ( $v == 1 )
            {
                $v = 2;
            }
            else
            {
                $v = 1;
            }
            $t .=  "<tr  class='section_field_$v'>";
            for ( $n = 0; $n < $cols; $n++ )
            {
                // START: JDH20071010: Define variables before using them.
                //$a = $row[$n];
                $a = "";
                if ( isset($row[$n]) )
                {
                    $a = $row[$n];
                }
                // END:   JDH20071010: Define variables before using them.

                $t .=  "<td class=\"section_field_". $v ."\" width=\"".$widths[$n]."px\" style=\"max-width:".$widths[$n]."px; overflow:hiden;\">". $a ."</td>";
            }
            $t .=  "</tr>\n";
        }
    }

    $t .=  "</table></div>\n";
    return $t;
}


// delete a series of records from the database
// delete is an array of key ids to be deleted
function DeleteRecords($tablename,$keyfield,$delete) {
    if(is_array($delete)) {
        foreach($delete as $id) {
            if(CheckNum($id)!=0) { // make sure we are inserting a number and not injecting unwanted coad
                $q1="DELETE FROM `$tablename` WHERE `$keyfield` = $id LIMIT 1;";
                $r1=mysql_query($q1) or exit(mysql_error().": $q1");
            }
        }
    }
}


// show a List Menu field populated with data from a table
function ShowListFieldFromTable($fieldname,$value,$childtable,$keyfield,$valuefield,$where) {
    // START: JDH20071010: Define variables prior to use.
    $s  = "";
    // END:   JDH20071010: Define variables prior to use.
    $q1 = "select $keyfield,$valuefield from $childtable $where;";
    $r1 = mysql_query($q1) or exit(mysql_error().": $q1");
    if ( mysql_num_rows($r1) > 0 )
    {
        $s .= "<select name=\"". $fieldname ."\" >";
        while ( $rec1 = mysql_fetch_assoc($r1) )
        {
            if ( $rec1[$keyfield] == $value )
            {
                $opt = " selected=\"selected\"";
            }
            else
            {
                $opt = "";
            }
            $s .= "<option value=\"". $rec1[$keyfield] ."\" $opt>". $rec1[$valuefield] ."</option>\n";
        }
        $s .= "</select>";
    }
    else
    {
        $s .= "No records found $q<br>";
    }

    return $s;
}

// show a List Menu field populated with static data from array in listvalues
function ShowStaticListField($currentvalue,$listvalues,$fieldname) {
    // START: JDH20071010: Define variables prior to use.
    $s  = "";
    // END:   JDH20071010: Define variables prior to use.
    $s .= "<select name=\"". $fieldname ."\" >";
    foreach ( $listvalues as $name=>$value )
    {
        if ( $currentvalue == $value )
        {
            $opt = " selected=\"selected\"";
        }
        else
        {
            $opt = "";
        }
        $s .= "<option value=\"". $value ."\" $opt>". $name ."</option>\n";
    }
    $s .= "</select>";
    return $s;
}

function ShowMultiStaticMenuField($currentvalues,$listvalues,$fieldname) {
    // START: JDH20071010: Define variables prior to use.
    $s  = "";
    $currentitems = explode("\n",$currentvalue);
    // END:   JDH20071010: Define variables prior to use.
    $s .= "<select name=\"". $fieldname ."[]\" multiple  size='10'>";
    foreach ( $listvalues as $name=>$value )
    {
        $opt = "";
        if(is_array($currentvalues))
        if ( in_array($value,$currentvalues) )
        {
            $opt = " selected=\"selected\"";
        }
        $s .= "<option value=\"". $value ."\" $opt>". $name ."</option>\n";
    }
    $s .= "</select>";
    return $s;
}


// fieldtype is
//  "S" for string,
//  "P" for password,
//  "N" for number,
//  "D" for date
//  "PH" for Phone Number as string
//  "PC" for Postcode as string
//  "EM" for Emails as string
function DoTextBox2($fieldtype, $title, $name, $value, $type="text", $mandatory=FALSE)
{
    if($type!="textarea") {
        $s = "<input type='". $type ."' name='". $name ."' size='25' value='";
        //
        if ( $fieldtype=="D" )
        {
            $s .= substr($value, 0, 10);
        }
        else
        {
            $s .= $value;
        }
        //
        $s .= "' ";
        //
        switch ($fieldtype)
        {
            case "N":
                $s .= " onBlur='CheckNumber(this)' ";
                break;
            case "S":
                $s .= " onBlur='CheckString(this)' ";
                break;
            case "P":
                $s .= " onBlur='CheckPassword(this)' ";
                break;
            case "PC":
                $s .= " onBlur='CheckPostcode(this)' ";
                break;
            case "PH":
                $s .= " onBlur='CheckPhone(this)' ";
                break;
            case "EM":
                $s .= " onBlur='CheckEmail(this)' ";
                break;
            case "D":
                $s .= " onBlur='CheckDate(this)' ";
                break;
            default:
        }
        //
        $s .= ">";
        //
        if ( $mandatory==TRUE )
        {
            $s .= "(*)";
        }
    } else {
        $s= "<textarea cols='60' name='$name' rows='6'>$value</textarea>";
        if ( $mandatory==TRUE )
        {
            $s .= "(*)";
        }
    }
    //
    return $s;
}


// load a series of fields from a table in an associative array
// created by P.Barby
// on the 15th April 2007
// changed
function loadFieldsToArray($txtTableame) {
    $result = mysql_query("SHOW COLUMNS FROM $txtTableame") or exit(mysql_error().": $q2");

    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $fields[$row['Field']]=$row;
        }
        return $fields;
    } else {
        return -1;
    }

}

function loadRecordsToArray($sql,$fieldtouse) {
    $result = mysql_query($sql) or exit(mysql_error().": $q2");

    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $fields[$row[$fieldtouse]]=$row[$fieldtouse];
        }
        return $fields;
    } else {
        return -1;
    }

}

// created by P.Barby
// on the 16th April 2007
// changed

// checks a mysql field type and returns
// 0: String
// 1: Numeric
function checkfieldtype($fieldtype) {
    $mysqlnumtype = array("TINYINT", "SMALLINT", "MEDIUMINT", "INT", "INTEGER", "BIGINT","FLOAT", "DOUBLE", "DECIMAL", "NUMERIC");
    $result=0;
    $fieldtype=strtoupper($fieldtype);
    foreach($mysqlnumtype as $ftypes) {
        if(strstr($fieldtype,$ftypes)) {
            $result=1;
        }
    }
    return $result;
}

function debug_in($loc,$desc) {
    // based on user record in table user, returns field conid (prev link)
    $loc=addslashes($loc);
    $desc=addslashes($desc);

    $q1="insert into debug (loc,`desc`) values ('".$loc."','".$desc."');";
    $r1=mysql_query($q1) or exit(mysql_error().": $q1");
}

function sb_mkdir($newpath,$mod) {
    $dirPath = BASE_DIR . $newpath;
    $rs = @mkdir( $dirPath, $mod );
}

    // Function to validate against any email injection attempts
    function IsInjected($str)
    {
      $injections = array('(\n+)',
                  '(\r+)',
                  '(\t+)',
                  '(%0A+)',
                  '(%0D+)',
                  '(%08+)',
                  '(%09+)'
                  );
      $inject = join('|', $injections);
      $inject = "/$inject/i";
      if(preg_match($inject,$str))
        {
        return true;
      }
      else
        {
        return false;
      }
    }


 function month_to_name2($in) {
   $marray['01']='Jan';
   $marray['02']='Feb';
   $marray['03']='Mar';
   $marray['04']='Apr';
   $marray['05']='May';
   $marray['06']='Jun';
   $marray['07']='Jul';
   $marray['08']='Aug';
   $marray['09']='Sep';
   $marray['10']='Oct';
   $marray['11']='Nov';
   $marray['12']='Dec';
    return $marray[$in];
    }

    function formatDateForPrint( $inputDate )
    {
        $info = array();
        $inputDate = str_replace ( ".", "-", $inputDate );
        $inputDate = str_replace ( "/", "-", $inputDate );
        if(($convertedDate = strtotime($inputDate)) == false ) $info = array('','','');
        else $info = array( date("Y",$convertedDate), date("m",$convertedDate), date("d",$convertedDate));
        return $info;
    }

    function convertToDate( $inputDate )
    {
        $inputDate = str_replace ( ".", "-", $inputDate );
        $inputDate = str_replace ( "/", "-", $inputDate );
        if(($convertedDate = strtotime($inputDate)) == false ) return date("d/m/Y");
        else return $convertedDate;
    }

function re_format_date($in) {
    $year=substr($in,6,4);
    $month=substr($in,3,2);
    $day=substr($in,0,2);
    return "$year-$month-$day";
}

function process_date($in) {
    if($in!="0000-00-00"){
        $year=substr($in,0,4);
        $month=substr($in,5,2);
        $day=substr($in,8,2);
        return "$year.$month.$day";
    } else {
        return "";
    }
}


function month_to_number($in) {
   $marray['Jan']='01';
   $marray['Feb']='02';
   $marray['Mar']='03';
   $marray['Apr']='04';
   $marray['May']='05';
   $marray['Jun']='06';
   $marray['Jul']='07';
   $marray['Aug']='08';
   $marray['Sep']='09';
   $marray['Oct']='10';
   $marray['Nov']='11';
   $marray['Dec']='12';
    return $marray[$in];

}
