<?php
require_once('init.php');

$receipt_id = $_GET['r'];
$token      = $_GET['token'];
$payer_id   = $_GET['PayerID'];  # PayerID changes to payer_id in the form!

$is_admin = false;
$title = "Confirm Order";
include('header.php');

$purchase  = Purchase::find_by_receipt_id($receipt_id);
$menu      = $purchase->menu();
$children  = $purchase->items_by_child();
$last_name = ucfirst($purchase->family_name);
?>

<h1>Confirm Your Order</h1>

<p>If everything looks correct, click on the <b>Confirm Order</b> button below.  This will complete your order.</p>

<table class="mediagroove" cellpadding="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th colspan="2" width="20%">Day</th>
      <th>Meal</th>
      <th width="10%">Portion</th>
      <th width="10%">Price</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($children as $key => $items) {
    $info = explode('|', $key);
    $child_name  = $info[0];
    $child_grade = "";
    if ($info[1] == 0) {
      $child_grade = "Kindergarten";
    } else {
      $child_grade = sprintf('Grade %d', $info[1]);
    }
  ?>
    <tr>
      <th colspan="5"><?= "$child_name / $child_grade" ?></th>
    </tr>
    <?php
    foreach ($items as $item) {
    ?>
    <tr>
      <td><?= sprintf('%02d/%02d', $menu->month, $item->day) ?></td>
      <td><?= date('l', $menu->timestamp_for_day($item->day)) ?></td>
      <td><?= $item->title ?></td>
      <td><?= $item->t ?></td>
      <td class="numeric">$<?= $item->price ?></td>
    </tr>
    <?php
    }
    ?>
  <?php
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
      <td>Total</td>
      <td colspan="4" class="numeric">$<?= $purchase->amount() ?></td>
    </tr>
  </tfoot>
</table>

<div id="confirm">
  <form method="post" action="confirm.php">
    <input type="hidden" name="r" value="<?= $receipt_id ?>" />
    <input type="hidden" name="token" value="<?= $token ?>" />
    <input type="hidden" name="payer_id" value="<?= $payer_id ?>" />
    <input type="submit" name="confirm" value="Confirm Order" />
  </form>
</div>

<?php
include('footer.php');
?>

