<?php
namespace configuration\validator\parser;

use configuration\validator\type\TypeFactory as TypeFactory;
use configuration\util\TreeWalker as TreeWalker;

/*
 * Load an onus file
 */
class OnusParser {
  private $factory;

  function __construct() {
    $this->factory = new TypeFactory();
  }
  
  function parse($tree) {
    $walker = new TreeWalker($tree);

    $walker->walk(TreeWalker::TRAVERSE_BF, function($node) {
      $reverseDepth = $node->getReverseDepth();

      switch ($reverseDepth) {
        case 0:
          echo "Case 0 with " . $node->getData() . "\n";
          break;
        case 1:
          echo "Case 1 with " . $node->getData() . "\n";
          break;
        case 2:
          echo "Case 2 with " . $node->getData() . "\n";
        default:
          // nothing
      }
    });
  }
}
?>
