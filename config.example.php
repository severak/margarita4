<?php
// path to database
$config['database'] = 'data/kyselo.sqlite';
// adminer password
$config['adminer_password'] = 'jelen';
// site base url
$config['base_url'] = 'localhost';
// secret random seed
$config['secret'] = 'jhjhlkjhkhuiuyuiy';

$config['net']['pid']['logo'] = '/st/img/pid.jpg';
$config['net']['pid']['background'] = '/st/img/praha-bg.jpg';
$config['net']['pid']['name'] = 'Pražská integrovaná doprava';
$config['net']['pid']['copyright'] = '<a href="http://opendata.praha.eu/dataset/dpp-jizdni-rady">Dopravní podnik hl. m. Prahy a.s.</a>';

return $config;