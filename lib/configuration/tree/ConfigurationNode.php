<?php
namespace configuration\tree;

/*
 * Structure for each node in the configuration tree. Since each node is in
 * itself a key, nodes naturally provide scoping
 *
 * Author: Bremen Braun
 */
class ConfigurationNode {
	private $data;
	private $parent;
	private $children;
	
	/*
	 * Construct a root node
	 */
	function __construct($data, $children=array()) {
		$this->data = $data;
		$this->parent = null;
		$this->children = $children;
	}
	
	/*
	 * Add a child node to this tree, altering the hierarchy so that this node
	 * is the parent of the node being added
	 */
	function addChild($configurationNode) {
		$configurationNode->parent = $this;
		array_push($this->children, $configurationNode);
	}
	
	/*
	 * Return the data held at this node
	 */
	function getData() {
		return $this->data;
	}
	
	/*
	 * Return the node which is the direct parent of this node
	 */
	function getParent() {
		return $this->parent;
	}
	
	function getChild($index) {
		if ($index < 0 || $index > count($this->children)-1) {
			throw new \OutOfBoundsException("Child index out of bounds ($index)");
		}
		return $this->children[$index];
	}
	
	/*
	 * Return child trees for this node
	 */
	function getChildren() {
		return $this->children;
	}
	
	/*
	 * Return whether or not this node is a root node
	 */
	function isRoot() {
		return $this->parent === null;
	}
	
	/*
	 * Return whether or not this node is a leaf node
	 */
	function isLeaf() {
		return count($this->children) === 0;
	}
}
?>
