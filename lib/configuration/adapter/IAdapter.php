<?php
namespace configuration\adapter;

/*
 * An adapter reads a configuration from a specific format
 *
 * Author: Bremen Braun
 */
interface IAdapter {

	/* Reads the file (probably passed in through a constructor) and decomposes
	 * it as an array structure
	 */
	function buildConfigurationTree();
}
?>
