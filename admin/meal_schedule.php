<?php
require_once('../lib/init.php');

$is_admin    = true;
$title       = "Admin";
include('../header.php');

$menu_id = $_GET['menu_id'];
$menu    = Menu::find($menu_id);
$days    = $menu->items('food');
?>

<h1><a href="index.php">Admin</a> &#x27a4; Meal Schedule</h1>

<h2><?= strftime('%B %Y Lunch Menu', $menu->timestamp_for_day(2)) ?></h2>

<table class="mediagroove" cellpadding="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th width="20%" colspan="2">Day</th>
      <th>Meal</th>
      <th width="15%"># of Meals</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($days as $item) {
    $parts = explode('|', $item->body);
    # $extra = "(" . implode(', ', $parts) . ")";
  ?>
    <tr>
      <td><a href="meal_day.php?menu_id=<?= $menu->id ?>&amp;day=<?= $item->day ?>"><?= sprintf('%02d/%02d', $menu->month, $item->day) ?></a></td>
      <td><?= date('l', $menu->timestamp_for_day($item->day)) ?></td>
      <td><?= $item->title ?> <?= $extra ?></td>
      <td class="numeric"><?= $item->orders ?></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php
include('../footer.php');
?>