<?php
$title = "Admin - Add New Month";
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="add-month-section">
<form method="post" action="month.php">
  <input type="text" name="month" value="12" />
  <input type="text" name="year" value="2010" />
  <input type="submit" name="action" value="Save" />
</form>
</div>

<?php
include('../footer.php');
?>

