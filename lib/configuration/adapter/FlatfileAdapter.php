<?php
namespace configuration\adapter;

use configuration\adapter\IAdapter as IAdapter;
use configuration\tree\TreeBuilder as TreeBuilder;
use configuration\exception\FileNotFoundException as FileNotFoundException;
use configuration\exception\FormatException as FormatException;

/*
 * An adapter to read configuration from a flatfile
 *
 * Author: Bremen Braun
 */
class FlatfileAdapter implements IAdapter {
  private $fileContents;

  function __construct($flatfile) {
    $contents = file_get_contents($flatfile);
    if ($contents === false) {
      throw new FileNotFoundException("Can't locate file '$flatfile'");
    }
    $this->filename = $flatfile;
    $this->fileContents = $contents;
  }

  /*
   * Return the contents of the JSON file as a configuration tree
   */
  function buildConfigurationTree() {
    $treeBuilder = new TreeBuilder();
    return $treeBuilder->buildTree($this->parseFlatfile($this->fileContents));
  }

  private function parseFlatfile($contents) {
    $tree = array();
    $lines = preg_split('/\r\n|\r|\n/', $contents);
    $lineNumber = 1;
    foreach ($lines as $line) {
      if (strpos($line, '#') !== 0) { // skip comment lines
        $keyval = explode('=', $line);
        if (!$line) continue; // skip blank lines
        if (count($keyval) == 2) {
          list($key, $value) = $keyval;

          $tree[$key] = $value;
        }
        else {
          throw new FormatException("Error parsing flatfile - no token '=' on line $lineNumber in file " . $this->filename);
        }
      }
      $lineNumber++;
    }
    
    return $tree;
  }
}
?>
