<?php
namespace configuration\util;

use configuration\util\TreeWalker as TreeWalker;

/*
 * A tree cache with n levels, where levels closer to 0 have a higher priority
 *
 * Author: Bremen Braun
 */
class MultilevelTreeCache {
	private $caches = array();

	/*
	 * Construct this cache with $nCaches number of levels
	 */
	function __construct($nCaches) {
		for ($i = 0; $i < $nCaches; $i++) {
			array_push($this->caches, null);
		}
	}

	/*
	 * Cache an item at a specific cache index (indexes closer to 0 have a
	 * higher priority)
	 */
	function cache($tree, $level) {
		$this->checkBounds($level);
		$this->caches[$level] = $tree;
	}

	/*
	 * Return whether or not a key exists in the multilevel cache
	 */
	function hasKey($key, $level=null) {
		try {
			$this->getValue($key, $level);
		}
		catch (Exception $e) {
			return false;
		}

		return true;
	}

	/*
	 * Return the number of levels in this cache
	 */
	function getCacheLevels() {
		return count($this->caches);
	}

	/*
	 * Search for a specific key in the cache, searching all levels starting at
	 * 0 and moving up, or only at a specific level
	 */
	function getValue($key, $level=null) {
		return $this->traverseCache(function($caches) use ($key, $level) {
			$value = null;
			foreach ($caches as $cache) {
				$walker = new TreeWalker($cache);

				$walker->walk(TreeWalker::TRAVERSE_BF, function($node) use ($key, &$value) {
					if (!$node->isLeaf() && $node->getData() === $key) { // leaf nodes ARE the values so exclude them
						$value = $node;
						return true;
					}
				});
			}

			if ($value === null) { // key not found
				$error = "";
				if ($level === null) {
					$error = "No such key '$key' in multilevel cache";
				}
				else {
					$error = "No such key '$key' at cache level $level";
				}
				throw new \RuntimeException($error);
			}
			return $value;
		}, $level);
	}

	/*
	 * Merge multilevel cache into a single level by combining all keys and
	 * values, using values for the keys at the higher priority levels where
	 * available. Merged result is a 1D array.
	 */
	function flatten() {
		$merged = array();
		$len = count($this->caches);
		for ($i = $len-1; $i > -1; $i--) { // traverse backwards so values can be replaced by those of higher priority
			$cache = $this->caches[$i]; // a configuration\tree\ConfigurationNode
      $walker = new TreeWalker($cache);

      echo "====\n";
      $scope = array($merged); // root scoping
      $walker->walk(TreeWalker::TRAVERSE_BF, function($node) use (&$scope) {
        $data = $node->getData();
        $depth = $node->getReverseDepth();

        if ($node->isLeaf()) {
          $key = $node->getParent()->getData();
          end($scope)[$key] = $data;
          echo "Adding ($key, $data) to scope\n";
        }
        else if ($depth >= 2){
          if ($data) {
            echo "!!!!Section value: $data\n";
            array_push(end($scope), array(
              $data => array()
            ));

            var_dump($scope);
          }
        }
      }, function() use (&$scope){
        //array_pop($scope);
      });
      echo "---------------\n";
		}

    var_dump($merged);
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
