<?php
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

if (isset($_POST['isnew'])) {
  if ($_POST['isnew'] == '1') {
    header('Location: ' .  SECURE_URL . WORKERS_COMP_FORM);
    exit();
  }
}
$js = '<script type="text/javascript">
    function showPatient( id )
    {
        window.location.href = "WorkersComp_form_DR2.php?id=" + id;
    }
</script>';
echo getHeader('Examination location', $s_menu, $js);

if (!(isset($_POST['Surname']) && $_POST['Surname'] != "")) {
?>
  <form id="SearchForm" action="secure.php" method="post" name="LocationForm">
    <input type="hidden" name="authprocess" value="location" />
    <h6>Please select your location</h6>
    <div class="table-responsive">
      <table class="table">
        <tr>
          <td width="132" valign="top">Doctor</td>
          <td><?php echo $user['Firstname'] . ' ' . $user['Lastname'] ?></td>
        </tr>
        <tr>
          <td width="132" valign="top">Current location</td>
          <td width="466" valign="top">

            <?php
            $db = getDBConnection();
            $stmt = $db->prepare("SELECT L.LocationId, L.LocationName, M.MPN " .
              "FROM locations L inner join medicare_details M on (L.LocationId = M.LocationId) " .
              "WHERE M.UserId=?");
            $stmt->bind_param("i", $user['UserId']);
            $stmt->execute();
            $stmt->bind_result($locationId, $locationName, $mpn);
            $stmt->store_result();
            $s_checked = 'checked="checked"';
            if ($stmt->num_rows > 0) {
              while ($stmt->fetch()) {
                echo '<div class="form-check">
                  <input type="radio" class="form-check-input" id="a' . $locationId . '" name="LocationId" value="' . $locationId . '" ' . $s_checked . '>
                  <label class="form-check-label" for="a' . $locationId . '">' . $locationName . '</label>
                </div>';
                $s_checked = '';
              }
            }
            $stmt->close();
            $db->close();
            ?>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="text-center"><label>
              <button type="submit" class="btn btn-primary btn-sm" name="Location" id="Location" value="Continue">Continue</button>
            </label></td>
        </tr>
      </table>
    </div>
  </form>
<?php
}
echo getFooter();
