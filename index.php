<?php
require_once('configuration.php');
require_once('lib/helpers.php');
require_once('lib/db.php');
require_once('lib/menu.php');
date_default_timezone_set('America/Los_Angeles');
db();
session_start();

$title = "Angel Foods";
$show_calendar = true;
include('header.php');
?>

<?php
$i = 0;
$children = array(
  0 => "Jim",
  1 => "Bob",
  2 => "Mary",
  3 => "Jane"
);
$food_days = $menu->items('food');
?>
<h1>Order Form</h1>
<div id="order-section">
  <form id="order" method="post" action="order.php">
    <table class="mediagroove" cellspacing="0" cellpadding="0" width="100%">
      <thead>
        <tr>
          <th>Child's Name</th>
          <th>Grade</th>
          <th>Food</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      foreach ($children as $child) { 
      ?>
        <tr>
          <td>
            <?php echo "$i $child" ?>
            name
          </td>

          <td>
            grade
          </td>

          <td>
            <ul class="picker">
            <?php foreach ($food_days as $item) { ?>
              <li><?= $item->day < 10 ? "&nbsp;" : "" ?><?= $item->day ?></li>
            <?php } ?>
            </ul>
          </td>

          <td class="numeric">
            price
          </td>
        </tr>
      <?php
        $i++;
      }
      ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">Total</th>
          <td colspan="1" class="numeric">$0.00</td>
        </tr>
      </tfoot>
    </table>
    <div id="buttons">
      <input type="submit" name="submit" value="Purchase Lunches" />
    </div>
  </form>
</div>

<?php
include('footer.php');
?>
