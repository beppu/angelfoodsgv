<?php

function config() {
  global $config;
  return $config;
}

/**
 *
 */
function q($s) {
  if (get_magic_quotes_gpc()) {
    return $s;
  }
  return mysql_real_escape_string($s);
}

function now() {
  $time = localtime();
  return sprintf('%d-%02d-%02d %02d:%02d:%02d',
    $time[5]+1900,
    $time[4]+1,
    $time[3],
    $time[2],
    $time[1],
    $time[0]
  );
}

function db() {
  global $config;
  static $connection;
  $connection = mysql_connect(
    $config->db_host,
    $config->db_user,
    $config->db_password
  );
  mysql_select_db($config->db);
}

?>
