<?php
require_once('../lib/init.php');

$title = "Admin";
$menus = Menu::list_all();
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="commands">
  <a href="menu_new.php">Add New Menu</a>
</div>

<table id="menus" width="100%" border="2">
  <thead>
    <tr>
      <th>Year/Month</th>
      <th># of Orders</th>
      <th>$</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($menus as $menu) {
  ?>
    <tr>
      <td><a href="menu_edit.php?id=<?php echo $menu->id ?>"><?php echo sprintf('%02d/%d', $menu->month, $menu->year) ?></a></td>
      <td>0</td>
      <td>0</td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php
include('../footer.php');
?>
