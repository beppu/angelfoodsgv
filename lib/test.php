<?php

/**
 * Did the test pass?  (Is it OK?)
 *
 * @param   boolean $passed       An expression
 * @param   string  $message      A description of this test
 * @return  boolean
 */
function ok($passed=false, $message) {
  static $i = 1;
  if ($passed) {
    printf("ok $i - %s\n", $message);
  } else {
    printf("not ok $i - %s\n", $message);
  }
  $i++;
  return $passed;
}

?>

