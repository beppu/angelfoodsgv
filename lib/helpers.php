<?php

/**
 *
 */
function redirect($location = '/', $status = 302) {
  header("Status: $status");
  header("Location: $location");
}

/**
 * Given an integer, return the grade it represents;
 *
 * @param   $n integer  grade as an integer
 * @return  string      grade string
 */
function grade($n) {
  static $grade_strings = array(
    -1 => "Pre-K",
    0  => "Kindergarten",
    1  => "1st Grade",
    2  => "2nd Grade",
    3  => "3rd Grade",
    4  => "4th Grade",
    5  => "5th Grade",
    6  => "6th Grade",
    7  => "7th Grade",
    8  => "8th Grade"
  );
  return $grade_strings[$n];
}

?>
