<?php
require_once('init.php');

$receipt_id = $_GET['r'];
$token      = $_GET['token'];
$payer_id   = $_GET['PayerID'];  # PayerID changes to payer_id in the form!

$is_admin = false;
$title = "Review";
include('header.php');
?>

TODO - show order details (again)

<form method="post" action="confirm.php">
  <input type="hidden" name="r" value="<?= $receipt_id ?>" />
  <input type="hidden" name="token" value="<?= $token ?>" />
  <input type="hidden" name="payer_id" value="<?= $payer_id ?>" />
  <input type="submit" name="confirm" value="Confirm Order" />
</form>

<?php
include('footer.php');
?>

