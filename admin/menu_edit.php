<?php
require_once('../lib/init.php');

$id = $_GET['id'];
$menu = Menu::find($id);
if (!$menu) {
  redirect('menu_not_found.php');
}
$title = "Admin - Edit Month";
include("../header.php");
?>

<div id="factory" style="display:none">
</div>

<h1><?php echo $title ?></h1>

<div><?php echo sprintf('%02d/%d', $menu->month, $menu->year) ?></div>

<div class="rough">day, type, title, body</div>

<table>
  <tbody>
  <?php
  $weekdays = $menu->weekdays();
  foreach ($weekdays as $day) {
  ?>
    <tr>
      <td><?php echo $day['day'] ?></td>
      <td><?php echo $day['dow'] ?></td>
      <td>
        <select>
          <option>food</option>
          <option>dismissal</option>
          <option>holiday</option>
        </select>
      </td>
      <td>
        <input type="text" name="title" />
      </td>
      <td>
        <input type="text" name="body" />
        <input type="text" name="body" />
        <input type="text" name="body" />
        <input type="text" name="body" />
      </td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php
include("../footer.php");
?>
