<?php
echo '<pre>';
echo 'pdftk workcover-certificate-capacity-1300.pdf test.fdf output test123.pdf<br>' ;
print_r( passthru('pdftk workcover-certificate-capacity-1300.pdf wc61846a62ba2e8.fdf output test123.pdf') );
print_r( passthru('which pdftk') );
print_r( passthru('pdftk --version') );
echo '</pre>';