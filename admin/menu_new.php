<?php
require_once('../lib/init.php');

$is_admin   = true;
$title      = "Admin - Add New Month";
$next_month = Menu::next_month();
include('../header.php');
?>

<h1><a href="index.php">Admin</a> &#x27a4; Add New Menu</h1>

<div id="add-menu-section">
<form method="post" action="menu.php">
  <input type="hidden" name="action" value="save" />
  Month:
  <input type="text" size="4" name="month" value="<?= $next_month->month ?>" />
  Year: 
  <input type="text" size="6" name="year" value="<?= $next_month->year ?>" />
  Regular Price:
  <input type="text" size="6" name="regular_price" value="<?= sprintf('%0.2f', $config->default_regular_price) ?>" />
  Double Price:
  <input type="text" size="6" name="double_price" value="<?= sprintf('%0.2f', $config->default_double_price) ?>" />
  &nbsp;
  <input type="submit" name="submit" value="Save" />
</form>
</div>

<?php
include('../footer.php');
?>

