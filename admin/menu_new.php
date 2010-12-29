<?php
require_once('../lib/init.php');

$is_admin   = true;
$title      = "Admin - Add New Month";
$next_month = Menu::next_month();
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="add-month-section">
<form method="post" action="menu.php">
  <input type="hidden" name="action" value="save" />
  <input type="text" name="month" value="<?= $next_month->month ?>" />
  <input type="text" name="year" value="<?= $next_month->year ?>" />
  <input type="submit" name="submit" value="Save" />
</form>
</div>

<?php
include('../footer.php');
?>

