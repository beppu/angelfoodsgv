<?php
require_once('init.php');

$receipt_id = $_GET['r'];
$purchase = Purchase::find_by_receipt_id($receipt_id);
$purchase->cancel();

redirect('index.php');
?>
