<?php

/**
 * What is a purchase?
 */
class Purchase {
  public $id;
  public $menu_id;
  public $session_id;
  public $family_name;
  public $phone_number;
  public $status;
  public $created_on;
  public $modified_on;

  /**
   * Retrieve a purchase from the database by id.
   *
   * @param   integer $id   the purchase id
   * @return  Menu          a Menu object
   */
  static function find($id) {
    $rs = mysql_query(sprintf("SELECT * FROM purchase WHERE id = %d", q($id)));
    $menu = mysql_fetch_object($rs, 'Menu');
    return $menu;
  }

  /**
   * Insert an purchase into the database.
   *
   * @return  boolean       Was the insert successful?
   */
  function create() {
    $rs = mysql_query(sprintf("INSERT INTO purchase (menu_id, session_id, family_name, phone_number, status, created_on) VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
      q($this->menu_id), q($this->session_id), q($this->family_name), q($this->phone_number), q($this->status), now()
    ));
    if ($rs) {
      $this->id = mysql_insert_id();
      $rs2 = mysql_query(sprintf("UPDATE purchase SET receipt_id = '%s'", sha1($this->id)));
    }
    return $rs;
  }


  /**
   * Add an item to the purchase
   *
   * @param   PurchaseItem $item    the thing that is being bought
   * @return  boolean               Was the insert successful?
   */
  function add_item($item) {
    $rs = mysql_query(sprintf("INSERT INTO purchase_item (purchase_id, day, child_name, child_grade, price, created_on) VALUES (%d, %d, '%s', %d, %0.2f, '%s')",
      q($this->id), q($item->day), q($item->child_name), q($item->child_grade), $item->price, now()
    ));
    $item->id = mysql_insert_id();
    return $rs;
  }
}

/**
 * What is a purchase item?
 */
class PurchaseItem {
  public $id;
  public $purchase_id;
  public $day;
  public $child_name;
  public $child_grade;
  public $item_name;
  public $item_price;

}

?>
