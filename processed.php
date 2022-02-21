<?php
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');

$s_content = getHeader('Client record search', $s_menu, $js);

$s_content .= '<p>&nbsp;</p>
  <p>This medical certificate has been updated and saved.</p>
  <h4>What would you like to do now?</h4>
  <p>
    1) <a href="' . SECURE_URL . SEARCH_DOCTOR . '">Continue</a> with another medical certificate<br />
    2) <a href="' . SECURE_URL . LOGOUT . '">Log out</a>.
</p>
<p>&nbsp;</p>';

$s_content .= getFooter();

echo $s_content;
