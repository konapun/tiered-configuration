<?php
namespace configuration\validator\type;

use configuration\exception\ParseException as ParseException;

abstract class Value {
  private $attributes;

  function __construct($attributes) {
    if (!array_key_exists('type', $attributes)) {
      throw new ParseException('Declaration requires "type" attribute');
    }
    $this->attributes = $attributes;
  }

  /*
   * Return the name this registers as in the configuration
   */
  abstract function registersAs();

  /*
   * Return true or false depending on whether value parses
   * to this type
   */
  abstract function validate($value);
}
?>
