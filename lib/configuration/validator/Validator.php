<?php
namespace configuration\validator;

use configuration\TieredConfiguration as TieredConfiguration;
use configuration\adapter\IAdapter as IAdapter;
use configuration\validator\parser\OnusParser as OnusParser;

/*
 * Ensure a tiered configuration conforms to a standard
 *
 * Mode of operation:
 * The validator takes a tree read by an adapter and generates a test tree where
 * each node contains a validation method to be run when `validate` is called
 * on the configuration to be validated.
 */
class Validator {
  private $onus;

  function __construct(IAdapter $adapter) {
    $parser = new OnusParser();
    $this->onus = $parser->parse($adapter->buildConfigurationTree());
  }

  /*
   * Validate a loaded tiered configuration. This works by walking both trees
   * in the same order and calling the onus tree nodes' validators for each
   * node in the configuration tree
   *
   * maybe: https://en.wikipedia.org/wiki/Graph_isomorphism
   */
  function validate(TieredConfiguration $configuration) {
    $this->onus->parse($configuration);
  }
}
?>
