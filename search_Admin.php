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

echo getHeader('User administration interface', $s_menu, $js);

if (!isset($_REQUEST['Surname'])) {
?>
  <form id="SearchForm" method="post" name="SearchForm">
    <h2>New User</h2>
    <p>I want to <a href="<?php echo SECURE_URL . EDIT_USERS_ADMIN; ?>">create a new user, doctor or administrator.</a></p>
    <tbody id="New"></tbody>
    <h2>Existing Users</h2>
    <p>I want to <a href="#" onclick="return searchAllUsers();">view all users</a> or update an existing user, doctor or administrator record.</p>
    <tbody id="Existing">
      <div class="table-responsive">
        <table border="0" cellspacing="0" cellpadding="5" width="100%" class="table">
          <tr>
            <td>Surname</td>
            <td>
              <input name="Surname" type="text" id="inputString" autocomplete="off" onkeyup="adminLookup(this.value);" class="form-control form-control-sm" />
              <div class="suggestionsBox" id="suggestions" style="display: none;">
                <img src="upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                <div class="suggestionList" id="autoSuggestionsList">
                  <ul>&nbsp;</ul>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td colspan="2" class="text-center"><label>
                <button type="submit" class="btn btn-sm btn-primary" name="search" id="search" value="Search">Search</button>
              </label></td>
          </tr>
        </table>
      </div>
  </form>
  <?php
} else {
  $searchname = $_REQUEST['Surname'] . '%';
  $db = getDBConnection();
  $stmt = $db->prepare("SELECT UserId, Username, Firstname, Lastname, UserType, Active FROM users WHERE Lastname LIKE ?");
  $stmt->bind_param("s", $searchname);
  $stmt->execute();
  $stmt->bind_result($userId, $username, $firstname, $lastname, $usertype, $active);
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
  ?>
    <h6>Existing Users</h6>
    <div class="table-responsive">
      <table border="0" cellspacing="0" cellpadding="5" width="100%" class="table">
        <tr>
          <td width="150" nowrap>Username</td>
          <td width="150" nowrap>First name</td>
          <td width="150" nowrap>Last name</td>
          <td width="50" nowrap>Type</td>
          <td width="50" nowrap>Is Active</td>
        </tr>
        <?php
        while ($stmt->fetch()) {
        ?>
          <tr>
            <td><a href="edit_user.php?userid=<?php echo $userId ?>"><?php echo $username ?></a></td>
            <td><?php echo $firstname ?></td>
            <td><?php echo $lastname ?></td>
            <td><?php echo $usertype ?></td>
            <td><?php echo $active ?></td>
          </tr>
        <?php
        }
        ?>
        <tr>
          <td align="right" colspan="5"><a href="edit_user.php">Add New User</a></td>
        </tr>
      </table>
    </div>
    <p>&nbsp;</p>

<?php
  }
  $stmt->close();
  $db->close();
}
echo getFooter();
