<?php
namespace configuration\validator\type;

class Limit extends OptionedType {

  function registersAs() {
    return "limit";
  }

  function validate($value) {
    if (is_array($value)) {
      foreach ($value as $allowed) {
        if ($
      }
    }

    return false;
  }

}
?>
