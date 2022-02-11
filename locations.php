<?php
$b_admin_area = 1;
require_once('../config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');

if(isset($_POST['isnew'])) {
    if($_POST['isnew']=='1') {
        header('Location: '.  SECURE_URL . WORKERS_COMP_MANAGE_DR_FORM);
        exit();
    }
}
$js  = '    <script src="./scripts/jquery.validate.js"></script>' . "\n";
$js .= '    <script src="./scripts/location-form.js"></script>' . "\n";

echo getHeader('Location administration interface', $s_menu, $js);

$db = getDBConnection();
$o = $db->query(
    'SELECT
    `LocationId`,
    `LocationName`,
    `LocationAddress`,
    `LocationSuburb`,
    `LocationState`,
    `LocationPostcode`,
    `LocationPhone`,
    `LocationFax`
    FROM locations
    WHERE `lo_deleted` = 0');
?>
  <h6>Existing Locations</h6>
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
      <td><b>Location</b></td>
      <td><b>Address</b></td>
      <td><b>Phone</b></td>
      <td><b>Fax</b></td>
      <td width="50" nowrap>&nbsp;</td>
      <td width="50" nowrap>&nbsp;</td>
    </tr>
<?php
    $s = '';
    while ( $r = $o->fetch_assoc() ) {
        $s .= '    <tr>
      <td nowrap="nowrap">'. $r['LocationName'] .'</td>
      <td nowrap="nowrap">'. $r['LocationAddress'] .'<br />'. $r['LocationSuburb'] .' '. $r['LocationState'] .' '. $r['LocationPostcode'] .'</td>
      <td nowrap="nowrap">'. $r['LocationPhone'] .'</td>
      <td nowrap="nowrap">'. $r['LocationFax'] .'</td>
      <td width="50" nowrap><a href="'. SECURE_URL . ADMIN_EDIT_LOCATIONS .'?i='. $r['LocationId']  .'&amp;a=e">Edit</a></td>
      <td width="50" nowrap><a href="'. SECURE_URL . ADMIN_EDIT_LOCATIONS .'?i='. $r['LocationId']  .'&amp;a=d" class="delete">Delete</a></td>
    </tr>';
    }
    echo $s;
?>
    <tr>
      <td align="right" colspan="6"><a href="<?php echo SECURE_URL . ADMIN_EDIT_LOCATIONS .'?a=a'; ?>">Add New Location</a></td>
    </tr>
  </table>
  <p>&nbsp;</p>

<?php

$o->close();

echo getFooter();
