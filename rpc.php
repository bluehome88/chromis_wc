<?php include("secure.php");

    if( isDoctor() ){

        if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString']."%";

            if(strlen($queryString) > 0) {
                /*
                 * SELECT Id, surname, othernames, suburb FROM paitent WHERE surname LIKE ?
                 * AND ( cons_status <> 'Final' OR NOW() <= DATE_ADD(examdate,INTERVAL 15 DAY))
                 * LIMIT 10
                 * */

                $db = getDBConnection();
                $stmt = $db->prepare(
                    "SELECT Id, surname, othernames, suburb, DATE_FORMAT(examdate,'%d-%m-%y')
                    FROM paitent
                    WHERE surname LIKE ?
                    ORDER BY examdate DESC LIMIT 20"
                );
                $stmt->bind_param("s", $queryString );
                $stmt->execute();
                $rowcount = $stmt->num_rows;
                $stmt->bind_result( $id, $surname, $othernames, $suburb, $examdate );
                echo '<ul>';
                while($stmt->fetch()) {
                    echo '<li onClick="showPatient(\''.$id.'\');">'.$surname.', '.$othernames.' ('.$suburb.') &nbsp; '.$examdate.'</li>';
                }
                $stmt->close();
                $db->close();
                echo '<li onClick="doSearch();">more detail...</li>';
                echo '</ul>';
            }
        } else {
            echo 'There should be no direct access to this script!';
        }
    }
