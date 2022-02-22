<?php
$b_admin_area = 1;
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

if (isset($_POST['isnew'])) {
    if ($_POST['isnew'] == '1') {
        header('Location: ' .  SECURE_URL . WORKERS_COMP_MANAGE_DR_FORM);
        exit();
    }
}

$user = (isset($_SESSION['User']) ? $_SESSION['User'] : null);
$webuser = new user;

if (isset($_POST['Submit'])) {

    $webuser->id = $_POST['userid'];
    $webuser->username = $_POST['username'];
    $webuser->password = $_POST['password'];
    $webuser->firstname = $_POST['firstname'];
    $webuser->lastname = $_POST['lastname'];
    $webuser->usertype = $_POST['usertype'];

    if (isset($_POST['active'])) {
        $webuser->active = ($_POST['active'] == "Y" ? "Y" : "N");
    } else {
        $webuser->active = $_POST['active'] = 'N';
        if ($_POST['usertype'] == 'ADMIN') {
            $webuser->active = $_POST['active'] = 'Y';
        }
    }

    $webuser->id = $webuser->Save();
    if ($webuser->id > 0) {

        if ($webuser->usertype == 'DOCTOR' || $webuser->usertype == 'ADMIN') {
            $db = getDBConnection();

            $stmt = $db->prepare("DELETE FROM medicare_details WHERE UserId=?");
            $stmt->bind_param("i", $webuser->id);
            $stmt->execute();
            $stmt->close();

            foreach (array_keys($_POST) as $key) {
                if (substr($key, 0, 4) == 'MPN_') {
                    $split = substr($key, 4, strlen($key));
                    $locationId = $split[0];
                    $mpn = $_POST[$key];
                    $stmt = $db->prepare("INSERT medicare_details( UserId, LocationId, MPN ) Values(?,?,?)");
                    $stmt->bind_param("iis", $webuser->id, $locationId, trim($mpn));
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $db->close();
        }
        header("Location: search_Admin.php");
        die();
    }
} else {
    $webuser->id = (isset($_GET['userid']) ? $_GET['userid'] : null);
    $webuser->Load();
}
// check if user is editing their own record. Need to avoid locking themselves out.
$editingCurrentUser = ($user['UserId'] == $webuser->id);

$js = "
    <script type=\"text/javascript\">
        $(document).ready( function(){
            $('#password').keyup( setPasswordConfirm );
            $('#usertype').change( setLocationOptions );
            setPasswordConfirm();
            setLocationOptions();
        });

        function setPasswordConfirm()
        {
                if( $('#userid').val() == '0' || $('#password').val() != '' ) $('#confirmPasswordTR').show();
                else { $('#confirmPasswordTR').hide(); $('#confirmPassword').val(''); }
        }

        function setLocationOptions()
        {
            if( $('#usertype').val() == 'DOCTOR') $('#doctorLocationTR').show();
            else $('#doctorLocationTR').hide();
        }

        function checkFields()
        {
            if( $('#username').val().length < 1  ){ alert( 'Please enter a username' ); return false; }
            if( $('#password').val().length != 0 )
            {
                if( $('#password').val().length < 6 ){ alert( 'For security reasons, passwords must be a minimum of 6 characters' ); return false; }
                if( $('#password').val() != $('#confirmPassword').val()){ alert( 'The password entered does not match the confirmation. Please retype the password in both fields.' ); return false; }
            }
            return true;
        }
        $(document).ready( function(){
            $('#password').keyup( setPasswordConfirm );
            $('#usertype').change( setLocationOptions );
            setPasswordConfirm();
            setLocationOptions();
        });

        function setPasswordConfirm()
        {
                if( $('#userid').val() == '0' || $('#password').val() != '' ) $('#confirmPasswordTR').show();
                else { $('#confirmPasswordTR').hide(); $('#confirmPassword').val(''); }
        }

        function setLocationOptions()
        {
            if( $('#usertype').val() == 'DOCTOR') $('#doctorLocationTR').show();
            else $('#doctorLocationTR').hide();
        }

        function checkFields()
        {
            if( $('#username').val().length < 1  ){ alert( 'Please enter a username' ); return false; }
            if( $('#password').val().length != 0 )
            {
                if( $('#password').val().length < 6 ){ alert( 'For security reasons, passwords must be a minimum of 6 characters' ); return false; }
                if( $('#password').val() != $('#confirmPassword').val()){ alert( 'The password entered does not match the confirmation. Please retype the password in both fields.' ); return false; }
            }
            return true;
        }
    </script>";


echo getHeader('User management', $s_menu, $js);
?>
<form action="edit_user.php" method="post" name="EditUserForm" onsubmit="return checkFields()">
    <input class="form-control form-control-sm" name="userid" id="userid" value="<?php echo $webuser->id ?>" type="hidden" />
    <h6>User record</h6>
    <div class="table-responsive">
        <table border="0" cellspacing="0" cellpadding="5" width="100%" class="table">
            <tr>
                <td width="128">Username</td>
                <td width="389"><input class="form-control form-control-sm" type="text" id="username" name="username" size="30" value="<?php echo $webuser->username ?>" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <input class="form-control form-control-sm" type="password" id="password" name="password" size="30" value="" />
                    <?php if ($webuser->id != "") { ?><div id="passwordHelpBlock" class="form-text">
                            (leave blank to retain existing password)
                        </div><?php } ?>
                </td>
            </tr>
            <tr id="confirmPasswordTR">
                <td>Confirm Password</td>
                <td><input class="form-control form-control-sm" type="password" id="confirmPassword" name="confirmPassword" size="30" value=""></td>
            </tr>
            <tr>
                <td>First name</td>
                <td><input class="form-control form-control-sm" type="text" name="firstname" size="30" value="<?php echo $webuser->firstname ?>"></td>
            </tr>
            <tr>
                <td>Last name</td>
                <td><input class="form-control form-control-sm" type="text" name="lastname" size="30" value="<?php echo $webuser->lastname ?>"></td>
            </tr>
            <tr>
                <td>User Type</td>
                <td>
                    <select name="usertype" id="usertype" class="form-select form-select-sm">
                        <option value="USER" <?php echo ($webuser->usertype == "USER" ? "Selected" : "") ?>>User</option>
                        <option value="DOCTOR" <?php echo ($webuser->usertype == "DOCTOR" ? "Selected" : "") ?>>Doctor</option>
                        <option value="ADMIN" <?php echo ($webuser->usertype == "ADMIN" ? "Selected" : "") ?>>Administrator</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Is Active</td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check1" name="active" value="Y" <?php echo (($editingCurrentUser || $webuser->active == "Y") ? "checked" : ""); echo ($editingCurrentUser ? "disabled" : "") ?> />
                    </div>
                </td>
            </tr>
            <tr id="doctorLocationTR" valign="top">
                <td>MPN</td>
                <td>
                    <?php
                    $db = getDBConnection();
                    $stmt = $db->prepare('SELECT L.LocationId, L.LocationName, M.MPN
                    FROM locations L
                    left JOIN medicare_details M on L.LocationId = M.LocationId AND M.UserId = ? OR MPN is null
                    WHERE L.lo_deleted = 0');
                    $stmt->bind_param("i", $webuser->id);
                    $stmt->execute();
                    $stmt->bind_result($locationId, $locationName, $mpn);
                    $stmt->store_result();
                    while ($stmt->fetch()) {
                    ?>
                        <div>
                            <div class="LocationName"><?php echo $locationName ?> </div>
                            <div class="MPN">
                                <input class="form-control form-control-sm" name="MPN_<?php echo $locationId ?>" type="text" value="<?php echo $mpn ?>" />
                            </div>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="2" class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm" name="Submit" value="Save">Save</button>
                    <button type="button" class="btn btn-danger btn-sm" name="Cancel" value="Cancel" onclick="window.location='search_Admin.php'">Cancel</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?php
echo getFooter();
