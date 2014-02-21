<?php
namespace configuration;

use configuration\ConfigurationReader as ConfigurationReader;
use configuration\util\MultilevelCache as MultilevelCache;
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
		$level = count($adapters)-1;
		$cache = new MultilevelCache(count($adapters));
		foreach ($adapters as $adapter) {
			$treeWalker = new TreeWalker($adapter->buildConfigurationTree());
			$treeWalker->walk(TreeWalker::BF, function($node) use ($cache, $level) {
				$cache->cache($node->key, $node->value, $level);
			});
			$level--;
		}
		$this->cache = $cache;
	}
	
	function getSection($sectionName) {
		// TODO
	}
	
	function getValue($key) {
		return $this->cache->getValue($key);
	}
}
?>
