<?php
class Patient {
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
  var $date_first_seen;
  var $medicare_no;

    function __construct() {
        // clear data
        $this->id = 0;
        $this->examdate = date('Y-m-d');
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
        $this->date_first_seen = '0000-00-00';
        $this->medicare_no = '';
    }

     // Loads data into  object
    function Load() {
        $db = getDBConnection();
        $stmt = $db->prepare("select *
            FROM paitent where id=?");
        $stmt->bind_param( 'i', $this->id );
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
            $this->doctor_agrees ,
            $this->date_first_seen,
            $this->medicare_no
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
                ",date_first_seen=?".
                ",medicare_no=?".
                " where id=?" );
            $stmt->bind_param( "ssssssssssssssssssssssssssi",
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
                $this->doctor_agrees ,
                $this->date_first_seen ,
                $this->medicare_no ,
                $this->id );
            $stmt->execute() or die($stmt->error);
            $stmt->close();
            $db->close();
        }
        else
        {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "INSERT INTO paitent (
                    examdate,
                    cons_status,
                    claimno,
                    surname,
                    othernames,
                    address,
                    suburb,
                    state,
                    postcode,
                    phone,
                    mobile,
                    dob,
                    occupation,
                    hours_week,
                    emp_name,
                    emp_address,
                    emp_suburb,
                    emp_state,
                    emp_postcode,
                    injury_desc,
                    injury_dateof,
                    doctor_name,
                    doctor_location,
                    doctor_agrees,
                    date_first_seen,
                    medicare_no
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param( "ssssssssssssssssssssssssss",
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
                $this->doctor_agrees,
                $this->date_first_seen ,
                $this->medicare_no );
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
