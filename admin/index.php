<?php
$title = "Admin";
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="commands">
  <a href="month_new.php">Add New Month</a>
</div>

<table id="months" width="100%" border="2">
  <thead>
    <tr>
      <th>Year/Month</th>
      <th># of Orders</th>
      <th>$</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<?php
include('../footer.php');
?>
