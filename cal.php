<?php
#
# Please initialize $menu before including this file.
#

/**
 *
 */
function weeks($menu, $items) {
  $index_dow = array(
    "Monday"    => 0,
    "Tuesday"   => 1,
    "Wednesday" => 2,
    "Thursday"  => 3,
    "Friday"    => 4
  );
  $weeks = array();
  $week  = array();
  foreach ($items as $i) {
    $dow = date('l', $menu->timestamp_for_day($i->day));
    $index = $index_dow[$dow];
    if ($index == 0) {
      if (count($week) == 0) {
        $week[0] = $i;
      } else {
        array_push($weeks, $week);
        $week = array();
        $week[0] = $i;
      }
    } else {
      $week[$index] = $i;
    }
  }
  if (count($week)) array_push($weeks, $week);
  return $weeks;
}

$weeks = weeks($menu, $menu->items());
?>
<div id="calendar-section" class="top-section">
  <h1><?= strftime('%B %Y Lunch Menu', $menu->timestamp_for_day(2)) ?></h1>
  <table id="calendar" width="100%">
    <thead>
      <tr>
        <th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($weeks as $week) {
      ?>
      <tr>
        <?php
        for ($i = 0; $i < 5; $i++) {
          $item = false;
          $extra = array();
          if (array_key_exists($i, $week)) {
            $item = $week[$i];
          }
          if ($item) { $extra = explode('|', $item->body); }
          if (!$item) {
        ?>
          <td class="none">
            &nbsp;
          </td>
        <?php
          } elseif ($item->t == 'food') {
        ?>
        <td class="<?= $item->t ?>">
          <h4><?= $item->day ?></h4>
          <h5><?= htmlentities($item->title) ?></h5>
          <ul>
            <li><?= htmlentities($extra[0]) ?></li>
            <li><?= htmlentities($extra[1]) ?></li>
            <li><?= htmlentities($extra[2]) ?></li>
          </ul>
        </td>
        <?php
          } elseif ($item->t == 'dismissal') {
        ?>
        <td class="<?= $item->t ?>">
          <h4><?= $item->day ?></h4>
          <h5>Dismissal</h5>
        </td>
        <?php
          } elseif ($item->t == 'holiday') {
        ?>
        <td class="<?= $item->t ?>">
          <h4><?= $item->day ?></h4>
          <h5><?= $item->title ?></h5>
        </td>
        <?php
          }
        }
        ?>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>
