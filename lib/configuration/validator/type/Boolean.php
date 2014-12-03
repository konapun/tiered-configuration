<?php
namespace configuration\validator\type;

class Boolean extends Value {

  function registersAs() {
    return "boolean";
  }

  function validate($value) {
    return is_bool($value);
  }
}
?>
