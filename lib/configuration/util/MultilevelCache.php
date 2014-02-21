<?php
namespace configuration\util;

/*
 * A cache with n levels, where levels closer to 0 have a higher priority
 *
 * Author: Bremen Braun
 */
class MultilevelCache {
	private $caches = array();
	
	/*
	 * Construct this cache with $nCaches number of levels
	 */
	function __construct($nCaches) {
		for ($i = 0; $i < $nCaches; $i++) {
			array_push($this->caches, array());
		}
	}
	
	/*
	 * Cache an item at a specific cache index (indexes closer to 0 have a
	 * higher priority)
	 */
	function cache($key, $value, $level) {
		$this->checkBounds($level);
		$this->caches[$level][$key] = $value;
	}
	
	/*
	 * Return whether or not a key exists in the multilevel cache
	 */
	function hasKey($key, $level=null) {
		return $this->traverseCache(function($caches) use($key) {
			foreach ($caches as $cache) {
				if (array_key_exists($key, $cache)) {
					return true;
				}
			}
			
			return false;
		}, $level);
	}
	
	/*
	 * Search for a specific key in the cache, searching all levels starting at
	 * 0 and moving up, or only at a specific level
	 */
	function getValue($key, $level=null) {
		return $this->traverseCache(function($caches) use ($key) {
			foreach ($caches as $cache) {
				if (array_key_exists($key, $cache)) {
					return $cache[$key];
				}
			}
		
			$error = "";
			if ($level === null) {
				$error = "No such key '$key' in multilevel cache";
			}
			else {
				$error = "No such key '$key' at cache level $level";
			}
			throw new \RuntimeException($error);
		}, $level);
	}
	
	/*
	 * Merge multilevel cache into a single level by combining all keys and
	 * values, using values for the keys at the higher priority levels where
	 * available. Merged result is a 1D array.
	 */
	function mergeLevels() {
		$merged = array();
		$len = count($this->caches);
		for ($i = $len-1; $i > -1; $i--) { // traverse backwards so values can be replaced by those of higher priority
			$cache = $this->caches[$i];
			foreach ($cache as $key => $val) {
				$merged[$key] = $val;
			}
		}
		
		return $merged;
	}
	
	/*
	 * Run a callback on the cache, treating the cache as multilevel even if a
	 * specific level is given
	 */
	private function traverseCache($fn, $level=null) {
		if ($level !== null) {
			$this->checkBounds($level);
			return $fn(array($this->caches[$level]));
		}
		else {
			return $fn($this->caches);
		}
	}
	
	/*
	 * Ensure the given level is within bounds
	 */
	private function checkBounds($level) {
		if ($level < 0 || $level >= count($this->caches)) {
			throw new \OutOfBoundsException("Cache index out of bounds ($level)");
		}
	}
}
?>
