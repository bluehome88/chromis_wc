<?php
$b_user_area = 1;
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

// $js  = '    <script src="./scripts/wc-form.js"></script>' . "\n";
$js .= '    <script src="./scripts/jquery.validate.min.js"></script>' . "\n";
$js .= '    <script src="./scripts/jqEasyCharCounter/jquery.jqEasyCharCounter.min.js"></script>' . "\n";

$a_patient =  new patient;
if (isset($_GET['id'])) {
  $a_patient->id = (int)$_GET['id'];
  $a_patient->Load();
}
$a_patient->ResetEmptyDates();
echo getHeader('Client record', $s_menu, $js);
?>
<h6>Examination details</h6>
<form action="process.php" method="post" name="TravHealthForm">
  <input class="form-control form-control-sm" name='mode' value='form1' type=hidden />
  <input class="form-control form-control-sm" name='id' value='<?php echo $a_patient->id ?>' type=hidden />
  <div class="table-responsive">
    <table border="0" cellspacing="0" cellpadding="5" class="table">
      <tr>
        <td>Examination date</td>
        <td>
          <input class="date form-control form-control-sm" name="EXDate" value="<?php echo ($a_patient->examdate != '0000-00-00' ? date("d/m/Y", convertToDate($a_patient->examdate)) : date("d/m/Y")); ?>" type="text" class="date" placeholder="dd/mm/yyyy" />
      </tr>
      <tr>
        <td>Claim no.</td>
        <td><input class="form-control form-control-sm" type="text" name="ClaimNo" size="30" value="<?php echo $a_patient->claimno ?>"></td>
      </tr>
      <tr>
        <td><acronym title="Date of injury">DOI</acronym></td>
        <td>
          <input class="form-control form-control-sm date" name="injury_dateof" value="<?php echo ($a_patient->injury_dateof != '0000-00-00' ? date("d/m/Y", convertToDate($a_patient->injury_dateof)) : ''); ?>" type="text" class="date" placeholder="dd/mm/yyyy" />
      </tr>
      <tr>
        <td>Medicare number</td>
        <td><input class="form-control form-control-sm" type="text" name="medicare_no" size="30" value="<?php echo $a_patient->medicare_no ?>"></td>
      </tr>
      <tr>
        <td>Date first seen for this injury / disease</td>
        <td>
          <input class="form-control form-control-sm date" name="date_first_seen" value="<?php echo ($a_patient->date_first_seen != '0000-00-00' ? date("d/m/Y", convertToDate($a_patient->date_first_seen)) : ''); ?>" type="text" class="date" placeholder="dd/mm/yyyy" />
        </td>
      </tr>
    </table>
  </div>
  <br />
  <h6>Worker details</h6>
  <div class="table-responsive">
    <table class="table" border="0" cellspacing="0" cellpadding="5" width="100%">
      <tr>
        <td>Surname</td>
        <td><input class="form-control form-control-sm" type="text" name="Surname" size="30" value="<?php echo $a_patient->surname ?>"></td>
      </tr>
      <tr>
        <td>Other names</td>
        <td><input class="form-control form-control-sm" type="text" name="OtherNames" size="30" value="<?php echo $a_patient->othernames ?>"></td>
      </tr>
      <tr>
        <td>Address</td>
        <td><input class="form-control form-control-sm" type="text" name="Address" size="30" value="<?php echo $a_patient->address ?>" /></td>
      </tr>
      <tr>
        <td>Suburb</td>
        <td><input class="form-control form-control-sm" type="text" name="Suburb" size="30" value="<?php echo $a_patient->suburb ?>" /></td>
      </tr>
      <tr>
        <td>State</td>
        <td>
          <select class="form-select form-select-sm" name="State" id="State">
            <option value="NSW" <?php echo (($a_patient->state == "NSW") ? "selected=\"selected\"" : ""); ?>>NSW</option>
            <option value="ACT" <?php echo (($a_patient->state == "ACT") ? "selected=\"selected\"" : ""); ?>>ACT</option>
            <option value="VIC" <?php echo (($a_patient->state == "VIC") ? "selected=\"selected\"" : ""); ?>>VIC</option>
            <option value="QLD" <?php echo (($a_patient->state == "QLD") ? "selected=\"selected\"" : ""); ?>>QLD</option>
            <option value="SA" <?php echo (($a_patient->state == "SA") ? "selected=\"selected\"" : ""); ?>>SA</option>
            <option value="WA" <?php echo (($a_patient->state == "WA") ? "selected=\"selected\"" : ""); ?>>WA</option>
            <option value="NT" <?php echo (($a_patient->state == "NT") ? "selected=\"selected\"" : ""); ?>>NT</option>
          </select>
      </tr>
      <tr>
        <td>Postcode</td>
        <td>
          <input class="form-control form-control-sm" type="text" name="PCode" size="4" value="<?php echo $a_patient->postcode ?>" />
        </td>
      </tr>
      <tr>
        <td>Phone (W)</td>
        <td><input class="form-control form-control-sm" type="text" name="WorkPhone" size="20" value="<?php echo $a_patient->phone ?>"></td>
      </tr>
      <tr>
        <td>Mobile</td>
        <td><input class="form-control form-control-sm" type="text" name="Mobile" size="20" value="<?php echo $a_patient->mobile ?>"></td>
      </tr>
      <tr>
        <td>Date of birth</td>
        <td>
          <?php
          list($db_year, $db_month, $db_day) = explode('-', $a_patient->dob);
          ?>
          <select class="form-select form-select-sm" name="day" id="day">
            <?php for ($a = 1; $a <= 31; $a++) echo '<option value="' . $a . '" ' . (($db_day == "$a") ? "selected=\"selected\"" : "") . '>' . $a . '</option>'; ?>
          </select>
          <select class="form-select form-select-sm" name="month" id="month">
            <option value="01" <?php echo ($db_month == "01" ? "selected" : "") ?>>January</option>
            <option value="02" <?php echo ($db_month == "02" ? "selected" : "") ?>>February</option>
            <option value="03" <?php echo ($db_month == "03" ? "selected" : "") ?>>March</option>
            <option value="04" <?php echo ($db_month == "04" ? "selected" : "") ?>>April</option>
            <option value="05" <?php echo ($db_month == "05" ? "selected" : "") ?>>May</option>
            <option value="06" <?php echo ($db_month == "06" ? "selected" : "") ?>>June</option>
            <option value="07" <?php echo ($db_month == "07" ? "selected" : "") ?>>July</option>
            <option value="08" <?php echo ($db_month == "08" ? "selected" : "") ?>>August</option>
            <option value="09" <?php echo ($db_month == "09" ? "selected" : "") ?>>September</option>
            <option value="10" <?php echo ($db_month == "10" ? "selected" : "") ?>>October</option>
            <option value="11" <?php echo ($db_month == "11" ? "selected" : "") ?>>November</option>
            <option value="12" <?php echo ($db_month == "12" ? "selected" : "") ?>>December</option>
          </select>
          <select class="form-select form-select-sm" name="Year" id="Year">
            <?php for ($a = 2011; $a >= (2010 - 130); $a--) echo '<option value="' . $a . '" ' . (($db_year == "$a") ? "selected=\"selected\"" : "") . '>' . $a . '</option>'; ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Occupation</td>
        <td><input class="form-control form-control-sm" type="text" name="Occupation" size="30" value="<?php echo $a_patient->occupation ?>" />
      </tr>
      <tr>
        <td>Hours / week</td>
        <td><input class="form-control form-control-sm" type="text" name="HoursPWeek" size="30" value="<?php echo $a_patient->hours_week ?>" /></td>
      </tr>
    </table>
  </div>
  <br />
  <h6>Employer details</h6>
  <div class="table-responsive">
    <table border="0" cellspacing="0" cellpadding="5" width="100%" class="table">
      <tr>
        <td>Employer name:</td>
        <td class="field"><input class="form-control form-control-sm" type="text" name="Employer" size="30" value="<?php echo $a_patient->emp_name ?>" /></td>
      </tr>
      <tr>
        <td>Employer address</td>
        <td><input class="form-control form-control-sm" type="text" name="EmpAdd" size="30" value="<?php echo $a_patient->emp_address ?>" /></td>
      </tr>
      <tr>
        <td>Suburb</td>
        <td><input class="form-control form-control-sm" type="text" name="EmpSub" size="30" value="<?php echo $a_patient->emp_suburb ?>" /></td>
      </tr>
      <tr>
        <td>State</td>
        <td>
          <select class="form-select form-select-sm" name="EmpState">
            <option value="NSW" <?php echo (($a_patient->emp_state == "NSW") ? "selected=\"selected\"" : ""); ?>>NSW</option>
            <option value="ACT" <?php echo (($a_patient->emp_state == "ACT") ? "selected=\"selected\"" : ""); ?>>ACT</option>
            <option value="VIC" <?php echo (($a_patient->emp_state == "VIC") ? "selected=\"selected\"" : ""); ?>>VIC</option>
            <option value="QLD" <?php echo (($a_patient->emp_state == "QLD") ? "selected=\"selected\"" : ""); ?>>QLD</option>
            <option value="SA" <?php echo (($a_patient->emp_state == "SA") ? "selected=\"selected\"" : ""); ?>>SA</option>
            <option value="WA" <?php echo (($a_patient->emp_state == "WA") ? "selected=\"selected\"" : ""); ?>>WA</option>
            <option value="NT" <?php echo (($a_patient->emp_state == "NT") ? "selected=\"selected\"" : ""); ?>>NT</option>
          </select>
      </tr>
      <tr>
        <td>Postcode</td>
        <td>
          <input class="form-control form-control-sm" type="text" name="EmpPCode" size="4" value="<?php echo $a_patient->emp_postcode ?>" />
        </td>
      </tr>
      <tr>
        <td colspan="2" class="text-center"><button type="submit" class="btn btn-primary btn-sm" name="Submit" value="Save" onClick="MM_validateForm('OtherNames','','R', 'Surname','','R','PCode','','RisNum','Address','','R','Suburb','','R','R','Postcode','','R');return document.MM_returnValue">Submit</button></td>
      </tr>
    </table>
  </div>
</form>
<script>
  jQuery.validator.methods.date = function(value, element) {
    return this.optional(element) || !/Invalid|NaN/.test(new Date(value)) || /^(\d+)\/(\d+)\/(\d{2,})$/.test(value);
  }
  $('.date').datepicker({
    dateFormat: 'dd/mm/yy',
    onSelect: function() {
      $(this).blur();
    },
    onClose: function() {
      $(this).blur();
      $('.btn-primary').removeAttr('disabled');
    }
  });
</script>
<?php
echo getFooter();
