<?php
// includes for all files in namespace
$basename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'configuration' . DIRECTORY_SEPARATOR;
include_once($basename . 'ConfigurationReader.php');
include_once($basename . 'TieredConfiguration.php');
include_once($basename . 'adapter' . DIRECTORY_SEPARATOR . 'IAdapter.php');
include_once($basename . 'adapter' . DIRECTORY_SEPARATOR . 'JSONAdapter.php');
include_once($basename . 'exception' . DIRECTORY_SEPARATOR . 'FileNotFoundException.php');
include_once($basename . 'exception' . DIRECTORY_SEPARATOR . 'FormatException.php');
include_once($basename . 'util' . DIRECTORY_SEPARATOR . 'MultilevelCache.php');
include_once($basename . 'util' . DIRECTORY_SEPARATOR . 'TreeWalker.php');
?>