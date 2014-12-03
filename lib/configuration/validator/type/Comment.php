<?php
namespace configuration\validator\type;

class Comment extends Value {

  function registersAs() {
    return "comment";
  }

  function validate($value) {
    return true; // a comment is true no matter what
  }
}
?>
