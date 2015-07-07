<?php
include_once('../lib/TieredConfiguration.php');

use configuration\adapter\FlatfileAdapter as FlatfileAdapter;
use configuration\TieredConfiguration as TieredConfiguration;

$config = new TieredConfiguration(array(new FlatfileAdapter('tier1.txt'), new FlatfileAdapter('tier2.txt')));

$array = $config->flatten();
var_dump($array);
?>
