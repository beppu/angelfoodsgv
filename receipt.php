<?php
require_once('init.php');

$receipt_id = $_GET['r'];

$purchase = Purchase::find_by_receipt_id($receipt_id);

$is_admin = false;
$title = "Receipt";
include('header.php');
?>

I think it worked.

TODO: Display a receipt.

<?php
include('footer.php');
?>
