<?php
class MedicalCert {
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

    var $pre_existing_factors;
    var $post_desc;
    var $est_return_to_work;
    var $fact_delay;
    var $comment;
    var $referral;

    /* [pre_existing_factors] //Detail any pre-existing factors which may be relevant to this condition
    [post_desc] => Yes // Do you require a copy of the position description/work duties?
    [est_return_to_work] => 3 weeks
    [fact_delay] =>  //factors dlaying return to work
    [comment] => test //comment
    referral */

    function __construct(){
        $this->id = 0;
        $this->paitentid = 0;
        $this->diagnosis = "";
        $this->work_is_factor = "Unknown";
        $this->manag_plan = "";
        $this->treat_rev_date = date('Y-m-d');
        $this->fit_for_work_status = "";
        $this->unfitfrom = NULL;
        $this->unfitto = NULL;
        $this->suitfrom = NULL;
        $this->suitto = NULL;
        $this->modfrom = NULL;
        $this->assreq = "No";
        $this->exam_date = date('Y-m-d');
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
        $this->fitness_review_date = NULL;
        $this->pre_existing_factors = '';
        $this->post_desc = '';
        $this->est_return_to_work = '';
        $this->fact_delay = '';
        $this->comment = '';
        $this->referral = '';
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
            $this->fitness_review_date ,
            $this->pre_existing_factors ,
            $this->post_desc ,
            $this->est_return_to_work ,
            $this->fact_delay ,
            $this->comment ,
            $this->referral
        );
        $stmt->execute() or die($stmt->error);
        $stmt->fetch();
        $stmt->close();
        $db->close();
        $a_replace = array("\r","\r\n","\n",'(',')');
        foreach( $this as $k => $v){
            $this->$k = str_replace( $a_replace, '', $v);
        }
    }

    // save values stored in the object in to the medical_cert table
    function Save() {
        $this->ResetEmptyDates();
        $newrecord=0;
        if(ISSET($this->id) && $this->id > 0) {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "UPDATE medical_cert
                SET diagnosis=?
                ,work_is_factor=?
                ,manag_plan=?
                ,treat_rev_date=?
                ,fit_for_work_status=?
                ,unfitfrom=?
                ,unfitto=?
                ,suitfrom=?
                ,suitto=?
                ,modfrom=?
                ,assreq=?
                ,exam_date=?
                ,has_cap_for_duration=?
                ,has_cap_for_duration_days=?
                ,has_cap_for_liftingupto=?
                ,has_cap_for_walkingupto=?
                ,has_cap_for_sittingupto=?
                ,has_cap_for_standingupto=?
                ,has_cap_for_travellingupto=?
                ,has_cap_for_keyingupto=?
                ,other_restrictions=?
                ,other_restrictions_details=?
                ,other_restrictions_other=?
                ,fitness_review_date=?
                ,pre_existing_factors=?
                ,post_desc=?
                ,est_return_to_work=?
                ,fact_delay=?
                ,comment=?
                ,referral=?
                 where id=?");
            $stmt->bind_param( "ssssssssssssssssssssssssissssss",
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
                $this->pre_existing_factors ,
                $this->post_desc ,
                $this->est_return_to_work ,
                $this->fact_delay ,
                $this->comment ,
                $this->referral ,
                $this->id );
            $stmt->execute() or die($stmt->error);
            $stmt->close();
            $db->close();
        } else {
            $db = getDBConnection();
            $stmt = $db->prepare(
                "INSERT INTO medical_cert (
                    paitentid,diagnosis,work_is_factor,manag_plan,
                    treat_rev_date,fit_for_work_status,unfitfrom,
                    unfitto,suitfrom,suitto,modfrom,assreq,exam_date,
                    has_cap_for_duration,has_cap_for_duration_days,
                    has_cap_for_liftingupto,has_cap_for_walkingupto,
                    has_cap_for_sittingupto,has_cap_for_standingupto,
                    has_cap_for_travellingupto,has_cap_for_keyingupto,
                    other_restrictions,other_restrictions_details,other_restrictions_other,
                    fitness_review_date,
                    pre_existing_factors,
                    post_desc,
                    est_return_to_work,
                    fact_delay,
                    comment,
                    referral
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param( "issssssssssssssssssssssssssssss",
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
                $this->fitness_review_date ,
                $this->pre_existing_factors ,
                $this->post_desc ,
                $this->est_return_to_work ,
                $this->fact_delay ,
                $this->comment,
                $this->referral );
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
        if((strtotime($this->treat_rev_date)) === false) $this->treat_rev_date = NULL;
        if((strtotime($this->unfitfrom)) === false) $this->unfitfrom = NULL;
        if((strtotime($this->unfitto)) === false) $this->unfitto = NULL;
        if((strtotime($this->suitfrom)) === false) $this->suitfrom = NULL;
        if((strtotime($this->suitto)) === false) $this->suitto = NULL;
        if((strtotime($this->modfrom)) === false) $this->modfrom = NULL;
        if((strtotime($this->exam_date)) === false) $this->exam_date = NULL;
        if((strtotime($this->fitness_review_date)) === false) $this->fitness_review_date = NULL;
    }
}
