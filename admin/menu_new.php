<?php
$title = "Admin - Add New Month";
include('../header.php');
?>

<h1><?php echo $title ?></h1>

<div id="add-month-section">
<form method="post" action="menu.php">
  <input type="hidden" name="action" value="save" />
  <input type="text" name="month" value="12" />
  <input type="text" name="year" value="2010" />
  <input type="submit" name="submit" value="Save" />
</form>
</div>

<?php
include('../footer.php');
?>

