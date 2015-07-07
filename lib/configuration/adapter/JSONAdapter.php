<?php
namespace configuration\adapter;

use configuration\adapter\IAdapter as IAdapter;
use configuration\tree\TreeBuilder as TreeBuilder;
use configuration\exception\FileNotFoundException as FileNotFoundException;
use configuration\exception\FormatException as FormatException;

/*
 * An adapter to read configuration from JSON
 *
 * Author: Bremen Braun
 */
class JSONAdapter implements IAdapter {
	private $fileContents;

	function __construct($jsonFile) {
		$contents = file_get_contents($jsonFile);
		if ($contents === false) {
			throw new FileNotFoundException("Can't locate file '$jsonFile'");
		}
		$this->fileContents = $contents;
	}

	/*
	 * Return the contents of the JSON file as a configuration tree
	 */
	function buildConfigurationTree() {
		$tree = json_decode($this->fileContents, true);
		if ($tree === null) {
			throw new FormatException("Can't parse file - not a JSON file");
		}
		$treeBuilder = new TreeBuilder();
		return $treeBuilder->buildTree($tree);
	}
}
?>
