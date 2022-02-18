<?php
class User {
    var $id; // key field is id;
    var $username;
    var $password;
    var $firstname;
    var $lastname;
    var $usertype;
    var $active;

    function __construct(){
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
        $stmt = $db->prepare("SELECT UserId, Username, Firstname, Lastname, UserType, Active
            FROM users WHERE UserId = ?");
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
    function Save() {
        $db = getDBConnection();
        if( !isset($this->id) || $this->id == 0) {
            $stmt = $db->prepare("INSERT users( Username, Password, Firstname, Lastname, UserType, Active ) VALUES( ?,PASSWORD(?),?,?,?,? )");
            $stmt->bind_param( "ssssss",$this->username,$this->password,$this->firstname,$this->lastname,$this->usertype,$this->active );
            $stmt->execute() or die($stmt->error);
            $this->id = $db->insert_id;
            $stmt->close();
        } else {
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
