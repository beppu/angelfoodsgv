<?php

/**
 *
 */
class Menu {
  public $id;
  public $year;
  public $month;
  public $created_on;
  public $modified_on;

  /**
   *
   */
  static function find($id) {
    $rs = mysql_query(sprintf("SELECT * FROM menu WHERE id = %d", q($id)));
    $menu = mysql_fetch_object($rs, 'Menu');
    return $menu;
  }

  /**
   *
   */
  static function find_by_date($year, $month) {
    $rs = mysql_query(sprintf("SELECT * FROM menu WHERE year = %d AND month = %d", q($year), q($month)));
    $menu = mysql_fetch_object($rs, 'Menu');
    return $menu;
  }

  /**
   *
   */
  static function list_all() {
    $rs = mysql_query("SELECT * FROM menu ORDER BY year desc, month desc;");
    $menus = array();
    while ($menu = mysql_fetch_object($rs, 'Menu')) {
      array_push($menus, $menu);
    }
    return $menus;
  }

  /**
   *
   */
  function create() {
    $rs = mysql_query(sprintf("INSERT INTO menu (year, month, created_on) VALUES (%d, %d, '%s')",
      q($this->year), q($this->month), now()
    ));
    $this->id = mysql_insert_id();
    foreach ($this->weekdays() as $d) {
      $item = new stdClass();
      $item->menu_id = $this->id;
      $item->day     = $d['day'];
      $item->dow     = $d['dow'];
      $item->t       = 'food';
      $item->title   = '';
      $item->body    = '';
      $this->add_item($item);
    }
    return $rs;
  }

  /**
   *
   */
  function update() {
    $rs = mysql_query(sprintf("UPDATE menu SET year = %d, month = %d WHERE id = %d",
      $this->year,
      $this->month,
      $this->id
    ));
    return $rs;
  }

  /**
   *
   */
  function delete() {
    $rs = mysql_query(sprintf("DELETE FROM menu WHERE id = %d", $this->id));
    return $rs;
  }

  /**
   *
   */
  function timestamp_for_day($day = 1) {
    return mktime(0, 0, 0, $this->month, $day, $this->year);
  }

  /**
   * The weekdays of this month
   *
   * @return array [ { "day": n, "dow": dayOfWeek }, ... ]
   */
  function weekdays() {
    $weekdays = array();
    $days_in_month = date('t', $this->timestamp_for_day());
    for ($i = 1; $i <= $days_in_month; $i++) {
      $dow = date('l', $this->timestamp_for_day($i));
      if ($dow == 'Saturday') continue;
      if ($dow == 'Sunday')   continue;
      array_push($weekdays, array(
        'day' => $i,
        'dow' => $dow
      ));
    }
    return $weekdays;
  }

  /**
   * Return all of the menu's items.
   *
   * @return array<stdClass> this month's menu items
   */
  function items() {
    $rs = mysql_query(sprintf("SELECT * FROM menu_item WHERE menu_id = %d ORDER by day", q($this->id)));
    $items = array();
    while ($item = mysql_fetch_object($rs)) {
      array_push($items, $item);
    }
    return $items;
  }

  /**
   * Find the item for the given day.
   */
  function find_item_for_day($day) {
    $rs = mysql_query(sprintf("SELECT * FROM menu_item WHERE menu_id = %d AND day = %d", q($this->id), q($day)));
    $item = mysql_fetch_object($rs);
    return $item;
  }

  /**
   *
   */
  function add_item($item) {
    $item->menu_id = $this->id;
    $item->created_on = now();
    $rs = mysql_query(sprintf(
      "INSERT INTO menu_item (menu_id, day, t, title, body, created_on) VALUES(%d, %d, '%s', '%s', '%s', '%s')",
      q($item->menu_id),
      q($item->day),
      q($item->t),
      q($item->title),
      q($item->body),
      q($item->created_on)
    ));
    $item->id = mysql_insert_id();
    return $item;
  }

  /**
   *
   */
  function update_item($item) {
    $rs = mysql_query(sprintf(
      "UPDATE menu_item SET day = %d, t = '%s', title = '%s', body = '%s' WHERE id = %d",
      q($item->day),
      q($item->t),
      q($item->title),
      q($item->body),
      q($item->id)
    ));
    return $rs;
  }

}
?>
