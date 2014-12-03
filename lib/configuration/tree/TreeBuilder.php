<?php
namespace configuration\tree;

use configuration\tree\ConfigurationNode as Node;

/*
 * Build tree structures from arrays, where each node in the tree is a recursive
 * tree
 *
 * Author: Bremen Braun
 */
class TreeBuilder {

	function __construct() {}

	/*
	 * Build a tree and return the root node
	 */
	function buildTree($arrayTree) {
		$tree = $this->buildTreeRec(new Node(null), $arrayTree); // root at an empty node
		while (!$tree->isRoot()) {
			$tree = $tree->getParent();
		}
    
		return $tree;
	}

	/*
	 * Convert a tree in an array structure to an actual tree structure
	 * providing convenient tree operations
	 */
	private function buildTreeRec($node, $arrayTree) {
		foreach (array_keys($arrayTree) as $key) {
			$sectionNode = new Node($key);
			$node->addChild($sectionNode);

			$nodeVal = $arrayTree[$key];
			if (is_array($nodeVal)) {
				$this->buildTreeRec($sectionNode, $nodeVal);
			}
			else {
				$sectionNode->addChild(new Node($nodeVal));
			}
		}
		return $node;
	}
}
?>
