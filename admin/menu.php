<?php
require_once('../lib/init.php');

$action = $_POST['action'];
if ($action == 'save') {
  $year  = 0;
  $month = 0;
  if (is_numeric($_POST['year'])) {
    $year = $_POST['year'];
  }
  if (is_numeric($_POST['month'])) {
    $month = $_POST['month'];
  }
  $menu = new Menu();
  $menu->year = $year;
  $menu->month = $month;
  $menu->create();
  redirect('menu_edit.php?id='.$menu->id);
} elseif ($action = 'add_item') {
  redirect('index.php');
} else {
  redirect('index.php');
}

?>
