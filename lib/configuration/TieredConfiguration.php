<?php
namespace configuration;

use configuration\ConfigurationReader as ConfigurationReader;
use configuration\util\MultilevelTreeCache as MultilevelTreeCache;
use configuration\util\TreeWalker as TreeWalker;

/*
 * Cascading configuration format
 *
 * Author: Bremen Braun
 */
class TieredConfiguration implements ConfigurationReader {
	private $cache;
	
	/* 
	 * Read in a configuration from an array of adapters sources, where adapters
	 * closer to the end of the array have higher priority
	 */
	function __construct($adapters) {
		$level = 0;
		$cache = new MultilevelTreeCache(count($adapters));
		foreach ($adapters as $adapter) {
			$cache->cache($adapter->buildConfigurationTree(), $level++);
		}
		$this->cache = $cache;
	}
	
	/*
	 * A section is just a node
	 */
	function getSection($key) {
		$node = $this->cache->getValue($key);
		$cache = new MultilevelTreeCache(1);
		$cache->cache($node, 0);
		$nodeAPI = $this->cloneWithCache($cache);
		return $nodeAPI;
	}
	
	/*
	 * A value for a key may be a section or a variable
	 */
	function getValue($key) {
		$section = $this->cache->getValue($key);
		if (count($section->getChildren()) == 1) {
			return $section->getChild(0)->getData();
		}
		return $this->getSection($key)->getData();
	}
	
	/*
	 * In order to traverse the configuration hierarchy while still maintaining
	 * this API, clones will be returned for partial results
	 */
	private function cloneWithCache($cache) {
		$clone = new self(array());
		$clone->cache = $cache;
		return $clone;
	}
}
?>
