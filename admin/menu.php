<?php
require_once('../lib/init.php');

$action = $_POST['action'];

switch($action) {

  case 'save':
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
    break;

  case 'item.update':
    $id = $_POST['id'];       # menu_id
    $day = $_POST['day'];     #   day of menu item
    $menu = Menu::find($id);
    if (!$menu) {
      echo json_encode(array("success" => 0));
    } else {
      $is_update = true;
      $item = $menu->find_item_for_day($day);
      if (!$item) {
        $is_update = false;
        $item = new stdClass();
      }
      $item->day   = $_POST['day'];
      $item->t     = $_POST['t'];
      $item->title = $_POST['title'];
      $item->body  = $_POST['body'];
      if ($is_update) {
        $item = $menu->update_item($item);
      } else {
        $item = $menu->add_item($item);
      }
      echo json_encode(array("success" => 1));
    }
    break;

  default;
    break;

}

?>
