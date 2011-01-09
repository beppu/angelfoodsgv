<?php
require_once('init.php');

function day_and_order($string) {
  $list     = explode('-', $string);
  $d        = new stdClass();
  $d->day   = $list[0];
  $d->order = $list[1];
  return $d;
}

function is_blank($s) {
  return (boolean) preg_match('/^\s*$/', $s);
}

function errors_for_row($row) {
  $error = new stdClass();
  $error->status = false;
  if (is_blank($row->name)) {
    $error->status = true;
    $error->description = "Name has not been filled in.";
  }
  if (is_blank($row->grade) || $row->grade == "-") {
    $error->status = true;
    $error->description = "Grade has not been selected.";
  }
  if (is_blank($row->order)) {
    $error->status = true;
    $error->description = "No food has been ordered.";
  }

  if ($error->status) {
    return $error;
  } else {
    return false;
  }
}

function is_blank_row($row) {
  return (is_blank($row->name) && (is_blank($row->grade) || $row->grade == "-") && is_blank($row->order));
}

$menu_id      = $_POST['menu_id'];
$family_name  = $_POST['family_name'];
$phone_number = $_POST['phone_number'];
$names        = $_POST['name'];
$grades       = $_POST['grade'];
$orders       = $_POST['order'];
$n            = count($names);

$rows   = array();
$errors = array();

for ($i = 0; $i < $n; $i++) {
  $row = new stdClass();
  $row->name   = $names[$i];
  $row->grade  = $grades[$i];
  $row->order  = $orders[$i];
  $row->orders = array_map('day_and_order', explode(',', $orders[$i]));
  if (is_blank_row($row)) {
    error_log(sprintf("Row %d *IS* blank.", $i));
    continue;
  } else {
    error_log(sprintf("Row %d is not blank.", $i));
  }

  if ($error = errors_for_row($row)) {
    array_push($errors, $error);
  } else {
    error_log(sprintf("%s / %s / %s", $row->name, $row->grade, $row->order));
    array_push($rows, $row);
  }
}

$menu = Menu::find($menu_id);
if (is_blank($family_name)) {
  $error = new stdClass();
  $error->description = "Blank family name";
  array_push($errors, error);
}
if (is_blank($phone_number)) {
  $error = new stdClass();
  $error->description = "Blank phone number";
  array_push($errors, error);
}
if (!$menu) {
  $error = new stdClass();
  $error->description = "No menu";
  array_push($errors, error);
}

error_log(sprintf("# of errors == %d", count($errors)));
error_log(sprintf("# of rows   == %d", count($rows)));

$price = array(
  "regular" => $menu->regular_price,
  "double"  => $menu->double_price
);

if (count($errors)) {
  header('Content-Type: text/plain');
  var_dump($_POST);
  var_dump($errors);
  // redirect('index.php');
} else {
  $purchase = new Purchase();
  $purchase->menu_id      = $menu_id;
  $purchase->session_id   = session_id();
  $purchase->status       = 'pending';
  $purchase->family_name  = $family_name;
  $purchase->phone_number = $phone_number;
  $success = $purchase->create();
  if ($success) {
    foreach ($rows as $r) {
      error_log($r->name);
      foreach ($r->orders as $d) {
        $item = new PurchaseItem();
        $item->day         = $d->day;
        $item->t           = $d->order;
        $item->child_name  = $r->name;
        $item->child_grade = $r->grade;
        $item->price       = $price[$d->order];
        $purchase->add_item($item);
        error_log("  ".$d->day);
      }
    }
    // redirect to paypal
    $paypal_url = $purchase->paypal_set_express_checkout();
    if ($paypal_url) {
      redirect($paypal_url);
    } else {
      error_log("PayPal didn't like our SetExpressCheckout request.");
      redirect('error.php');
    }

    // DEBUG
    // header('Content-Type: text/plain');
    // var_dump($purchase);
  } else {
    echo "could not create purchase";
  }
}

?>
