<?php

/**
 *
 */
class Menu {
  public $id;
  public $year;
  public $month;
  public $regular_price;
  public $double_price;
  public $is_current;  // only available via list_all() at the moment
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
   * Return currently active menu
   */
  static function current() {
    $rs = mysql_query("SELECT menu.* FROM menu, config WHERE menu.id = config.current_menu_id");
    $menu = mysql_fetch_object($rs, 'Menu');
    return $menu;
  }

  /**
   * Make the given menu_id be the "current" menu.
   * @param int $id   a menu id
   */
  static function set_current($id) {
    $rs = mysql_query(sprintf("UPDATE config SET current_menu_id = %d", q($id)));
    return $rs;
  }

  /**
   *
   */
  static function list_all() {
    $rs = mysql_query("
      SELECT menu.*,
             (SELECT count(*) FROM purchase WHERE status = 'paid' AND menu_id = menu.id) AS purchases, 
             if(purchase.status, sum(if(strcmp(purchase.status, 'paid'), 0, 1)), 0)      AS meals,
             sum(if(strcmp(purchase.status, 'paid'), 0, purchase_item.price))            AS income,
             (config.current_menu_id = menu.id) AS is_current 
        FROM config, menu 
             LEFT JOIN purchase      ON menu.id     = purchase.menu_id
             LEFT JOIN purchase_item ON purchase.id = purchase_item.purchase_id 
       WHERE config.id = 1 
       GROUP BY menu.id 
       ORDER BY year DESC, month DESC
    ");
    $menus = array();
    while ($menu = mysql_fetch_object($rs, 'Menu')) {
      array_push($menus, $menu);
    }
    return $menus;
  }

  /**
   * Figure out what year and month the next menu should be.
   */
  static function next_month() {
    $rs = mysql_query("SELECT year, month FROM menu ORDER BY year DESC, month DESC LIMIT 1");
    $timing = mysql_fetch_object($rs);
    if ($timing->month == 12) {
      $timing->year++;
      $timing->month = 1;
    } else {
      $timing->month++;
    }
    return $timing;
  }

  /**
   *
   */
  function create() {
    $rs = mysql_query(sprintf("INSERT INTO menu (year, month, regular_price, double_price, created_on) VALUES (%d, %d, %f, %f, '%s')",
      q($this->year), q($this->month), q($this->regular_price), q($this->double_price), now()
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
    $rs = mysql_query(sprintf("UPDATE menu SET year = %d, month = %d, regular_price = %0.2f, double_price = %0.2f WHERE id = %d",
      $this->year,
      $this->month,
      $this->regular_price,
      $this->double_price,
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
   * @return array    [ { "day": n, "dow": dayOfWeek }, ... ]
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
  function items($t=null) {
    $query = "";
    if ($t) {
      $query = sprintf("
        SELECT mi.*,  
               (SELECT count(pi.id) 
                  FROM purchase_item pi 
                       JOIN purchase p ON p.id = pi.purchase_id 
                 WHERE pi.day = mi.day 
                   AND p.menu_id = mi.menu_id
                   AND p.status = 'paid') as orders
          FROM menu_item mi 
         WHERE menu_id = %d AND mi.t = '%s' 
         ORDER BY mi.day",
        q($this->id), 
        q($t)
      );
    } else {
      $query = sprintf("
        SELECT mi.*,  
               (SELECT count(pi.id) 
                  FROM purchase_item pi 
                       JOIN purchase p ON p.id = pi.purchase_id 
                 WHERE pi.day = mi.day 
                   AND p.menu_id = mi.menu_id
                   AND p.status = 'paid') as orders
          FROM menu_item mi 
         WHERE menu_id = %d
         ORDER BY mi.day",
        q($this->id)
      );
    }
    $rs = mysql_query($query);
    $items = array();
    while ($item = mysql_fetch_object($rs, 'MenuItem')) {
      array_push($items, $item);
    }
    return $items;
  }

  /**
   * Find the item for the given day.
   */
  function find_item_for_day($day) {
    $rs = mysql_query(sprintf("SELECT * FROM menu_item WHERE menu_id = %d AND day = %d", q($this->id), q($day)));
    $item = mysql_fetch_object($rs, 'MenuItem');
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

  /**
   * Get a list of purchases for this menu
   */
  function purchases($status='paid') {
    $rs = mysql_query(sprintf(
      "SELECT * FROM purchase WHERE menu_id = %d AND status = '%s' ORDER BY created_on DESC",
      q($this->id),
      q($status)
    ));
    $purchases = array();
    while ($purchase = mysql_fetch_object($rs, 'Purchase')) {
      array_push($purchases, $purchase);
    }
    return $purchases;
  }

}

/**
 *
 */
class MenuItem {
  public $id;
  public $menu_id;
  public $day;
  public $t;
  public $title;
  public $body;

  /**
   * Return the purchases of this MenuItem
   *
   * @return array<MenuItem>    purchases of this MenuItem
   */
  function purchase_items() {
    $rs = mysql_query(sprintf("
      SELECT pi.*, p.family_name
        FROM purchase_item pi
             JOIN purchase p ON p.id = pi.purchase_id
       WHERE p.status  = 'paid'
         AND p.menu_id = %d
         AND pi.day    = '%s'
       ORDER BY pi.child_grade, p.family_name, pi.child_name",
      q($this->menu_id),
      q($this->day)
    ));
    $purchase_items = array();
    while ($item = mysql_fetch_object($rs, 'PurchaseItem')) {
      array_push($purchase_items, $item);
    }
    return $purchase_items;
  }

  /**
   * Return the purchase of this MenuItem organized by grade
   * 
   * @return array              purchases of this MenuItem
   */
  function purchase_items_by_grade() {
    $purchase_items_by_grade = array();
    $purchase_items = $this->purchase_items();
    foreach ($purchase_items as $item) {
      $grade = $item->child_grade;
      if (!array_key_exists($grade, $purchase_items_by_grade)) {
        $purchase_items_by_grade[$grade] = array();
      }
      array_push($purchase_items_by_grade[$grade], $item);
    }
    return $purchase_items_by_grade;
  }
}

?>
