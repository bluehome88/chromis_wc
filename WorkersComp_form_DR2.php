<?php
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

$a_patient = new patient;

if (isset($_GET['id'])) {
    $a_patient->id = (int)$_GET['id'];
    $a_patient->Load();
} else {
    die('PatientID missing');
}

$s_cons_status = '';
$s_cons_status = $a_patient->cons_status ? $a_patient->cons_status : 'Initial';

$js  = '    <script src="./scripts/wc-form.js"></script>' . "\n";
$js .= '    <script src="./scripts/jquery.validate.min.js"></script>' . "\n";
$js .= '    <script src="./scripts/jqEasyCharCounter/jquery.jqEasyCharCounter.min.js"></script>' . "\n";

$db = getDBConnection();
$stmt = $db->prepare("select id from medical_cert where paitentid=? order by id desc limit 1");
$stmt->bind_param('s', $a_patient->id);
$stmt->execute();
$stmt->bind_result($certId);
$stmt->fetch();
$stmt->close();
$db->close();

$a_medical_cert =  new MedicalCert;
$a_medical_cert->id = (int)$certId;
$a_medical_cert->Load();

$user = $_SESSION['User'];

$js .= '    <script> var o_def = {consult:"' . $s_cons_status . '", wfit:"' . $a_medical_cert->fit_for_work_status . '",other:"' . $a_medical_cert->other_restrictions . '"} </script>' . "\n";

echo getHeader('Medical certificate form', $s_menu, $js, true);

?>
<form action="<?php echo SECURE_URL . DOC_PROCESS; ?>" method="post" class="form-horizontal" id="DR_WCMC">
    <input name='mode' value='form2' type=hidden />
    <input name='paitentid' value='<?php echo $a_patient->id ?>' type=hidden />
    <h6>&nbsp;</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Consultation:</td>
                <td>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio1" name="Consult" value="Initial" <?php echo (strstr($s_cons_status, "Initial") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio1">Initial</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio2" name="Consult" value="First Progress" <?php echo (strstr($s_cons_status, "First Progress") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio2">First Progress</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio3" name="Consult" value="Progress" <?php echo (strstr($s_cons_status, "Progress") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio3">Progress</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio4" name="Consult" value="Final" <?php echo (strstr($s_cons_status, "Final") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio4">Final</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Date of examination:</td>
                <td>
                    <?php
                    $value = date('d/m/Y');
                    ?>
                    <input type="text" name="exam_date" value="<?php echo $value; ?>" required="" class="required date valid form-control form-control-sm" placeholder="dd/mm/yyyy" id="exam_date">
                </td>
            </tr>
        </table>
    </div>
    <?php
    $a_emp = arrayLoader(array(
        $a_patient->emp_name,
        $a_patient->emp_address,
        $a_patient->emp_suburb . ' ' . $a_patient->emp_state . ' ' . $a_patient->emp_postcode
    ));

    $s_emp = implode('<br />', $a_emp);

    $o_date = null;
    $o_date = new DateTime($a_patient->dob);
    $value = '';
    $value = $o_date->format('d/m/Y');

    echo '<br /><h6>PART A: Retrieved patient details</h6>
        <div class="table-responsive">
            <table class="docForm table">
            <tr>
                <td>Patient\'s name:</td>
                <td>' . $a_patient->othernames . '&nbsp;' . $a_patient->surname . '</td>
            </tr>
            <tr>
                <td>Date of birth:</td>
                <td>' . $value . '&nbsp;</td>
            </tr>
            <tr>
                <td>Patient\'s address:</td>
                <td>' . $a_patient->address . ',<br />' . $a_patient->suburb . ', ' . $a_patient->state . ' ' . $a_patient->postcode . '</td>
            </tr>
            <tr>
                <td>Claim Number</td>
                <td>' . ($a_patient->claimno ? $a_patient->claimno : '-') . '&nbsp;</td>
            </tr>
            <tr>
                <td>Medicare Number</td>
                <td>' . ($a_patient->medicare_no ? $a_patient->medicare_no : '-') . '&nbsp;</td>
            </tr>
            <tr>
                <td>Patient\'s occupation/job title:</td>
                <td>' . $a_patient->occupation . '</td>
            </tr>
            <tr>
                <td>Employer\'s name and contact details:</td>
                <td>' .  $s_emp . '</td>
            </tr>
            </table>
        </div>';
    ?>
    <br />
    <h6>Part B: Medical Certification</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Diagnosis of work related injury/disease:</td>
                <td>
                    <textarea rows="4" cols="30" name="diagnosis" required="" class="required sml valid form-control form-control-sm" id="DR_WCMC-element-9"><?php echo $a_medical_cert->diagnosis; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>Patient stated date of injury</td>
                <td>
                    <?php
                    $value = '';
                    if ($a_patient->injury_dateof != '0000-00-00') {
                        $o_date = null;
                        $o_date = new DateTime($a_patient->injury_dateof);
                        $value = '';
                        $value = $o_date->format('d/m/Y');
                    }
                    ?>
                    <input type="text" name="InjuryDate" value="<?php echo $value; ?>" required="" class="required date valid form-control form-control-sm" placeholder="dd/mm/yyyy" id="DR_WCMC-element-11">
                </td>
            </tr>
        </table>
    </div>

    <div class="Initial">
        <h6>Initial certificate only</h6>
        <div class="table-responsive">
            <table class="docForm table">
                <tr>
                    <td>Patient was first seen at this practice/hospital for this injury/disease on:</td>
                    <td>
                        <?php
                        $value = '';
                        if ($a_patient->date_first_seen != '0000-00-00') {
                            $o_date = null;
                            $o_date = new DateTime($a_patient->date_first_seen);
                            $value = '';
                            $value = $o_date->format('d/m/Y');
                        }
                        ?>
                        <input type="text" name="date_first_seen" value="<?php echo $value; ?>" required="" class="form-control form-control-sm required date valid" placeholder="dd/mm/yyyy" id="DR_WCMC-element-13">
                    </td>
                </tr>
                <tr>
                    <td>Injury/disease is consistent with patient\'s description of cause?</td>
                    <td>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio41" name="Contribute" value="Yes" <?php echo (strstr($a_medical_cert->work_is_factor, "Yes") ? "checked='checked'" : ""); ?> />
                            <label class="form-check-label" for="radio41">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio42" name="Contribute" value="No" <?php echo (strstr($a_medical_cert->work_is_factor, "No") ? "checked='checked'" : ""); ?> />
                            <label class="form-check-label" for="radio42">No</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio43" name="Contribute" value="Unknown" <?php echo (strstr($a_medical_cert->work_is_factor, "Unknown") || !$a_medical_cert->work_is_factor ? "checked='checked'" : ""); ?> />
                            <label class="form-check-label" for="radio43">Unknown</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>How is the injury/disease related to work?</td>
                    <td>
                        <textarea rows="4" cols="30" name="InjuryDetails" required="" class="required valid form-control form-control-sm" id="DR_WCMC-element-17"><?php echo $a_patient->injury_desc; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Detail any pre-existing factors which may be relevant to this condition:</td>
                    <td>
                        <textarea rows="4" cols="30" name="pre_existing_factors" id="DR_WCMC-element-19" class="valid form-control form-control-sm"><?php echo $a_medical_cert->pre_existing_factors; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br />
    <h6>Management Plan for this Period</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Treatment/medication type and duration.<br />Duration: short term =&lt; 6 weeks,<br />medium term = 6-12 weeks,<br />long term =&gt; 12 weeks.</td>
                <td>
                    <textarea rows="4" cols="30" name="MPlan" id="DR_WCMC-element-21" class="valid required form-control form-control-sm"><?php echo $a_medical_cert->manag_plan; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>Referral to another health care provider.<br />Provide details of provider and service requested, duration and frequency when relevant:</td>
                <td>
                    <textarea rows="4" cols="30" name="referral" id="DR_WCMC-element-21" class="valid form-control form-control-sm"><?php echo $a_medical_cert->referral; ?></textarea>
                </td>
            </tr>
        </table>
    </div>

    <br />
    <h6>Capacity for Employment &ndash; please consider the health benefits of work when completing this section</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Do you require a copy of the position description/work duties?</td>
                <td>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio5" name="post_desc" value="Yes" <?php echo (strstr($a_medical_cert->post_desc, "Yes") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio5">Yes</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio6" name="post_desc" value="No" <?php echo (strstr($a_medical_cert->post_desc, "No") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio6">No</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Patient:</td>
                <td>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio7" name="WFit" value="fit" <?php echo (strstr($a_medical_cert->fit_for_work_status, "fit") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio7">Is FIT for pre-injury duties.</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio8" name="WFit" value="suitable" <?php echo (strstr($a_medical_cert->fit_for_work_status, "suitable") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio8">Has capacity for SOME type of employment.</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio9" name="WFit" value="unfit" <?php echo (strstr($a_medical_cert->fit_for_work_status, "unfit") ? "checked='checked'" : ""); ?>>
                        <label class="form-check-label" for="radio9">Has NO current WORK CAPACITY for any employment.</label>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="suitable">
        <h6>Has capacity for SOME type of employment</h6>
        <div class="table-responsive">
            <table class="docForm table">
                <tr>
                    <td class="title">From</td>
                    <td>
                        <?php
                        $value = '';
                        if ($a_medical_cert->suitfrom != '0000-00-00') {
                            $o_date = null;
                            $o_date = new DateTime($a_medical_cert->suitfrom);
                            $value = '';
                            $value = $o_date->format('d/m/Y');
                        }
                        ?>
                        <input type="text" name="SuitFrom" value="<?php echo $value; ?>" class="date suit valid form-control form-control-sm" placeholder="dd/mm/yyyy" id="DR_WCMC-element-30">
                    </td>
                </tr>
                <tr>
                    <td class="title">To</td>
                    <td>
                        <?php
                        $value = '';
                        if ($a_medical_cert->suitto != '0000-00-00') {
                            $o_date = null;
                            $o_date = new DateTime($a_medical_cert->suitto);
                            $value = '';
                            $value = $o_date->format('d/m/Y');
                        }
                        ?>
                        <input type="text" name="SuitTo" value="<?php echo $value; ?>" class="date suit valid form-control form-control-sm" placeholder="dd/mm/yyyy" id="DR_WCMC-element-32">
                    </td>
                </tr>
                <tr>
                    <td>Duration</td>
                    <td class="field">
                        <div class="control-group">
                            <select name="i1" class="suit required form-select form-select-sm" id=" DR_WCMC-element-34">
                                <option value="">Please select (hours per day)</option>
                                <?php
                                $a_options = array();
                                for ($i = 1; $i <= 12; $i++) {
                                    $s_posfix = ' hours per day';
                                    if ($i == 1) {
                                        $s_posfix = ' hour per day';
                                    }
                                    $a_options[$i] = $i . $s_posfix;
                                }
                                foreach ($a_options as $key => $val) {
                                    $selected = "";
                                    if ($key == $a_medical_cert->has_cap_for_duration)
                                        $selected = "selected='selected'";

                                    echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="control-group">
                            <select name="i2" class="suit required form-select form-select-sm" id="DR_WCMC-element-36">
                                <option value="">Please select (days per week)</option>
                                <?php
                                $a_options = array();
                                for ($i = 1; $i <= 12; $i++) {
                                    $s_posfix = ' hours per day';
                                    if ($i == 1) {
                                        $s_posfix = ' hour per day';
                                    }
                                    $a_options[$i] = $i . $s_posfix;
                                }
                                foreach ($a_options as $key => $val) {
                                    $selected = "";
                                    if ($key == $a_medical_cert->has_cap_for_duration_days)
                                        $selected = "selected='selected'";

                                    echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="unfit">
        <h6>Has NO current WORK CAPACITY for any employment</h6>
        <div class="table-responsive">
            <table class="docForm table">
                <tr>
                    <td class="title">From</td>
                    <td>
                        <?php
                        $value = '';
                        if ($a_medical_cert->unfitfrom != '0000-00-00') {
                            $o_date = null;
                            $o_date = new DateTime($a_medical_cert->unfitfrom);
                            $value = '';
                            $value = $o_date->format('d/m/Y');
                        }
                        ?>
                        <input type="text" name="UnfitFrom" value="<?php echo $value; ?>" class="form-control form-control-sm date unfi required" placeholder="dd/mm/yyyy" id="DR_WCMC-element-37">
                    </td>
                </tr>
                <tr>
                    <td class="title">To</td>
                    <td>
                        <?php
                        $value = '';
                        if ($a_medical_cert->unfitto != '0000-00-00') {
                            $o_date = null;
                            $o_date = new DateTime($a_medical_cert->unfitto);
                            $value = '';
                            $value = $o_date->format('d/m/Y');
                        }
                        ?>
                        <input type="text" name="UnfitTo" value="<?php echo $value; ?>" class="form-control form-control-sm date unfi required" placeholder="dd/mm/yyyy" id="DR_WCMC-element-39">
                    </td>
                </tr>
                <tr>
                    <td class="title">If no current work capacity, estimated time to return to any type of employment:</td>
                    <td>
                        <textarea rows="4" cols="30" name="est_return_to_work" class="unfi vsml form-control form-control-sm" id="DR_WCMC-element-41"><?php echo $a_medical_cert->est_return_to_work; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="unfitsuit">
        <div class="table-responsive">
            <table class="docForm table">
                <tr>
                    <td class="title">Factors delaying recovery:</td>
                    <td>
                        <textarea rows="4" cols="30" rows="3" name="fact_delay" class="exsml form-control form-control-sm" id="DR_WCMC-element-41"><?php echo $a_medical_cert->fact_delay; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="title">Do you recommend referral to workplace rehabilitation provider?</td>
                    <td>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio11" name="AssReq" value="Yes" <?php echo (strstr($a_medical_cert->assreq, "Yes") ? "checked='checked'" : ""); ?> />
                            <label class="form-check-label" for="radio11">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="radio12" name="AssReq" value="No" <?php echo (strstr($a_medical_cert->assreq, "No") ? "" : "checked='checked'"); ?> />
                            <label class="form-check-label" for="radio12">No</label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br />
    <h6>Capacity &ndash; If the patient is fit for pre-injury duties this section does not need to be completed. For all other patients please consider activities of daily living currently being performed</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Lifting/carrying capacity</td>
                <td class="field">
                    <select name="i3" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-47">
                        <?php
                        $a_options = array();
                        $a_options = array(
                            'Unrestricted' => 'Unrestricted',
                            'As tolerated' => 'As tolerated',
                            'No lifting advised' => 'No lifting advised'
                        );
                        for ($i = 1; $i <= 30; $i++) {
                            $a_options[$i . 'kg'] = $i . 'kg';
                        }
                        foreach ($a_options as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_liftingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sitting tolerance</td>
                <td class="field">
                    <select name="i5" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-49">
                        <?php
                        $a_options = array();
                        $a_options['Unrestricted'] = 'Unrestricted';
                        $a_options['Minimize'] = 'Minimize';
                        $a_options['As tolerated'] = 'As tolerated';
                        $a_options['No Sitting'] = 'No Sitting Advised';
                        $a_options['5 min'] = '5 min';
                        $a_options['10 min'] = '10 min';
                        $a_options['15 min'] = '15 min';
                        $a_options['20 min'] = '20 min';
                        $a_options['25 min'] = '25 min';
                        $a_options['30 min'] = '30 min';
                        $a_options['45 min'] = '45 min';
                        $a_options['60 min'] = '60 min';
                        $a_options['90 min'] = '90 min';
                        $a_options['2 hrs'] = '2 hrs';
                        $a_options['3 hrs'] = '3 hrs';
                        $a_options['4 hrs'] = '4 hrs';
                        $a_options['5 hrs'] = '5 hrs';
                        $a_options['6 hrs'] = '6 hrs';
                        $a_options['7 hrs'] = '7 hrs';
                        $a_options['8 hrs'] = '8 hrs';
                        foreach ($a_options as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_sittingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Standing tolerance</td>
                <td class="field">
                    <select name="i9" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-51">
                        <?php
                        $a_options = array();
                        $a_options['Unrestricted'] = 'Unrestricted';
                        $a_options['No standing or walking advised'] = 'No standing or walking advised';
                        $a_options['No standing or walking on uneven ground'] = 'No standing or walking on uneven ground';
                        $a_options['As tolerated'] = 'As tolerated';
                        $a_options['5 min'] = '5 min';
                        $a_options['10 min'] = '10 min';
                        $a_options['15 min'] = '15 min';
                        $a_options['20 min'] = '20 min';
                        $a_options['25 min'] = '25 min';
                        $a_options['30 min'] = '30 min';
                        $a_options['45 min'] = '45 min';
                        $a_options['60 min'] = '60 min';
                        $a_options['90 min'] = '90 min';
                        foreach ($a_options as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_standingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Pushing/pulling ability</td>
                <td class="field">
                    <select name="i4" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-53">
                        <?php
                        $a_options = array();
                        $a_options = array(
                            'Unrestricted' => 'Unrestricted',
                            'As tolerated' => 'As tolerated',
                            'No pushing/pulling advised' => 'No pushing/pulling advised'
                        );
                        for ($i = 1; $i <= 30; $i++) {
                            $a_options[$i . 'kg'] = $i . 'kg';
                        }
                        foreach ($a_options as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_walkingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Bending/twisting/squatting ability</td>
                <td class="field">
                    <select name="i6" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-55">
                        <?php
                        $a_options = array(
                            'Unrestricted' => 'Unrestricted',
                            'As tolerated' => 'As tolerated',
                            'No bending/twisting/squatting advised' => 'No bending/twisting/squatting advised'
                        );
                        foreach ($a_options as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_keyingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Driving ability</td>
                <td class="field">
                    <select name="i7" class="unfisuit form-select form-select-sm" id="DR_WCMC-element-57">
                        <?php
                        $a_options2 = array();
                        $a_options2['Unrestricted'] = 'Unrestricted';
                        $a_options2['Avoid clutching/manual vehicles'] = 'Avoid clutching/manual vehicles';
                        $a_options2['No driving advised'] = 'No driving advised';
                        $a_options2['No driving on uneven ground'] = 'No driving on uneven ground';
                        $a_options2['As tolerated'] = 'As tolerated';
                        $a_options2['5 min'] = '5 min';
                        $a_options2['10 min'] = '10 min';
                        $a_options2['15 min'] = '15 min';
                        $a_options2['20 min'] = '20 min';
                        $a_options2['25 min'] = '25 min';
                        $a_options2['30 min'] = '30 min';
                        $a_options2['45 min'] = '45 min';
                        $a_options2['60 min'] = '60 min';
                        $a_options2['90 min'] = '90 min';
                        $a_options2['2 hrs'] = '2 hrs';
                        $a_options2['3 hrs'] = '3 hrs';
                        $a_options2['4 hrs'] = '4 hrs';
                        $a_options2['5 hrs'] = '5 hrs';
                        $a_options2['6 hrs'] = '6 hrs';
                        $a_options2['7 hrs'] = '7 hrs';
                        $a_options2['8 hrs'] = '8 hrs';
                        foreach ($a_options2 as $key => $val) {
                            $selected = "";
                            if ($key == $a_medical_cert->has_cap_for_travellingupto)
                                $selected = "selected='selected'";

                            echo "<option value='" . $key . "' $selected>" . $val . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <br />
    <h6>Other capabilities or restrictions</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Other (please specify) eg psychological considerations, keep wound clean and dry.</td>
                <td>
                    <div class="form-check">
                        <input type="radio" q class="form-check-input" id="radio31" name="OTHER_RESTRICTIONS" value="OTHER" checked>
                        <label class="form-check-label" for="radio31">Yes</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio32" name="OTHER_RESTRICTIONS" value="No">
                        <label class="form-check-label" for="radio32">No</label>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="other">
        <h6>&nbsp;</h6>
        <div class="table-responsive">
            <table class="docForm table">
                <?php
                $a_values = array();
                $a_values = explode(',', $a_medical_cert->other_restrictions_details);
                foreach ($a_values as $k => $v) {
                    $a_values[$k] = trim($v);
                }
                ?>
                <tr>
                    <td class="title">No work above</td>
                    <td class="field">
                        <?php
                        $a_options = array(
                            'work above shoulder ht' => 'Shoulder height',
                            'work above chest ht' => 'Chest height',
                            'work above waist ht' => 'Waist height',
                        );
                        $index = 1;
                        foreach ($a_options as $key => $val) {
                            $checked = "";
                            foreach ($a_values as $opt_key => $opt_val) {
                                if (strstr($opt_val, $key)) {
                                    $checked = "checked";
                                }
                            }
                            echo '<div class="form-check">
<input type="checkbox" class="form-check-input" id="DR_WCMC-element-61-' . ++$index . '" name="OTH[]" value="' . $key . '" ' . $checked . '>
<label class="form-check-label" for="DR_WCMC-element-61-' . $index . '">' . $val . '</label>
</div>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="title">Avoid / minimise</td>
                    <td class="field">
                        <?php
                        $a_options = array(
                            'stairs' => 'Stairs',
                            'ladders' => 'Ladders',
                            'steps' => 'Steps',
                            'walking uneven ground' => 'Walking on uneven ground',
                        );
                        $index = 1;
                        foreach ($a_options as $key => $val) {
                            $checked = "";
                            foreach ($a_values as $opt_key => $opt_val) {
                                if (strstr($opt_val, $key)) {
                                    $checked = "checked";
                                }
                            }
                            echo '<div class="form-check"><input class="form-check-input" type="checkbox" id="DR_WCMC-element-63-' . ++$index . '" name="OTH[]" value="' . $key . '" ' . $checked . '><label for="DR_WCMC-element-63-' . $index . '" class="form-check-label">' . $val . '</label></div>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="title">Lifting restriction applies to</td>
                    <td class="field">
                        <?php
                        $a_options = array(
                            'LS lifting' => 'Left side',
                            'RS lifting' => 'Right side'
                        );
                        $inex = 1;
                        foreach ($a_options as $key => $val) {
                            $checked = "";
                            foreach ($a_values as $opt_key => $opt_val) {
                                if (strstr($opt_val, $key)) {
                                    $checked = "checked";
                                }
                            }

                            // echo '<label class="checkbox"> <input id="DR_WCMC-element-65-' . $index++ . '" type="checkbox" name="OTH[]" class="atext" value="' . $key . '" ' . $checked . '>' . $val . ' </label>';
                            echo '<div class="form-check"><input class="form-check-input" type="checkbox" id="DR_WCMC-element-65-' . ++$inex . '" name="OTH[]" value="' . $key . '" ' . $checked . '><label for="DR_WCMC-element-65-' . $inex . '" class="form-check-label">' . $val . '</label></div>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="title">Other</td>
                    <td class="field">
                        <textarea rows="4" cols="30" name="OTH_TXT" class="sml otext form-control form-control-sm" id="DR_WCMC-element-67"><?php echo $a_medical_cert->other_restrictions_other; ?></textarea>
                        <p><b>Historic information from last certificate:</b><br />&quot;<?php echo $a_medical_cert->other_restrictions_other; ?>&quot;</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <br />
    <h6>Review date</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Next review date:<br />If greater than 28 days, please provide clinical reasoning in comments.</td>
                <td>
                    <?php
                    $value = '';
                    if ($a_medical_cert->fitness_review_date != '0000-00-00') {
                        $o_date = null;
                        $o_date = new DateTime($a_medical_cert->fitness_review_date);
                        $value = '';
                        $value = $o_date->format('d/m/Y');
                    }
                    ?>
                    <input type="text" name="FReview" value="<?php echo $value; ?>" class="date unfisuit form-control form-control-sm" placeholder="dd/mm/yyyy" id="DR_WCMC-element-69" />
                </td>
            </tr>
            <tr>
                <td>Comments:</td>
                <td>
                    <textarea rows="4" cols="30" name="comment" class="sml form-control form-control-sm" id="DR_WCMC-element-71"><?php echo $a_medical_cert->comment; ?></textarea>

                </td>
            </tr>
        </table>
    </div>
    <?php
    if (isDoctor()) {
        $doctorName = "Dr " . $user['Firstname'] . " " . $user['Lastname'];
        $doctorLocation = $user['LocationName'];
    } else {
        $doctorName = $a_patient->doctor_name;
        $doctorLocation = $a_patient->doctor_location;
    }
    ?>
    <input name='Doctor' value='<?php echo $doctorName; ?>' type=hidden />
    <input name='Location' value='<?php echo $doctorLocation; ?>' type=hidden />

    <br />
    <h6>Medical practitioner details</h6>
    <div class="table-responsive">
        <table class="docForm table">
            <tr>
                <td>Doctors Name:</td>
                <td class="field"><?php echo $doctorName; ?></td>
            </tr>
            <tr>
                <td>Doctors Location:</td>
                <td class="field"><?php echo $doctorLocation; ?>
                </td>
            </tr>
            <tr>
                <td>Please tick if you agree to be the nominated treating doctor for the ongoing management of this worker\'s injury and return to work:</td>
                <td>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio51" name="Commit" value="Yes" <?php echo (strstr($a_patient->doctor_agrees, "Yes") ? "checked='checked'" : ""); ?> />
                        <label class="form-check-label" for="radio51">Yes</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio52" name="Commit" value="No" <?php echo (strstr($a_patient->doctor_agrees, "No") ? "checked='checked'" : ""); ?> />
                        <label class="form-check-label" for="radio52">No</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>PDF options:</td>
                <td>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio61" name="output" value="all" <?php echo (strstr("all", "all") ? "checked='checked'" : ""); ?> />
                        <label class="form-check-label" for="radio61">Output the whole form.</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="radio62" name="output" value="page2" <?php echo (strstr("all", "page2") ? "checked='checked'" : ""); ?> />
                        <label class="form-check-label" for="radio62">Output just page 2 of the form.</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <label><button type="submit" class="btn btn-sm btn-primary" id="DR_WCMC-element-79" value="Save to Form">Save to Form</button>
                    </label>
                </td>
            </tr>
        </table>
    </div>
</form>
<?php

echo getFooter();
