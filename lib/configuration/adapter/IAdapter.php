<?php
namespace configuration\adapter;

/*
 * An adapter reads a configuration from a specific format
 *
 * Author: Bremen Braun
 */
interface IAdapter {

	/* Reads the file (probably passed in through a constructor) and reformats
	 * it into a tree structure, where each node in the tree is a
	 * ConfigurationNode
	 */
	function buildConfigurationTree();
}
?>
