<?php
require_once('secure.php');
echo getHeader('Medical certificate login');
?>
                    <h6>Login  to either commence, revise or complete a medical certificate</h6>
                    <form action="<?php echo SECURE_URL . LOGIN_FORM ?>" method="post">
                        <input type="hidden" name="authprocess" value="login" />
                        <table border="0" cellspacing="0" cellpadding="5" width="100%">
                          <tr>
                            <td width="254">Username</td>
                            <td width="389"><label>
                              <input type="text" name="uname" id="username" />
                            </label></td>
                          </tr>
                          <tr>
                            <td width="254">Password</td>
                            <td width="389"><label>
                              <input type="password" name="pword" id="password" />
                            </label>      <br /></td>
                          </tr>
                          <tr>
                            <td width="254">&nbsp;</td>
                            <td width="389" ><label>
                              <input type="submit" name="Login" id="Login" value="Submit" />
                            </label></td>
                          </tr>
                        </table>
                    </form>
<?php echo getFooter(); ?>
