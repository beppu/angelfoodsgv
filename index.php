<?php
require_once('init.php');

# By default the current menu is selected.
# However, an explicit menu_id may be given as well.
$menu = null;
if (isset($_GET['menu_id'])) {
  $menu = Menu::find($_GET['menu_id']);
} else {
  $menu = Menu::current();
}

$title = "Angel Foods";
$top_section = 'cal.php';
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
            <input type="text" class="name" name="name[]" value="" />
          </td>
          <td>
            <select name="grade[]">
              <option value="-">-</option>
              <option value="-1">Pre-K</option>
              <option value="0">K</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
            </select>
          </td>
          <td>
            <ul class="picker">
            <?php foreach ($food_days as $item) { ?>
              <li class="none"><?= $item->day < 10 ? "&nbsp;" : "" ?><?= $item->day ?></li>
            <?php } ?>
            </ul>
            <span class="all">all</span>
            <input type="hidden" class="order" name="order[]" />
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
    <input type="hidden" name="menu_id" value="<?= $menu->id ?>" />

    <!-- The prices are here to support the JS that calculates the totals.  
    order.php gets the prices from the database and not from these form values. -->
    <input type="hidden" id="regular_price" name="regular_price" value="<?= $menu->regular_price ?>" />
    <input type="hidden" id="double_price" name="double_price" value="<?= $menu->double_price ?>" />

    <div class="family-info">
      <h4>Family Name</h4>
      <input type="text" id="family-name" name="family_name" size="30" />

      <h4>Phone Number</h4>
      <input type="text" id="phone-number" name="phone_number" size="30" />
    </div>

    <table class="mediagroove" cellspacing="0" cellpadding="0" width="100%">
      <thead>
        <tr>
          <th width="20px">X</th>
          <th width="20%">Child's Name</th>
          <th width="30px">Grade</th>
          <th>Meals (click on the days) </th>
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
            <input type="text" class="name" name="name[]" value="<?= $child ?>" />
          </td>

          <td>
            <select name="grade[]">
              <option value="-">-</option>
              <option value="-1">Pre-K</option>
              <option value="0">K</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
            </select>
          </td>

          <td>
            <ul class="picker">
            <?php foreach ($food_days as $item) { ?>
              <li class="none"><?= $item->day < 10 ? "&nbsp;" : "" ?><?= $item->day ?></li>
            <?php } ?>
            </ul>
            <span class="all">all</span>
            <input type="hidden" class="order" name="order[]" />
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
        <!-- <a href="#buttons">Add Another Child</a> -->
        <button class="blue-pill">Add Another Child</button>
      </div>
      <div class="purchase">
        <!-- <input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" /> -->
        <input type="image" name="submit" src="https://checkout.google.com/buttons/checkout.gif?merchant_id=<?= $config->google_merchant_id ?>&w=180&h=46&style=trans&variant=<?= $config->environment == 'production' ? 'text' : 'disabled' ?>&loc=en_US" />
      </div>
      <div class="clear">&nbsp;</div>
    </div>
  </form>

  <div class="info">
    <table cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td width="106">Lunch</td>
          <td class="numeric">$<?= $menu->regular_price ?></td>
          <td><ul class="picker"><li class="regular">&nbsp;1</li></ul> Click Box Once</td>
        </tr>
        <tr>
          <td>Double Entr&eacute;e</td>
          <td class="numeric">+$<?= sprintf('%0.2f', $menu->double_price - $menu->regular_price) ?></td>
          <td><ul class="picker"><li class="double">&nbsp;2</li></ul> Click Box Twice</td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<!--

the flow: index.php, order.php, paypal, review.php, confirm.php, receipt.php

-->

<?php
include('footer.php');
?>
