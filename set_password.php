<?php
$b_user_area = 1;
require_once('../../etc/config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

    $errorMsg = "";
    $user = ( isset($_SESSION['User']) ? $_SESSION['User'] : null );

    if(isset($_POST['Submit']) && $user != null )
    {
        $existingPassword = ( isset($_POST['existingPassword']) ? $_POST['existingPassword'] : null );
        $newPassword = ( isset($_POST['newPassword']) ? $_POST['newPassword'] : null );
        $confirmPassword = ( isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null );
        if( $newPassword == $confirmPassword && $newPassword != $existingPassword )
        {
            $db = getDBConnection();
            $stmt = $db->prepare("UPDATE `users` SET `Password`=PASSWORD(?) WHERE `Password`=PASSWORD(?) AND `UserId`=?");
            $stmt->bind_param("ssi", $newPassword, $existingPassword, $user['UserId'] );
            $stmt->execute() or die($stmt->error);
            $rowcount = $stmt->affected_rows;
            $stmt->close();
            $db->close();
            if( $rowcount > 0 ){
                header('Location: '.getStartPage($user));
                exit;
            } else{
                 $errorMsg = "There was an error updating your password. Please confirm you have entered your existing password correctly";
            }
        } else {
            $errorMsg = "The new password entered does not match the confirmation. Please retype the password in both fields.";
        }
    }

$js = '<script type="text/javascript">
    function checkFields()
    {
        if( $("#existingPassword").val() == "" ){ alert( "For security reasons, you must provide your existing password" ); return false; }
        if( $("#newPassword").val().length < 6 ){ alert( "For security reasons, passwords must be a minimum of 6 characters" ); return false; }
        if( $("#newPassword").val() != $("#confirmPassword").val()){ alert( "The new password entered does not match the confirmation. Please retype the password in both fields." ); return false; }
        if( $("#existingPassword").val() == $("#newPassword").val()){ alert( "Your new password cannot be the same as your old one." ); return false; }
        return true;
    }
</script>';
echo getHeader('Password management', $s_menu, $js);
?>
    <div class="error-text"><?php echo $errorMsg?></div>

      <form action="set_password.php" method="post" name="SetPasswordForm" onsubmit="return checkFields()">
      <h6>Change  password for <span class="nav_ur_here"><?php echo $user['Firstname']." ".$user['Lastname']?></span></h6>

        <table border="0" cellspacing="0" cellpadding="5" width="100%">
          <tr>
            <td>Current password</td>
            <td><input type="password" id="existingPassword" name="existingPassword" size="30" value="" /></td>
          </tr>
          <tr>
            <td>New password</td>
            <td><input type="password" id="newPassword" name="newPassword"  size="30" value=""></td>
          </tr>
          <tr>
            <td>Confirm new password</td>
            <td><input type="password" id="confirmPassword" name="confirmPassword"  size="30" value=""></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="right">
                <input type="submit" name="Submit" value="Save" />
                <input type="button" name="Cancel" value="Cancel" onclick="window.location='<?php echo getStartPage($user)?>'" />
            </td>
            </tr>
        </table>
      </form>
<?php
echo getFooter();
