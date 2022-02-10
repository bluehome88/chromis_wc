<?php
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class user {

  var $id; // key field is id;
  var $username;
  var $password;
  var $firstname;
  var $lastname;
  var $usertype;
  var $active;

  function __construct()
  {
    $this->id = 0;
    $this->username = "";
    $this->firstname = "";
    $this->lastname = "";
    $this->usertype = "USER";
    $this->active = "Y";
  }

 // Loads data into  object
      function Load() {
            $this->username = "";
            $this->firstname = "";
            $this->lastname = "";
            $this->usertype = "USER";
            $this->active = "Y";
            $db = getDBConnection();
            $stmt = $db->prepare("SELECT UserId, Username, Firstname, Lastname, UserType, Active FROM users WHERE UserId = ?");
            $stmt->bind_param( "i",$this->id );
            $stmt->execute() or die($stmt->error);
            $stmt->store_result();
            $stmt->bind_result(
                $this->id,
                $this->username,
                $this->firstname,
                $this->lastname,
                $this->usertype,
                $this->active
             );
            $stmt->fetch();
            $stmt->close();
            $db->close();
      }

    // save values stored in the object in to the doctor table
    function Save()
    {
        $db = getDBConnection();
        if( !isset($this->id) || $this->id == 0)
        {
            $stmt = $db->prepare("INSERT users( Username, Password, Firstname, Lastname, UserType, Active ) VALUES( ?,PASSWORD(?),?,?,?,? )");
            $stmt->bind_param( "ssssss",$this->username,$this->password,$this->firstname,$this->lastname,$this->usertype,$this->active );
            $stmt->execute() or die($stmt->error);
            $this->id = $db->insert_id;
            $stmt->close();
        }
        else
        {
            if( isset($this->password) && $this->password != "" )   // check if password is being updated too
            {
                $stmt = $db->prepare("UPDATE users SET Username=?, Password=PASSWORD(?), Firstname=?, Lastname=?, UserType=?, Active=? WHERE Userid=?");
                $stmt->bind_param( "ssssssi",$this->username,$this->password,$this->firstname,$this->lastname,$this->usertype,$this->active,$this->id);
                $stmt->execute() or die($stmt->error);
                $stmt->close();
            }
            else
            {
                $stmt = $db->prepare("UPDATE users SET Username=?, Firstname=?, Lastname=?, UserType=?, Active=? WHERE Userid=?");
                $stmt->bind_param( "sssssi",$this->username,$this->firstname,$this->lastname,$this->usertype,$this->active,$this->id);
                $stmt->execute() or die($stmt->error);
                $stmt->close();
            }
        }
        $db->close();

        return $this->id;
    }
}



class medical_cert {

  var $id; // key field is id;
  var $paitentid;
  var $diagnosis;
  var $work_is_factor;
  var $manag_plan;
  var $treat_rev_date;
  var $fit_for_work_status;
  var $unfitfrom;
  var $unfitto;
  var $suitfrom;
  var $suitto;
  var $modfrom;
  var $assreq;
  var $exam_date;
  var $has_cap_for_duration;
  var $has_cap_for_duration_days;
  var $has_cap_for_liftingupto;
  var $has_cap_for_walkingupto;
  var $has_cap_for_sittingupto;
  var $has_cap_for_standingupto;
  var $has_cap_for_travellingupto;
  var $has_cap_for_keyingupto;
  var $other_restrictions;
  var $other_restrictions_details;
  var $other_restrictions_other;
  var $fitness_review_date;

  function __construct()
  {
        // clear data

        $this->id = 0;
        $this->paitentid = 0;
        $this->diagnosis = "";
        $this->work_is_factor = "Unknown";
        $this->manag_plan = "";
        $this->treat_rev_date = date("d/m/Y");
        $this->fit_for_work_status = "";
        $this->unfitfrom = "0000-00-00";
        $this->unfitto = "0000-00-00";
        $this->suitfrom = "0000-00-00";
        $this->suitto = "0000-00-00";
        $this->modfrom = "0000-00-00";
        $this->assreq = "";
        $this->exam_date = date("d/m/Y");
        $this->has_cap_for_duration = "";
        $this->has_cap_for_duration_days = "";
        $this->has_cap_for_liftingupto = "";
        $this->has_cap_for_walkingupto = "";
        $this->has_cap_for_sittingupto = "";
        $this->has_cap_for_standingupto = "";
        $this->has_cap_for_travellingupto = "";
        $this->has_cap_for_keyingupto = "";
        $this->other_restrictions = "";
        $this->other_restrictions_details = "";
        $this->other_restrictions_other = "";
        $this->fitness_review_date = date("d/m/Y");
  }

 // Loads data into  object
      function Load() {

            $db = getDBConnection();
            $stmt = $db->prepare("select * from medical_cert where id=?");
            $stmt->bind_param( "i",$this->id );
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result(
                $this->id,
                $this->paitentid,
                $this->diagnosis ,
                $this->work_is_factor ,
                $this->manag_plan ,
                $this->treat_rev_date ,
                $this->fit_for_work_status ,
                $this->unfitfrom ,
                $this->unfitto ,
                $this->suitfrom ,
                $this->suitto ,
                $this->modfrom ,
                $this->assreq ,
                $this->exam_date ,
                $this->has_cap_for_duration ,
                $this->has_cap_for_duration_days ,
                $this->has_cap_for_liftingupto ,
                $this->has_cap_for_walkingupto ,
                $this->has_cap_for_sittingupto ,
                $this->has_cap_for_standingupto ,
                $this->has_cap_for_travellingupto ,
                $this->has_cap_for_keyingupto ,
                $this->other_restrictions ,
                $this->other_restrictions_details ,
                $this->other_restrictions_other ,
                $this->fitness_review_date
             );
            $stmt->execute() or die($stmt->error);
            $stmt->fetch();
            $stmt->close();
            $db->close();

      }

    // save values stored in the object in to the medical_cert table

    function Save()
    {
        $this->ResetEmptyDates();
        $newrecord=0;
        if(ISSET($this->id) && $this->id > 0)
        {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "update medical_cert" .
                    "set diagnosis=?".
                    ",work_is_factor=?".
                    ",manag_plan=?".
                    ",treat_rev_date=?".
                    ",fit_for_work_status=?".
                    ",unfitfrom=?".
                    ",unfitto=?".
                    ",suitfrom=?".
                    ",suitto=?".
                    ",modfrom=?".
                    ",assreq=?".
                    ",exam_date=?".
                    ",has_cap_for_duration=?".
                    ",has_cap_for_duration_days=?".
                    ",has_cap_for_liftingupto=?".
                    ",has_cap_for_walkingupto=?".
                    ",has_cap_for_sittingupto=?".
                    ",has_cap_for_standingupto=?".
                    ",has_cap_for_travellingupto=?".
                    ",has_cap_for_keyingupto=?".
                    ",other_restrictions=?".
                    ",other_restrictions_details=?".
                    ",other_restrictions_other=?".
                    ",fitness_review_date=?".
                " where id=?");
            $stmt->bind_param( "ssssssssssssssssssssssssi",
                $this->diagnosis,
                $this->work_is_factor ,
                $this->manag_plan ,
                $this->treat_rev_date ,
                $this->fit_for_work_status ,
                $this->unfitfrom ,
                $this->unfitto ,
                $this->suitfrom ,
                $this->suitto ,
                $this->modfrom ,
                $this->assreq ,
                $this->exam_date ,
                $this->has_cap_for_duration ,
                $this->has_cap_for_duration_days ,
                $this->has_cap_for_liftingupto ,
                $this->has_cap_for_walkingupto ,
                $this->has_cap_for_sittingupto ,
                $this->has_cap_for_standingupto ,
                $this->has_cap_for_travellingupto ,
                $this->has_cap_for_keyingupto ,
                $this->other_restrictions ,
                $this->other_restrictions_details ,
                $this->other_restrictions_other ,
                $this->fitness_review_date ,
                $this->id );
            $stmt->execute() or die($stmt->error);
            $stmt->close();
            $db->close();
        }
        else
        {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "insert medical_cert(paitentid,diagnosis,work_is_factor,manag_plan,treat_rev_date,fit_for_work_status,unfitfrom,unfitto,suitfrom,suitto,modfrom,assreq,exam_date,has_cap_for_duration,has_cap_for_duration_days,has_cap_for_liftingupto,has_cap_for_walkingupto,has_cap_for_sittingupto,has_cap_for_standingupto,has_cap_for_travellingupto,has_cap_for_keyingupto,other_restrictions,other_restrictions_details,other_restrictions_other,fitness_review_date)" .
                "values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param( "issssssssssssssssssssssss",
                $this->paitentid,
                $this->diagnosis,
                $this->work_is_factor ,
                $this->manag_plan ,
                $this->treat_rev_date ,
                $this->fit_for_work_status ,
                $this->unfitfrom ,
                $this->unfitto ,
                $this->suitfrom ,
                $this->suitto ,
                $this->modfrom ,
                $this->assreq ,
                $this->exam_date ,
                $this->has_cap_for_duration ,
                $this->has_cap_for_duration_days ,
                $this->has_cap_for_liftingupto ,
                $this->has_cap_for_walkingupto ,
                $this->has_cap_for_sittingupto ,
                $this->has_cap_for_standingupto ,
                $this->has_cap_for_travellingupto ,
                $this->has_cap_for_keyingupto ,
                $this->other_restrictions ,
                $this->other_restrictions_details ,
                $this->other_restrictions_other ,
                $this->fitness_review_date );
            $stmt->execute() or die($stmt->error);
            $this->id = $db->insert_id;
            $stmt->close();
            $db->close();
        }
        return $this->id;
    }

    // Existing tables do not allow NULL values so we have to reset any empty dates to "day-zero"
    function ResetEmptyDates()
    {
        if((strtotime($this->treat_rev_date)) === false) $this->treat_rev_date = "0000-00-00";
        if((strtotime($this->unfitfrom)) === false) $this->unfitfrom = "0000-00-00";
        if((strtotime($this->unfitto)) === false) $this->unfitto = "0000-00-00";
        if((strtotime($this->suitfrom)) === false) $this->suitfrom = "0000-00-00";
        if((strtotime($this->suitto)) === false) $this->suitto = "0000-00-00";
        if((strtotime($this->modfrom)) === false) $this->modfrom = "0000-00-00";
        if((strtotime($this->exam_date)) === false) $this->exam_date = "0000-00-00";
        if((strtotime($this->fitness_review_date)) === false) $this->fitness_review_date = "0000-00-00";
    }
/*
    function DefaultEmptyDates( $toDate )
    {
        if((strtotime($this->treat_rev_date)) === false || $this->treat_rev_date == "0000-00-00") $this->treat_rev_date = date("Y.m.d");
        if((strtotime($this->unfitfrom)) === false || $this->unfitfrom == "0000-00-00") $this->unfitfrom = date("Y.m.d");
        if((strtotime($this->unfitto)) === false || $this->unfitto == "0000-00-00") $this->unfitto = date("Y.m.d");
        if((strtotime($this->suitfrom)) === false || $this->suitfrom == "0000-00-00") $this->suitfrom = date("Y.m.d");
        if((strtotime($this->suitto)) === false || $this->suitto == "0000-00-00") $this->suitto = date("Y.m.d");
        if((strtotime($this->modfrom)) === false || $this->modfrom == "0000-00-00") $this->modfrom = date("Y.m.d");
        if((strtotime($this->exam_date)) === false || $this->exam_date == "0000-00-00") $this->exam_date = date("Y.m.d");
        if((strtotime($this->fitness_review_date)) === false || $this->fitness_review_date == "0000-00-00") $this->fitness_review_date = date("Y.m.d");
    }
*/
}


class patient {

  var $id; // key field is id;
  var $examdate;
  var $cons_status;
  var $claimno;
  var $surname;
  var $othernames;
  var $address;
  var $suburb;
  var $state;
  var $postcode;
  var $phone;
  var $mobile;
  var $dob;
  var $occupation;
  var $hours_week;
  var $emp_name;
  var $emp_address;
  var $emp_suburb;
  var $emp_state;
  var $emp_postcode;
  var $injury_desc;
  var $injury_dateof;
  var $doctor_name;
  var $doctor_location;
  var $doctor_agrees;

    function __construct()
    {
                // clear data
                $this->id = 0;
                $this->examdate = date("d/m/Y");
                $this->cons_status = "Initial";
                $this->claimno = "";
                $this->surname = "";
                $this->othernames = "";
                $this->address = "";
                $this->suburb = "";
                $this->state = "";
                $this->postcode = "";
                $this->phone = "";
                $this->mobile = "";
                $this->dob = "0000-00-00";
                $this->occupation = "";
                $this->hours_week = "";
                $this->emp_name = "";
                $this->emp_address = "";
                $this->emp_suburb = "";
                $this->emp_state = "";
                $this->emp_postcode = "";
                $this->injury_desc = "";
                $this->injury_dateof = "0000-00-00";
                $this->doctor_name = "";
                $this->doctor_location = "";
                $this->doctor_agrees = "Yes";
    }

     // Loads data into  object
      function Load() {
            $db = getDBConnection();
            $stmt = $db->prepare("select * from paitent where id=?");
            $stmt->bind_param( "i",$this->id );
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result(
                $this->id,
                $this->examdate ,
                $this->cons_status ,
                $this->claimno ,
                $this->surname ,
                $this->othernames ,
                $this->address ,
                $this->suburb ,
                $this->state ,
                $this->postcode ,
                $this->phone ,
                $this->mobile ,
                $this->dob ,
                $this->occupation ,
                $this->hours_week ,
                $this->emp_name ,
                $this->emp_address ,
                $this->emp_suburb ,
                $this->emp_state ,
                $this->emp_postcode ,
                $this->injury_desc ,
                $this->injury_dateof ,
                $this->doctor_name ,
                $this->doctor_location ,
                $this->doctor_agrees
             );
            $stmt->execute() or die($stmt->error);
            $stmt->fetch();
            $stmt->close();
            $db->close();
      }


    // save values stored in the object in to the paitent table
    function Save() {
        if( ISSET($this->id) && $this->id != 0 )
        {
            $this->ResetEmptyDates();
            $db = getDBConnection();
            $stmt = $db->prepare(
            "update paitent " .
                "set examdate=?".
                ",cons_status=?".
                ",claimno=?".
                ",surname=?".
                ",othernames=?".
                ",address=?".
                ",suburb=?".
                ",state=?".
                ",postcode=?".
                ",phone=?".
                ",mobile=?".
                ",dob=?".
                ",occupation=?".
                ",hours_week=?".
                ",emp_name=?".
                ",emp_address=?".
                ",emp_suburb=?".
                ",emp_state=?".
                ",emp_postcode=?".
                ",injury_desc=?".
                ",injury_dateof=?".
                ",doctor_name=?".
                ",doctor_location=?".
                ",doctor_agrees=?".
                " where id=?" );
            $stmt->bind_param( "ssssssssssssssssssssssssi",
                $this->examdate ,
                $this->cons_status ,
                $this->claimno ,
                $this->surname ,
                $this->othernames ,
                $this->address ,
                $this->suburb ,
                $this->state ,
                $this->postcode ,
                $this->phone ,
                $this->mobile ,
                $this->dob ,
                $this->occupation ,
                $this->hours_week ,
                $this->emp_name ,
                $this->emp_address ,
                $this->emp_suburb ,
                $this->emp_state ,
                $this->emp_postcode ,
                $this->injury_desc ,
                $this->injury_dateof ,
                $this->doctor_name ,
                $this->doctor_location ,
                $this->doctor_agrees    ,
                $this->id );
            $stmt->execute() or die($stmt->error);
            $stmt->close();
            $db->close();
        }
        else
        {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "INSERT INTO paitent(examdate,cons_status,claimno,surname,othernames,address,suburb,state,postcode,phone,mobile,dob,occupation,hours_week,emp_name,emp_address,emp_suburb,emp_state,emp_postcode,injury_desc,injury_dateof,doctor_name,doctor_location,doctor_agrees)" .
                "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param( "ssssssssssssssssssssssss",
                $this->examdate,
                $this->cons_status,
                $this->claimno,
                $this->surname,
                $this->othernames,
                $this->address,
                $this->suburb,
                $this->state,
                $this->postcode,
                $this->phone,
                $this->mobile,
                $this->dob,
                $this->occupation,
                $this->hours_week,
                $this->emp_name,
                $this->emp_address,
                $this->emp_suburb,
                $this->emp_state,
                $this->emp_postcode,
                $this->injury_desc,
                $this->injury_dateof,
                $this->doctor_name,
                $this->doctor_location,
                $this->doctor_agrees );
            $stmt->execute() or die($stmt->error);
            $this->id = $db->insert_id;
            $stmt->close();
            $db->close();
        }
        return $this->id;
    }

    // Existing tables do not allow NULL values so we have to reset any empty dates to "day-zero"
    function ResetEmptyDates()
    {
        if((strtotime($this->examdate)) === false) $this->examdate = "0000-00-00";
        if((strtotime($this->dob)) === false) $this->dob = "0000-00-00";
        if((strtotime($this->injury_dateof)) === false) $this->injury_dateof = "0000-00-00";
    }
}
