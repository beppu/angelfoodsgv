<?php
# TODO - use absolute paths somehow
require_once('../configuration.php');
require_once('helpers.php');
require_once('db.php');
require_once('../lib/menu.php'); # XXX - is this a bug in require_once?

date_default_timezone_set('America/Los_Angeles');
db();
session_start();
?>
