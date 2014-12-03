<?php
namespace configuration\validator\type;

class Integer extends Value {

  function registersAs() {
    return "integer";
  }

  function validate($value) {
    return is_int($value);
  }

}
?>
