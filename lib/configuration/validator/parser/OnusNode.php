<?php
namespace configuration\validator\parser;

use configuration\tree\ConfigurationNode as ConfigurationNode;


class OnusNode extends ConfigurationNode {
  private $validationFn;

  // TODO
  
  function validate($value) {
    return $this->validationFn($value);
  }
}
?>
