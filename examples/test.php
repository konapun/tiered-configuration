<?php
include_once('../lib/TieredConfiguration.php');

use configuration\adapter\JSONAdapter as JSONAdapter;
use configuration\TieredConfiguration as TieredConfiguration;

$config = new TieredConfiguration(array(new JSONAdapter('global.json'), new JSONAdapter('specific.json')));
//$section = $config->getSection('server');
$address = $config->getValue('name');
print "$address\n";
?>
