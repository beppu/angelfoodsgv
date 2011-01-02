<?php

/**
 * What is an order?
 */
class Order {
  public $id;
  public $menu_id;
  public $session_id;
  public $family_name;
  public $phone_number;
  public $status;
  public $created_on;
  public $modified_on;

  /**
   * Retrieve an order from the database by id.
   *
   * @param   integer $id   the order id
   * @return  Menu          a Menu object
   */
  static function find($id) {
    $rs = mysql_query(sprintf("SELECT * FROM order WHERE id = %d", q($id)));
    $menu = mysql_fetch_object($rs, 'Menu');
    return $menu;
  }

  /**
   * Insert an order into the database.
   *
   * @return  boolean       Was the insert successful?
   */
  function create() {
    $rs = mysql_query(sprintf("INSERT INTO order (menu_id, session_id, family_name, phone_number, status, created_on) VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
      q($this->menu_id), q($this->session_id), q($this->family_name), q($this->phone_number), q($this->status), now()
    ));
    $this->id = mysql_insert_id();
    return $rs;
  }
}

/**
 * What is an order item?
 */
class OrderItem {
  public $id;
  public $order_id;
  public $day;
  public $child_name;
  public $child_grade;
  public $item_name;
  public $item_price;

}

?>
