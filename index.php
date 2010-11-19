<?php
$title = "Angel Foods";
include('header.php');
?>

<h1>Angel Foods</h1>

<h2>Calendar</h2>
<div id="calendar-section">
  <table id="calendar" width="100%" border="2">
    <thead>
      <tr>
        <th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>

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
    <table width="100%">
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
