<?php
require_once('../lib/init.php');

$is_admin = true;
$title    = "Admin";
include('../header.php');

$purchase_id = $_GET['purchase_id'];
$purchase    = Purchase::find($purchase_id);
$menu        = $purchase->menu();
$children    = $purchase->items_by_child();
$last_name   = ucfirst($purchase->family_name);
?>

<h1><a href="index.php">Admin</a> &#x27a4; <a href="purchases.php?menu_id=<?= $menu->id ?>">Purchases</a> &#x27a4; Receipt</h1>

<h2><?= strftime("%B %Y: $last_name Family Lunch Orders", $menu->timestamp_for_day(2)) ?></h2>

<dl class="receipt">
  <dt>Family Name</dt>
  <dd><?= $purchase->family_name ?></dd>
  <dt>Phone Number</dt>
  <dd><?= $purchase->phone_number ?></dd>
  <dt>Purchase Date</dt>
  <dd><?= $purchase->created_on ?></dd>
  <dt>Receipt ID</dt>
  <dd><?= $purchase->receipt_id ?></dd>
</dl>

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
include('../footer.php');
?>
