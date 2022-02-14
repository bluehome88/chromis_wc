<?php
$b_admin_area = 1;
require_once('../config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');
require_once( ROOT_FOLDER . 'lib/Location.php');

$o_l = new Location;
if( isset($_REQUEST['a'])) {
    switch($_REQUEST['a']){
        case 'd': //delete
            $o_l->i_LocationId = (int)$_GET['i'];
            $o_l->load();
            $o_l->b_lo_deleted = 1;
            $o_l->save();
            header('Location: '. SECURE_URL . ADMIN_LOCATIONS . '?m=s' );
            exit;

        case 'e': //edit
            $o_l->i_LocationId = (int)$_GET['i'];
            $o_l->load();
            break;

        case 'i': //edit
            $o_l->i_LocationId       = (int)$_POST['i_LocationId'];
            $o_l->s_LocationName     = $_POST['s_LocationName'];
            $o_l->s_LocationAddress  = $_POST['s_LocationAddress'];
            $o_l->s_LocationSuburb   = $_POST['s_LocationSuburb'];
            $o_l->s_LocationState    = $_POST['s_LocationState'];
            $o_l->s_LocationPostcode = $_POST['s_LocationPostcode'];
            $o_l->s_LocationPhone    = $_POST['s_LocationPhone'];
            $o_l->s_LocationFax      = $_POST['s_LocationFax'];
            $o_l->b_lo_deleted       = 0;
            $o_l->save();
            header('Location: '. SECURE_URL . ADMIN_LOCATIONS . '?m=s' );
            exit;
    }
} else {
    header('Location: '. SECURE_URL . ADMIN_LOCATIONS );
    exit;
}

$js  = '    <script src="./scripts/jquery.validate.min.js"></script>' . "\n";
$js .= '    <script src="./scripts/location-form.js"></script>' . "\n";

echo getHeader('Location add / edit interface', $s_menu, $js, true);
?>
<form action="locations-edit.php" id="location" method="post" class="form-horizontal" novalidate="novalidate">
    <input name='i_LocationId' value='<?php echo $o_l->i_LocationId;?>' type=hidden />
    <input name='a' value='i' type=hidden />

    <table class="docForm">
        <tr>
            <td>Location Name:</td>
            <td>
                <input type="text" name="s_LocationName" value="<?php echo $o_l->s_LocationName; ?>" required="" class="required valid" id="location-element-4">
            </td>
        </tr>
        <tr>
            <td>Location Address:</td>
            <td>
                <input type="text" name="s_LocationAddress" value="<?php echo $o_l->s_LocationAddress; ?>" required="" class="required valid" id="location-element-6">
            </td>
        </tr>
        <tr>
            <td>Location Suburb:</td>
            <td>
                <input type="text" name="s_LocationSuburb" value="<?php echo $o_l->s_LocationSuburb; ?>" required="" class="required valid" id="location-element-8">
            </td>
        </tr>
        <tr>
            <td>Location Postcode:</td>
            <td>
                <input type="text" name="s_LocationPostcode" value="<?php echo $o_l->s_LocationPostcode; ?>" required="" class="required number" id="location-element-10">
            </td>
        </tr>
        <tr>
            <td>Location State:</td>
            <td>
                <input type="text" name="s_LocationState" value="<?php echo $o_l->s_LocationState; ?>" required="" class="required" id="location-element-12">
            </td>
        </tr>
        <tr>
            <td>Location Phone:</td>
            <td>
                <input type="text" name="s_LocationPhone" value="<?php echo $o_l->s_LocationPhone; ?>" required="" class="required" id="location-element-14">
            </td>
        </tr>
        <tr>
            <td>Location Fax:</td>
            <td>
                <input type="text" name="s_LocationFax" value="<?php echo $o_l->s_LocationFax; ?>" required="" class="required" id="location-element-16">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <label><button type="submit" class="btn btn-primary" id="location-element-18" value="Save Location">Save Location</button>
            </td>
        </tr>
    </table>
</form>


<?php
echo getFooter();
