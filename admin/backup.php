<?php
require_once('../lib/init.php');

$tables = array(
  "config",
  "menu",
  "menu_item",
  "purchase",
  "purchase_item"
);
$timestamp       = strftime('%Y%m%d%H%M%S');
$backup_filename = "angelfoodsgv-backup-$timestamp.txt";

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=$backup_filename");

foreach ($tables as $t) {
  echo "$t\n";
  $rs = mysql_query(sprintf("SELECT * FROM %s", $t));
  while ($columns = mysql_fetch_array($rs, MYSQL_NUM)) {
    echo join("\t", array_values($columns)) . "\n";
  }
  echo "\n";
}
?>
