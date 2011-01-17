<?php
require_once('../lib/init.php');

$purchase_id = $_GET['purchase_id'];
$purchase    = Purchase::find($purchase_id);
$menu        = $purchase->menu();
$children    = $purchase->items_by_child();
$last_name   = ucfirst($purchase->family_name);
$food_days   = $menu->items('food');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Receipt</title>
<link rel="stylesheet" type="text/css" href="/css/receipt.css" />
<!--
<link rel="stylesheet" type="text/css" href="FIXME" />
<script type="text/javascript" src="FIXME"></script>
<style type="text/css">
/* <![CDATA[ */
/* ]]> */
</style>
-->
</head>
<body>

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

<table class="receipt" width="100%" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th width="15%">Child's Name</th>
      <th width="15%">Grade</th>
      <th>Food</th>
      <th width="10%">Price</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($children as $k => $items) {
    $list = explode('|', $k);
    $name = $list[0];
    $grade = grade($list[1]);
    $type = array();
    foreach ($food_days as $item) {
      $type[$item->day] = "none";
    }
    foreach ($items as $item) {
      $type[$item->day] = $item->t;
    }
  ?>
    <tr>
      <td><?= $name ?></td>
      <td><?= $grade ?></td>
      <td>
        <ul class="picker">
        <?php 
        $total = 0;
        foreach ($food_days as $item) { 
        ?>
          <li class="<?= $type[$item->day] ?>"><?= $item->day < 10 ? "&nbsp;" : "" ?><?= $item->day ?></li>
        <?php 
          switch ($type[$item->day]) {
            case 'regular':
              $total += $menu->regular_price;
              break;
            case 'double':
              $total += $menu->double_price;
              break;
          }
        }
        ?>
        </ul>
      </td>
      <td class="numeric">$<?= sprintf('%0.2f', $total) ?></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">Total</td>
      <td class="numeric">$<?= $purchase->amount() ?></td>
    </tr>
  </tfoot>
</table>

<table cellspacing="0" cellpadding="4">
  <tbody>
    <tr>
      <td width="120">Lunch</td>
      <td width="80"class="numeric">$<?= $menu->regular_price ?></td>
      <td><ul class="picker"><li class="regular">&nbsp;1</li></ul></td>
    </tr>
    <tr>
      <td>Double Entree</td>
      <td class="numeric">+$<?= sprintf('%0.2f', $menu->double_price - $menu->regular_price) ?></td>
      <td><ul class="picker"><li class="double">&nbsp;2</li></ul></td>
    </tr>
  </tbody>
</table>

</body>
</html>
