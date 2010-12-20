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
      <th>Year/Month</th>
      <th># of Orders</th>
      <th>$</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $odd = 1;
  foreach ($menus as $menu) {
  ?>
    <tr class="<?php echo $odd ? "odd" : "even" ?>">
      <td><a href="menu_edit.php?id=<?php echo $menu->id ?>"><?php echo sprintf('%02d/%d', $menu->month, $menu->year) ?></a></td>
      <td>0</td>
      <td>0</td>
    </tr>
  <?php
    $odd ^= 1;
  }
  ?>
  </tbody>
</table>

<?php
include('../footer.php');
?>
