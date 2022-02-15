<?php
require_once('../../etc/config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');

if(isset($_POST['isnew'])) {
    if($_POST['isnew']=='1') {
        header('Location: '.  SECURE_URL . WORKERS_COMP_FORM);
        exit();
    }
}
$js = '<script type="text/javascript">
    s_url = "'. SECURE_URL . DOC_WORKERS_COMP_FORM .'";
</script>';

echo getHeader('Client record search', $s_menu, $js);

    if( !( isset($_GET['Surname']) && $_GET['Surname'] != "" )) {
?>

  <form id="SearchForm" action="search_DR.php" method="get" name="SearchForm">

    <h6>Enter a surname to complete or update a medical certificate.</h6>
    <table border="0" cellspacing="0" cellpadding="5" width="100%">
       <tr>
        <td width="132" valign="top">Patient Surname</td>
        <td width="466" valign="top">
<input name="Surname" type="text" id="inputString" autocomplete="off" onkeyup="lookup(this.value);" />


            <span>Only current medical certificates displayed</span>
            <div class="suggestionsBox" id="suggestions" style="display: none;">
                <img src="upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                <div class="suggestionList" id="autoSuggestionsList" style="padding:1em 0;"></div>
            </div></td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td><label>
          <button type="submit" class="btn btn-primary" name="search" id="search" value="Search">Search</button>
        </label></td>
      </tr>
    </table>
  </form>

<?php
    } else {
        $surname = $_GET['Surname'].'%';
        $db = getDBConnection();

        if( isAdmin() ){
            $stmt = $db->prepare(
                "SELECT id, surname, othernames, dob, injury_dateof
                FROM paitent
                WHERE surname like ?
                ORDER by surname, othernames ASC"
            );
        } else {
            /*
            $sql = 'SELECT id, surname, othernames, dob, injury_dateof from paitent where surname like ?
                AND (
                    cons_status != 'Final' OR NOW() <= DATE_ADD(examdate,INTERVAL 15 DAY)
                ) ORDER by id DESC';
                '
            * */
            $stmt = $db->prepare(
                "SELECT id, surname, othernames, dob, injury_dateof
                FROM paitent
                WHERE surname like ?
                ORDER by surname, othernames ASC"
            );
        }
        $stmt->bind_param("s", $surname );
        $stmt->execute();
        $stmt->bind_result( $patientId, $surname, $othernames, $dob, $injury_dateof);
        $stmt->store_result();
        if( $stmt->num_rows > 0 ) {
?>
  <h6>Search results for &quot;<?php echo $_GET['Surname'] ?>&quot;</h6>

    <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <td width="132">Name</td>
      <td width="72">DOB</td>
      <td width="72">DOI</td>
      <td width="302">Diagnosis</td>
    </tr>
<?php

        while ($stmt->fetch()){
            echo '<tr valign="top">
      <td><a href=\'WorkersComp_form_DR2.php?id='. $patientId .'\'>'. $surname .', '.$othernames .'</a></td>
      <td>'. processDate($dob) .'</td>
      <td>'. processDate($injury_dateof) .'</td>
      <td>';

            $db2 = getDBConnection();
            $stmt2 = $db2->prepare( "select distinct treat_rev_date, diagnosis from medical_cert where paitentid = ? and diagnosis <> '' order by treat_rev_date desc");
            $stmt2->bind_param("s", $patientId );
            $stmt2->execute();
            $stmt2->bind_result( $treat_rev_date, $diagnosis  );
            $stmt2->store_result();
            if( $stmt2->num_rows > 0 ) {
            echo '<ul>';
                while($stmt2->fetch()) {
                    echo '<li>'. processDate($treat_rev_date) .' - '. $diagnosis .'</li>';
                }
                echo '</ul>';
            }
            $stmt2->close();
            $db2->close();
            echo '</td></tr>';
        }
        $stmt->close();
        $db->close();

        echo '</table>';
        if( !isAdmin()) {
            echo '<p>Only current medical certificates displayed</p>';
        }
    echo '<p>&nbsp;</p>';
    }
}
echo getFooter();
