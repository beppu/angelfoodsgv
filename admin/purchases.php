<?php
require_once('../lib/init.php');

$is_admin = true;
$title    = "Admin";
include('../header.php');

$menu_id   = $_GET['menu_id'];
$menu      = Menu::find($menu_id);
$purchases = $menu->purchases('paid');
?>

<h1><a href="index.php">Admin</a> &#x27a4; Purchases</h1>

<h2><?= strftime('%B %Y Lunch Menu', $menu->timestamp_for_day(2)) ?></h2>

<table class="mediagroove" cellpadding="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th width="55%">Family Name</th>
      <th width="15%">Phone Number</th>
      <th width="15%">Ordered On</th>
      <th width="15%">Total</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($purchases as $p) {
  ?>
    <tr>
      <td>
        <a href="purchase_receipt_printable.php?purchase_id=<?= $p->id ?>"><img src="/images/printer.png" alt="printable receipt" /></a>
        <a href="purchase_receipt.php?purchase_id=<?= $p->id ?>"><?= $p->family_name ?></a>
      </td>
      <td><?= $p->phone_number ?></td>
      <td><?= $p->created_on ?></td>
      <td class="numeric">$<?= $p->amount() ?></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php
include('../footer.php');
?>
