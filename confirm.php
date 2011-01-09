<?php
require_once('init.php');

$receipt_id = $_POST['r'];
$token      = $_POST['token'];
$payer_id   = $_POST['payer_id'];

$purchase = Purchase::find_by_receipt_id($receipt_id);
if ($purchase) {
  $response = $purchase->paypal_do_express_checkout($token, $payer_id);
  if ($response) {
    redirect("receipt.php?r=$receipt_id");
  } else {
    error_log("false response");
    redirect("error.php?r=$receipt_id");
  }
} else {
  error_log("purchase not found");
  redirect("error.php?r=$receipt_id");
}
?>
