<?php
require_once('../lib/init.php');

$is_admin    = true;
$title       = "Admin";
include('../header.php');

$menu_id = $_GET['menu_id'];
$day     = $_GET['day'];
$menu    = Menu::find($menu_id);
$ts      = $menu->timestamp_for_day($day);
$item    = $menu->find_item_for_day($day);
$extra   = explode('|', $item->body);

$purchase_items_by_grade = $item->purchase_items_by_grade();

$all = summary($item->purchase_items());

function summary($items) {
  $n = new stdClass();
  $n->regular = 0;
  $n->double  = 0;
  foreach ($items as $item) {
    if ($item->t == "regular") {
      $n->regular++;
    } elseif ($item->t == "double") {
      $n->double++;
    }
  }
  return $n;
}
?>

<h1><a href="index.php">Admin</a> &#x27a4; <a href="meal_schedule.php?menu_id=<?= $menu_id ?>">Meal Schedule</a> &#x27a4; <?= strftime('%D', $ts) ?></h1>

<h2><?= strftime('%B %e, %Y Schedule', $ts) ?></h2>

<table id="calendar" width="187">
  <thead>
    <th><?= date('l', $ts) ?></th>
  </thead>
  <tbody>
    <td class="<?= $item->t ?>" width="187">
      <h4><?= $item->day ?></h4>
      <h5><?= $item->title ?></h5>
      <ul>
        <li><?= $extra[0] ?></li>
        <li><?= $extra[1] ?></li>
        <li><?= $extra[2] ?></li>
      </ul>
    </td>
  </tbody>
</table>

<table class="mediagroove" cellpadding="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th width="20%">Name</th>
      <th width="20%">Family</th>
      <th width="60%">Meal</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach ($purchase_items_by_grade as $grade => $purchase_items) {
    $n = summary($purchase_items);
  ?>
    <tr>
      <th colspan="2"><?= grade($grade) ?></th>
      <th><?= sprintf("regular: %d, double %d", $n->regular, $n->double) ?></th>
    </tr>
    <?php
    foreach ($purchase_items as $item) {
    ?>
    <tr>
      <td><?= $item->child_name ?></td>
      <td><?= $item->family_name ?></td>
      <td><?= $item->t ?></td>
    </tr>
    <?php
    }
    ?>
  <?php
  }
  ?>
  </tbody>
</table>

<h2>Regular: <?= $all->regular ?></h2>
<h2>Double: <?= $all->double ?></h2>

<?php
include('../footer.php');
?>
