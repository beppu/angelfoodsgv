<?php
require_once('../lib/init.php');

$id = $_GET['id'];
$menu = Menu::find($id);
if (!$menu) {
  redirect('menu_not_found.php');
}

$is_admin = true;
$title = "Admin - Edit Month";
include("../header.php");
?>

<h1><a href="index.php">Admin</a> &#x27a4; Edit Menu</h1>

<form class="menu-editor">
<input type="hidden" id="id" name="id" value="<?= $id ?>" />

<table class="mediagroove menu-editor" cellspacing="0" cellpadding="0" width="100%">
  <thead>
    <th colspan="2">
      Date
    </th>
    <th>
      Type
    </th>
    <th width="167">
      Title
    </th>
    <th>
      Extra
    </th>
  </thead>
  <tbody>
  <?php
  foreach ($menu->items() as $item) {
    $body = explode('|', $item->body);
  ?>
    <tr>
      <td>
        <?= sprintf('%02d/%02d', $menu->month, $item->day) ?>
        <input type="hidden" name="day" value="<?= $item->day ?>" />
      </td>
      <td><?= date('l', $menu->timestamp_for_day($item->day)) ?></td>
      <td>
        <select name="t">
          <option<?= $item->t == 'food'      ? ' selected="selected"' : '' ?>>food</option>
          <option<?= $item->t == 'dismissal' ? ' selected="selected"' : '' ?>>dismissal</option>
          <option<?= $item->t == 'holiday'   ? ' selected="selected"' : '' ?>>holiday</option>
        </select>
      </td>
      <td class="title">
        <input size="16" type="text" name="title" value="<?= $item->title ?>"<?= $item->t == 'dismissal' ? ' disabled="disabled"' : '' ?>/>
        <span class="saving"></span>
      </td>
      <td class="extra">
        <input size="12" type="text" name="body" value="<?= $body[0] ?>"<?= ($item->t == 'holiday' || $item->t == 'dismissal') ? ' disabled="disabled"' : '' ?>/>
        <input size="12" type="text" name="body" value="<?= $body[1] ?>"<?= ($item->t == 'holiday' || $item->t == 'dismissal') ? ' disabled="disabled"' : '' ?>/>
        <input size="12" type="text" name="body" value="<?= $body[2] ?>"<?= ($item->t == 'holiday' || $item->t == 'dismissal') ? ' disabled="disabled"' : '' ?>/>
      </td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

</form>

<?php
include("../footer.php");
?>
