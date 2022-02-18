<?php
class Location {
    var $i_LocationId       = '';
    var $s_LocationName     = '';
    var $s_LocationAddress  = '';
    var $s_LocationSuburb   = '';
    var $s_LocationState    = '';
    var $s_LocationPostcode = '';
    var $s_LocationPhone    = '';
    var $s_LocationFax      = '';
    var $b_lo_deleted       = 0;

    function load() {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT * FROM locations WHERE LocationId=? LIMIT 1");
        $stmt->bind_param( 'i', $this->i_LocationId );
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result(
            $this->i_LocationId,
            $this->s_LocationName,
            $this->s_LocationAddress,
            $this->s_LocationSuburb,
            $this->s_LocationState,
            $this->s_LocationPostcode,
            $this->s_LocationPhone,
            $this->s_LocationFax,
            $this->b_lo_deleted
        );

        $stmt->execute() or die($stmt->error);
        $stmt->fetch();
        $stmt->close();
        $db->close();
    }

    function save() {
        if( isset($this->i_LocationId) && $this->i_LocationId != 0 ) {
            $db = getDBConnection();
            $stmt = $db->prepare(
            'UPDATE locations
                SET LocationName = ?,
                    LocationAddress = ?,
                    LocationSuburb = ?,
                    LocationState = ?,
                    LocationPostcode = ?,
                    LocationPhone = ?,
                    LocationFax = ?,
                    lo_deleted = ?
                WHERE LocationId=?' );

            $stmt->bind_param( 'sssssssii',
                $this->s_LocationName,
                $this->s_LocationAddress,
                $this->s_LocationSuburb,
                $this->s_LocationState,
                $this->s_LocationPostcode,
                $this->s_LocationPhone,
                $this->s_LocationFax,
                $this->b_lo_deleted,
                $this->i_LocationId
             );
            $stmt->execute() or die($stmt->error);
            $stmt->close();
            $db->close();
        } else {
            $db = getDBConnection();
            $stmt = $db->prepare(
                'INSERT INTO locations (
                    LocationName,
                    LocationAddress,
                    LocationSuburb,
                    LocationState,
                    LocationPostcode,
                    LocationPhone,
                    LocationFax,
                    lo_deleted
                ) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->bind_param( 'sssssssi',
                $this->s_LocationName,
                $this->s_LocationAddress,
                $this->s_LocationSuburb,
                $this->s_LocationState,
                $this->s_LocationPostcode,
                $this->s_LocationPhone,
                $this->s_LocationFax,
                $this->b_lo_deleted );
            $stmt->execute() or die($stmt->error);
            $this->id = $db->insert_id;
            $stmt->close();
            $db->close();
        }
        return $this->id;
    }
}
