<?php
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
?>
<h2>Order Form</h2>
<div id="order-section">
  <form id="order">
    <table class="mediagroove" width="100%" cellspacing="0" cellpadding="0" >
      <thead>
        <tr>
          <th>Child's Name / Grade</th>
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
            name and grade
          </td>

          <td>
            food * day
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
          <th colspan="2">Total</th>
          <td colspan="1" class="numeric">$0.00</td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<?php
include('footer.php');
?>
