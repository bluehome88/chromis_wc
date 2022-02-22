<?php
$b_user_area = 1;
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

$errorMsg = "";
$user = (isset($_SESSION['User']) ? $_SESSION['User'] : null);

if (isset($_POST['Submit']) && $user != null) {
  $existingPassword = (isset($_POST['existingPassword']) ? $_POST['existingPassword'] : null);
  $newPassword = (isset($_POST['newPassword']) ? $_POST['newPassword'] : null);
  $confirmPassword = (isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null);
  if ($newPassword == $confirmPassword && $newPassword != $existingPassword) {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE `users` SET `Password`=PASSWORD(?) WHERE `Password`=PASSWORD(?) AND `UserId`=?");
    $stmt->bind_param("ssi", $newPassword, $existingPassword, $user['UserId']);
    $stmt->execute() or die($stmt->error);
    $rowcount = $stmt->affected_rows;
    $stmt->close();
    $db->close();
    if ($rowcount > 0) {
      header('Location: ' . getStartPage($user));
      exit;
    } else {
      $errorMsg = "There was an error updating your password. Please confirm you have entered your existing password correctly";
    }
  } else {
    $errorMsg = "The new password entered does not match the confirmation. Please retype the password in both fields.";
  }
}

$js = '
    <script src="./scripts/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#SetPasswordForm").validate({
              submitHandler: function(form) {
                if( $("#existingPassword").val() == $("#newPassword").val()){
                    $(".error-text").html("Your new password cannot be the same as your old one.");
                    return false;
                }
                if( $("#newPassword").val() != $("#confirmPassword").val()){
                    $(".error-text").html( "The new password entered does not match the confirmation. Please retype the password in both fields." );
                    return false;
                }
                $(form).submit();
              }
            });
        });
    </script>';

echo getHeader('Password management', $s_menu, $js);
?>
<div class="error-text"><?php echo $errorMsg ?></div>

<form action="set_password.php" method="post" name="SetPasswordForm" id="SetPasswordForm">
  <h6>Change password for <span class="nav_ur_here"><?php echo $user['Firstname'] . " " . $user['Lastname'] ?></span></h6>
  <div class="table-responsive">
    <table class="table" border="0" cellspacing="0" cellpadding="5" width="100%">
      <tr>
        <td width="50">Current password</td>
        <td width="50"><input type="password" id="existingPassword" class="form-control form-control-sm" name="existingPassword" value="" required class="required valid" /></td>
      </tr>
      <tr>
        <td>New password</td>
        <td><input type="password" id="newPassword" name="newPassword" value="" required class="required form-control form-control-sm"></td>
      </tr>
      <tr>
        <td>Confirm new password</td>
        <td><input class="form-control form-control-sm" type="password" id="confirmPassword" name="confirmPassword" value="" required class="required"></td>
      </tr>
      <tr>
        <td class="text-center" colspan="2">
          <button type="submit" class="btn btn-sm btn-primary" name="Submit" value="Save">Save</button>
          <button type="submit" class="btn btn-sm btn-secondary" name="Cancel" value="Cancel" onclick="window.location='<?php echo getStartPage($user) ?>'">Cancel</button>
        </td>
      </tr>
    </table>
  </div>
</form>
<?php
echo getFooter();
