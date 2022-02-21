<?php
$b_user_area = 1;
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

if (isset($_POST['isnew'])) {
  if ($_POST['isnew'] == '1') {
    header('Location: ' .  SECURE_URL . WORKERS_COMP_FORM);
    exit();
  }
}

$js = '
<script type="text/javascript">
    function showPatient( id )
    {
        window.location.href = "WorkersComp_form.php?id=" + id;
    }
</script>
';

echo getHeader('Client record interface', $s_menu, $js);
?>
<p>&nbsp;</p>
<?php
if (!isset($_POST['Surname'])) {
?>
  <form id="SearchForm" action="" method="post" name="SearchForm">
    <h2>New clients</h2>
    <p>I want to <a href="WorkersComp_form.php">create a new client record.</a></p>
    <!--WORKER FITNESS OPTIONS ARE LISTED HERE -->
    <tbody id="New"></tbody>
    <h2>Returning clients</h2>
    <p>Enter a surname to update an existing client record.</p>
    <tbody id="Existing">
      <table border="0" cellspacing="0" cellpadding="5" width="100%" class="table">
        <tr>
          <td>Patient Surname</td>
          <td>
            <input name="Surname" type="text" id="inputString" onblur="fill();" autocomplete="off" onkeyup="lookup(this.value);" class="form-control form-control-sm" />
            <div class="form-text">
              Only current clients displayed
            </div>
            <div class="suggestionsBox" id="suggestions" style="display: none;">
              <img src="upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
              <div class="suggestionList" id="autoSuggestionsList">
                <ul>
                  &nbsp;
                </ul>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="text-center"><label>
              <button type="submit" class="btn btn-primary btn-sm" name="search" id="search" value="Search">Search</button>
            </label></td>
        </tr>
      </table>
  </form>
  <?php
} else {
  $surname = $_POST['Surname'] . '%';
  $db = getDBConnection();
  $stmt = $db->prepare(
    "select id, surname, othernames, dob, injury_dateof from paitent where surname like ? " .
      "and ( cons_status <> 'Final' or NOW() <= DATE_ADD(examdate,INTERVAL 15 DAY)) " .
      "order by id desc"
  );
  $stmt->bind_param("s", $surname);
  $stmt->execute();
  $stmt->bind_result($patientId, $surname, $othernames, $dob, $injury_dateof);
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
  ?>
    <h6>Search results</h6>
    <div class="table-responsive">
      <table class="table" border="0" cellspacing="0" cellpadding="5" width="100%">
        <tr>
          <td width="132">Name</td>
          <td width="72">DOB</td>
          <td width="72">DOI</td>
          <td width="302">Diagnosis</td>
        </tr>
        <?php
        while ($stmt->fetch()) {
        ?>
          <tr valign="top">
            <td><a href='WorkersComp_form.php?id=<?php echo $patientId ?>'><?php echo $surname ?>, <?php echo $othernames ?></a></td>
            <td><?php echo $dob ?></td>
            <td><?php echo $injury_dateof ?></td>
            <td>
              <?php
              $db2 = getDBConnection();
              $stmt2 = $db2->prepare("select distinct treat_rev_date, diagnosis from medical_cert where paitentid = ? and diagnosis <> '' order by treat_rev_date desc");
              $stmt2->bind_param("s", $patientId);
              $stmt2->execute();
              $stmt2->bind_result($treat_rev_date, $diagnosis);
              $stmt2->store_result();
              if ($stmt2->num_rows > 0) {
              ?>
                <ul>
                  <?php while ($stmt2->fetch()) { ?>
                    <li><?php echo $treat_rev_date ?> - <?php echo $diagnosis ?></li>
                  <?php } ?>
                </ul>
              <?php
              }
              $stmt2->close();
              $db2->close();
              ?>
            </td>
          </tr>
        <?php
        }
        ?>
      </table>
    </div>
<?php
  }
  $stmt->close();
  $db->close();
}
echo getFooter();
