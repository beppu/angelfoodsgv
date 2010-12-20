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
    <table width="100%" class="mediagroove" >
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

          <td>
            price
          </td>
        </tr>
      <?php
        $i++;
      }
      ?>
      </tbody>
    </table>
  </form>
</div>

<?php
include('footer.php');
?>
