<?php
require_once('secure.php');
echo getHeader('Medical certificate login');
?>
<h6>Login to either commence, revise or complete a medical certificate</h6>
<form action="<?php echo SECURE_URL . LOGIN_FORM ?>" method="post">
  <input type="hidden" name="authprocess" value="login" />
  <div class="table-responsive">
    <table class="table">
      <tr>
        <td width="254">Username</td>
        <td width="389"><label>
            <input class="form-control form-control-sm" type="text" name="uname" id="username" />
          </label></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><label>
            <input class="form-control form-control-sm" type="password" name="pword" id="password" />
          </label></td>
      </tr>
      <tr>
        <td class="text-center" colspan="2"><label>
            <button type="submit" class="btn btn-primary btn-sm" name="Login" id="Login" value="Submit">Submit</button>
          </label></td>
      </tr>
    </table>
  </div>
</form>
<?php echo getFooter(); ?>