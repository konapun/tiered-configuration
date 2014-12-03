<?php
namespace configuration\validator\type;

abstract class Value {

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
