<?php
require_once('init.php');

$receipt_id = $_GET['r'];

$purchase = Purchase::find_by_receipt_id($receipt_id);

$is_admin = false;
$title = "Receipt";
include('header.php');

$purchase  = Purchase::find_by_receipt_id($receipt_id);
$menu      = $purchase->menu();
$children  = $purchase->items_by_child();
$last_name = ucfirst($purchase->family_name);
?>

<h1>Receipt</h1>

<dl class="receipt">
  <dt>Family Name</dt>
  <dd><?= $purchase->family_name ?></dd>
  <dt>Purchase Date</dt>
  <dd><?= $purchase->created_on ?></dd>
  <dt>Receipt ID</dt>
  <dd><?= $purchase->receipt_id ?></dd>
</dl>

<p>Thank you very much for ordering lunch for your children with Angel Foods.  Save this page for your records.</p>

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

<?php
include('footer.php');
?>
