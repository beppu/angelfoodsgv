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
    $rs = mysql_query(sprintf("SELECT * FROM menu WHERE id = %s", q($id)));
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
   *
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
   *
   */
  function add($item) {
  }

  /**
   *
   */
  function remove($item) {
  }

}
?>
