<?php
require_once('init.php');

$title = "Angel Foods";
$show_calendar = true;
include('header.php');
?>

<?php
$children = array(
  0 => "",
  1 => "",
  2 => "",
  3 => ""
);
$food_days = $menu->items('food');
?>
<div id="factory" style="display: none;">
  <form>
    <table>
      <tbody>
        <tr>
          <td class="closer">X</td>
          <td>
            <input type="text" name="name[]" value="" />
          </td>
          <td>
            <select name="grade[]">
              <option value="-">-</option>
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
            <span class="all">all</span>
            <input type="hidden" name="days[]" />
          </td>
          <td class="numeric total">$0.00</td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

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
          <th width="20px">X</th>
          <th width="20%">Child's Name</th>
          <th width="30px">Grade</th>
          <th>Food</th>
          <th width="60px">Price</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      foreach ($children as $child) { 
      ?>
        <tr>
          <td class="closer">X</td>
          <td>
            <input type="text" name="name[]" value="<?php echo "$child" ?>" />
          </td>

          <td>
            <select name="grade[]">
              <option value="-">-</option>
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
            <span class="all">all</span>
            <input type="hidden" name="days[]" />
          </td>

          <td class="numeric total">$0.00</td>
        </tr>
      <?php
      }
      ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4">Total</th>
          <td colspan="1" class="numeric grand-total">$0.00</td>
        </tr>
      </tfoot>
    </table>
    <div id="buttons">
      <div class="add">
        <a href="#buttons">Add Another Child</a>
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
