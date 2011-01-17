<?php
require_once('../lib/init.php');

$tables = array(
  "config",
  "menu",
  "menu_item",
  "purchase",
  "purchase_item"
);

$timestamp = strftime('%Y%m%s%H%M%S');

$backup_filename = "angelfoodsgv-backup-$timestamp.txt";
$backup_dir = $config->db_backup_dir . "/$timestamp";

mkdir($backup_dir);
chmod($backup_dir, 0777);
foreach ($tables as $t) {
  $rs = mysql_query(sprintf("SELECT * FROM %s INTO OUTFILE '%s'", $t, sprintf("%s/%s.outfile", $backup_dir, $t)));
}

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=$backup_filename");

foreach ($tables as $t) {
  $f = sprintf("%s/%s.outfile", $backup_dir, $t);
  echo "$t\n";
  readfile($f);
  unlink($f);
  echo "\n";
}
rmdir($backup_dir);
?>
