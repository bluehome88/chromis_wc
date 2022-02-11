<?php
$b_admin_area = 1;
require_once('../config.php');
require_once( ROOT_FOLDER . 'lib/header_doc.php');
require_once( ROOT_FOLDER . 'lib/Location.php');
use PFBC\Form;
use PFBC\Element;
use PFBC\Validation;
require_once(ROOT_FOLDER . 'lib/pfbc3.1-php5.3/PFBC/Form.php');

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

$form = new Form('location');
$form->configure(array(
    'prevent' => array('bootstrap', 'jQuery')
));

$form->addElement(new Element\Hidden('i_LocationId', $o_l->i_LocationId));
$form->addElement(new Element\Hidden('a', 'i'));
$form->addElement(new Element\HTML('<h6>&nbsp;</h6>'));

$form->addElement(new Element\HTML('
    <table class="docForm">
    <tr>
        <td>Location Name:</td>
        <td>'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationName',
    array(
        'cols'=>40,
        'value'=>$o_l->s_LocationName,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location Address:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationAddress',
    array(
        'cols'=>50,
        'value'=>$o_l->s_LocationAddress,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location Suburb:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationSuburb',
    array(
        'cols'=>40,
        'value'=>$o_l->s_LocationSuburb,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location Postcode:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationPostcode',
    array(
        'cols'=>30,
        'value'=>$o_l->s_LocationPostcode,
        'required' => 'required',
        'class' => 'required number'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location State:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationState',
    array(
        'cols'=>40,
        'value'=>$o_l->s_LocationState,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location Phone:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationPhone',
    array(
        'cols'=>40,
        'value'=>$o_l->s_LocationPhone,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>Location Fax:</td>
        <td>
'));

$form->addElement(new Element\Textbox(
    '',
    's_LocationFax',
    array(
        'cols'=>40,
        'value'=>$o_l->s_LocationFax,
        'required' => 'required',
        'class' => 'required'
    )
));

$form->addElement(new Element\HTML('
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
'));

$form->addElement(new Element\Button(' Save Location '));
$form->addElement(new Element\HTML('</td></tr></table><p>&nbsp;</p>'));

$form->render();

echo getFooter();
