<?php
namespace configuration\validator;

use configuration\TieredConfiguration as TieredConfiguration;
use configuration\adapter\IAdapter as IAdapter;
use configuration\validator\parser\OnusParser as OnusParser;

/*
 * Ensure a tiered configuration conforms to a standard
 */
class Validator {
  private $onus;

  function __construct(IAdapter $adapter) {
    $parser = new OnusParser();
    $this->onus = $parser->parse($adapter->buildConfigurationTree());
  }

  /*
   * Validate a loaded tiered configuration
   */
  function validate(TieredConfiguration $configuration) {
    $this->onus->parse($configuration);
  }
}
?>
