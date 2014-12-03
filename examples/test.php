<?php
include_once('../lib/TieredConfiguration.php');

use configuration\adapter\JSONAdapter as JSONAdapter;
use configuration\TieredConfiguration as TieredConfiguration;
use configuration\validator\Validator as Validator;

$config = new TieredConfiguration(array(new JSONAdapter('global.json'), new JSONAdapter('specific.json')));
$validator = new Validator(new JSONAdapter('onus.json'));
//$validator->validate($config);

$envName = $config->getSection('environment')->getValue('name');
$projectName = $config->getSection('project')->getValue('name');
//$projectRoot = $config->getSection('project')->getValue('root');
print "Using environment $envName\n";
print "Using project name $projectName\n";
//print "$projectRoot\n";
?>
