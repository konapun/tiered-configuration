<?php
namespace configuration\util;

/*
 * Traverse a tree using a variety of algorithms, allowing a callback to be
 * executed at each node in the traversal
 *
 * Author: Bremen Braun
 */
class TreeWalker {
	private $tree;
	
	// Walker types
	const TRAVERSE_BF = 0;
	/*const TRAVERSE_DF_PREORDER = 1;
	const TRAVERSE_DF_INORDER = 2;
	const TRAVERSE_DF_POSTORDER = 3;
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
			case self::TRAVERSE_BF:
				$this->walkRecBF($this->tree, $callback);
				break;
			default:
				throw new \InvalidArgumentException("No algorithm for walk type");
		}
	}
	
	/*
	 * Breadth-first traversal
	 */
	private function walkRecBF($tree, $callback) {
		if ($callback($tree) === true) { // break traversal early if callback returns true
			return;
		}
		foreach ($tree->getChildren() as $child) {
			$this->walkRecBF($child, $callback);
		}
	}
}
?>
