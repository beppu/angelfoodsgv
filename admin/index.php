<?php
require_once('../lib/init.php');

$is_admin = true;
$title = "Admin";
$menus = Menu::list_all();
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="commands">
  <a href="menu_new.php">Add New Menu</a>
</div>

<table id="menus" class="mediagroove" width="100%" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th width="10px">*</th>
      <th>Menu</th>
      <th class="numeric" width="15%"># of Orders</th>
      <th class="numeric" width="15%"># of Meals</th>
      <th class="numeric" width="15%">Income</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($menus as $menu) {
  ?>
    <tr>
      <td id="menu-current-<?= $menu->id ?>" class="current"><?= $menu->is_current ? "*" : "&nbsp;" ?></td>
      <td><a href="menu_edit.php?id=<?php echo $menu->id ?>"><?= sprintf('%02d/%d', $menu->month, $menu->year) ?></a></td>
      <td class="numeric"><?= $menu->purchases ?></td>
      <td class="numeric"><?= $menu->meals ?></td>
      <td class="numeric">$<?= $menu->income ?></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php
include('../footer.php');
?>
