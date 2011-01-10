<?php
require_once('../lib/init.php');

$is_admin    = true;
$title       = "Admin";
include('../header.php');

$menu_id = $_GET['menu_id'];
$menu = Menu::find($menu_id);
?>

<h1><a href="index.php">Admin</a> &#x27a4; Meal Calendar</h1>

<h2><?= strftime('%B %Y Lunch Menu', $menu->timestamp_for_day(2)) ?></h2>

<?php
include('../footer.php');
?>
