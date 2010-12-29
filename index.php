<?php
require_once('init.php');

$title = "Angel Foods";
$show_calendar = true;
include('header.php');
?>

<?php
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

    <div class="family-info">
      <h4>Family Name</h4>
      <input type="text" name="family_name" size="30" />

      <h4>Phone Number</h4>
      <input type="text" name="phone_number" size="30" />
    </div>

    <table class="mediagroove" cellspacing="0" cellpadding="0" width="100%">
      <thead>
        <tr>
          <th>X</th>
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
          <td class="closer">X</td>
          <td>
            <input type="text" name="name" value="<?php echo "$child" ?>" />
          </td>

          <td>
            <select name="grade">
              <option value="0">K</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
            </select>
          </td>

          <td>
            <ul class="picker">
            <?php foreach ($food_days as $item) { ?>
              <li class="none"><?= $item->day < 10 ? "&nbsp;" : "" ?><?= $item->day ?></li>
            <?php } ?>
            </ul>
          </td>

          <td class="numeric">$0.00</td>
        </tr>
      <?php
      }
      ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4">Total</th>
          <td colspan="1" class="numeric">$0.00</td>
        </tr>
      </tfoot>
    </table>
    <div id="buttons">
      <div class="add">
        <input type="submit" name="submit" value="Add Child" />
      </div>
      <div class="purchase">
        <input type="submit" name="submit" value="Purchase Lunches" />
      </div>
      <div class="clear">&nbsp;</div>
    </div>
  </form>
</div>

<?php
include('footer.php');
?>
