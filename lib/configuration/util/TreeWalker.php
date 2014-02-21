<?php
namespace configuration\util;

class TreeNode {
	public $key;
	public $value;
	
	function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}
}

/*
 * Traverse a tree using a variety of algorithms, allowing a callback to be
 * executed at each node in the traversal
 *
 * Author: Bremen Braun
 */
class TreeWalker {
	private $tree;
	
	// Walker types
	const BF = 0;
	/*const DF_PREORDER = 1;
	const DF_INORDER = 2;
	const DF_POSTORDER = 3;
	*/
	
	function __construct($tree) {
		$this->tree = $tree;
	}
	
	/*
	 * Execute a traversal of type $algorithm, where $algorithm is a value from
	 * the enum above, calling $callback on each node in the order encountered
	 */
	function walk($algorithm, $callback) {
		switch ($algorithm) {
			case self::BF:
				$this->walkRecBF($this->tree, $callback);
				break;
			default:
				throw new \InvalidArgumentException("No algorithm for walk type");
		}
	}
	
	/*
	 * Breadth-first traversal
	 *
	 * FIXME: Handling of key for nested array
	 */
	private function walkRecBF($tree, $callback) {
		foreach (array_keys($tree) as $key) {
			$val = $tree[$key];
			if (is_array($val)) {
				$this->walkRecBF($val, $callback);
			}
			else {
				$callback(new TreeNode($key, $val));
			}
		}
	}
}
?>
