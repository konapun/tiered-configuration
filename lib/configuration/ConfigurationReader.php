<?php
namespace configuration;

/*
 * Interface for something that reads from a configuration file
 *
 * Author: Bremen Braun
 */
interface ConfigurationReader {
	/*
	 * Return a section which is a key with key-value pairs
	 */
	function getSection($key);
	
	/*
	 * Given a key for a configurable option, return its value
	 */
	function getValue($key);
}
?>
