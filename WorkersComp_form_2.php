<?php
define('_VALID_MOS', 1);
include "secure.php";
include "include/functions.php";
include "include/class_objects.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <title>CHROMIS Workcover NSW Medical Certificate</title>
  <link href="chromisWCstyle.css" rel="stylesheet" type="text/css" />
  <link href="chromisGED.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/JavaScript" src="scripts/dates.js"></script>
  <script type="text/JavaScript" src="scripts/wc-form-tools.js"></script>
</head>

<body>
  <div id="line">
    <!---grey line at very top of page--->

  </div>
  <!-----end line----->

  <div id="page-wrap">

    <div id="inside">

      <div id="container">
        <div id="header">

          <div id="logo">
            <img src="images/logo.png" width="277" height="126" alt="Chromis Medical Health Services" />

          </div>
          <!-------end logo------>
          <?php include("include/menu.php"); ?>
          <div id="content">
            <h4>workcover nsw medical certificate</h4>
            <h1> client record form</h1>
            <div class="content_category">
              <?

              ?>
              <h6><br />
                <?

                $a_patient =  new patient;
                if (isset($_GET['id'])) {
                  $a_patient->id = $_GET['id'] * 1;
                  $a_patient->Load();
                }
                $a_patient->ResetEmptyDates();

                ?>
                Examination details</h6>
              <form action="process_2.php" method="post" name="TravHealthForm">
                <input name='mode' value='form1' type=hidden />
                <input name='id' value='<?php echo $a_patient->id ?>' type=hidden />

                <table border="0" cellspacing="0" cellpadding="5" width="100%">
                  <tr>
                    <td width="128">Examination Date</td>
                    <td width="389"><input name="EXDate" value="<?php echo ($a_patient->examdate != '0000-00-00' ? date("d/m/Y", convertToDate($a_patient->examdate)) : ''); ?>">
                      <input type=button value="select" onclick="displayDatePicker('EXDate', false, 'dmy', '/');">
                    </td>
                  </tr>
                  <tr>
                    <td width="128">Claim no.</td>
                    <td width="389"><input type="text" name="ClaimNo" size="30" value="<?php echo $a_patient->claimno ?>"></td>
                  </tr>
                </table>
                <h6>Worker details</h6>
                <table border="0" cellspacing="0" cellpadding="5" width="100%">
                  <tr>
                    <td width="128">Surname</td>
                    <td width="389"><input type="text" name="Surname" size="30" value="<?php echo $a_patient->surname ?>"></td>
                  </tr>
                  <tr>
                    <td width="128">Other names</td>
                    <td width="389"><input type="text" name="OtherNames" size="30" value="<?php echo $a_patient->othernames ?>"></td>
                  </tr>
                  <tr>
                    <td width="128">Address</td>
                    <td width="389"><input type="text" name="Address" size="30" value="<?php echo $a_patient->address ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">Suburb</td>
                    <td width="389"><input type="text" name="Suburb" size="30" value="<?php echo $a_patient->suburb ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">State</td>
                    <td width="389"><label>
                        <select name="State" id="State" class="form-select form-select-sm">
                          <option value="NSW" <?php echo (($a_patient->state == "NSW") ? "selected=\"selected\"" : ""); ?>>NSW</option>
                          <option value="ACT" <?php echo (($a_patient->state == "ACT") ? "selected=\"selected\"" : ""); ?>>ACT</option>
                          <option value="VIC" <?php echo (($a_patient->state == "VIC") ? "selected=\"selected\"" : ""); ?>>VIC</option>
                          <option value="QLD" <?php echo (($a_patient->state == "QLD") ? "selected=\"selected\"" : ""); ?>>QLD</option>
                          <option value="SA" <?php echo (($a_patient->state == "SA") ? "selected=\"selected\"" : ""); ?>>SA</option>
                          <option value="WA" <?php echo (($a_patient->state == "WA") ? "selected=\"selected\"" : ""); ?>>WA</option>
                          <option value="NT" <?php echo (($a_patient->state == "NT") ? "selected=\"selected\"" : ""); ?>>NT</option>
                        </select>
                      </label>
                      Postcode
                      <input type="text" name="PCode" size="4" value="<?php echo $a_patient->postcode ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td width="128">Phone (W)</td>
                    <td width="389"><input type="text" name="WorkPhone" size="20" value="<?php echo $a_patient->phone ?>"></td>
                  </tr>
                  <tr>
                    <td width="128">Mobile</td>
                    <td width="389"><input type="text" name="Mobile" size="20" value="<?php echo $a_patient->mobile ?>"></td>
                  </tr>
                  <tr>
                    <td width="128">Date of birth</td>
                    <td width="389">
                      <?php
                      list($db_year, $db_month, $db_day) = split('[/.-]', $a_patient->dob);
                      ?>
                      <select name="day" id="day" class="form-select form-select-sm">
                        <?php for ($a = 1; $a <= 31; $a++) echo '<option value="' . $a . '" ' . (($db_day == "$a") ? "selected=\"selected\"" : "") . '>' . $a . '</option>'; ?>
                      </select>
                      <select name="month" id="month" class="form-select form-select-sm">
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
                      <select name="Year" id="Year" class="form-select form-select-sm">
                        <?php for ($a = 2011; $a >= (2010 - 130); $a--) echo '<option value="' . $a . '" ' . (($db_year == "$a") ? "selected=\"selected\"" : "") . '>' . $a . '</option>'; ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td width="128">Occupation</td>
                    <td width="389"><input type="text" name="Occupation" size="30" value="<?php echo $a_patient->occupation ?>" />
                  </tr>
                  <tr>
                    <td width="128">Hours / week</td>
                    <td width="389"><input type="text" name="HoursPWeek" size="30" value="<?php echo $a_patient->hours_week ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">DOI</td>
                    <td width="389"><input name="InjuryDate" value="<?php echo ((($a_patient->injury_dateof != '') && ($a_patient->injury_dateof != '0000-00-00')) ? date("d/m/Y", convertToDate($a_patient->injury_dateof)) : ''); ?>" />
                      <input type="button" value="select" onclick="displayDatePicker('InjuryDate', false, 'dmy', '/');" />
                    </td>

                  </tr>
                </table>
                <h6>Employer details</h6>
                <table border="0" cellspacing="0" cellpadding="5" width="100%">
                  <tr>
                    <td width="128">Employer name:</td>
                    <td class="field"><input type="text" name="Employer" size="30" value="<?php echo $a_patient->emp_name ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">Employer address</td>
                    <td width="389"><input type="text" name="EmpAdd" size="30" value="<?php echo $a_patient->emp_address ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">Suburb</td>
                    <td width="389"><input type="text" name="EmpSub" size="30" value="<?php echo $a_patient->emp_suburb ?>" /></td>
                  </tr>
                  <tr>
                    <td width="128">State</td>
                    <td width="389"><label>
                        <select name="EmpState" class="form-select form-select-sm">
                          <option value="NSW" <?php echo (($a_patient->emp_state == "NSW") ? "selected=\"selected\"" : ""); ?>>NSW</option>
                          <option value="ACT" <?php echo (($a_patient->emp_state == "ACT") ? "selected=\"selected\"" : ""); ?>>ACT</option>
                          <option value="VIC" <?php echo (($a_patient->emp_state == "VIC") ? "selected=\"selected\"" : ""); ?>>VIC</option>
                          <option value="QLD" <?php echo (($a_patient->emp_state == "QLD") ? "selected=\"selected\"" : ""); ?>>QLD</option>
                          <option value="SA" <?php echo (($a_patient->emp_state == "SA") ? "selected=\"selected\"" : ""); ?>>SA</option>
                          <option value="WA" <?php echo (($a_patient->emp_state == "WA") ? "selected=\"selected\"" : ""); ?>>WA</option>
                          <option value="NT" <?php echo (($a_patient->emp_state == "NT") ? "selected=\"selected\"" : ""); ?>>NT</option>
                        </select>
                      </label>
                      Postcode
                      <input type="text" name="EmpPCode" size="4" value="<?php echo $a_patient->emp_postcode ?>" />
                    </td>
                  </tr>
                </table>
                <h6>Medical practitioner details</h6>
                <table border="0" cellspacing="0" cellpadding="5" width="100%">
                  <tr>
                    <td width="268">Name</td>
                    <td width="261" class="field"><select name="Doctor" class="form-select form-select-sm">
                        <?
                        $wut = NULL;
                        $wut[''] = 'Please select';
                        $wut['Dr Puru Sagar'] = 'Dr Puru Sagar';
                        $wut['Dr Mary McGinty'] = 'Dr Mary McGinty';
                        $wut['Dr Christine Aus'] = 'Dr Christine Aus';
                        $wut['Dr Vince Duffy'] = 'Dr Vince Duffy';

                        foreach ($wut as $k => $v) {
                          $sel = ($a_patient->doctor_name == "$k") ? "selected=\"selected\"" : "";
                          echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                        }
                        ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td width="268">Location</td>
                    <td width="261" class="field"><select name="Location" class="form-select form-select-sm">
                        <?
                        $wut = NULL;
                        $wut[''] = 'Please select';
                        $wut['Maitland'] = 'Maitland';
                        $wut['Kotara'] = 'Kotara';
                        foreach ($wut as $k => $v) {
                          $sel = ($a_patient->doctor_location == "$k") ? "selected=\"selected\"" : "";
                          echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                        }
                        ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td width="268">I agree to be this worker&rsquo;s Nominated Treating Doctor
                      and to assist in his / her return to work</td>
                    <td width="261" class="field"><label>
                        <input type="radio" name="Commit[]" value="Yes" <?php echo (strstr($a_patient->doctor_agrees, "Yes") ? "checked" : ""); ?> />
                        Yes</label>
                      <br />
                      <label>
                        <input type="radio" name="Commit[]" value="No" <?php echo (strstr($a_patient->doctor_agrees, "No") ? "checked" : ""); ?> />
                        No</label>
                      <br />
                    </td>
                  </tr>
                  <tr>
                    <td width="268">&nbsp;</td>
                    <td width="261"><button type="submit" class="btn btn-primary btn-sm" name="Submit" value="Save" onClick="MM_validateForm('OtherNames','','R', 'Surname','','R','PCode','','RisNum','Address','','R','Suburb','','R','R','Postcode','','R');return document.MM_returnValue">Submit</button></td>
                  </tr>
                </table>
              </form>
              <?php

              ?>
              </p>
              </tbody>
              <tfoot></tfoot>

              <img src="images/fish.png" width="104" height="20" alt="CHROMIS medical health services" />
            </div>
          </div>
        </div>
        <!-----------END CONTENT-------->
        <!----end navButtons------>
        <div id="footer">


          <div class="copyright">
            &copy; CHROMIS
          </div>
          <!-----end copyright---->
        </div>
        <!-----end Footer------>
      </div>
      <!----end container---->
      <!-----end newsBox----->
    </div>
    <!-----end inside----->
  </div>
  <!------end page-wrap------>
</body>

</html>