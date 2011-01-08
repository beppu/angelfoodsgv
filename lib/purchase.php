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

  /**
   * Send a POST request to PayPal
   *
   * @param   string  $api_method   Name of PayPal API method
   * @param   array   $params       POST parameters
   * @param   array   $headers      HTTP headers
   * @return  string                PayPal API response
   */
  function paypal_post($api_method, $params="", $headers=null) {
    global $config;
    $ch = curl_init($config->paypal_api_url);
    if ($ch) {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      // Turn off the server and peer verification (TrustManager Concept).
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

      $params_as_string = "";
      if (is_array($params)) {
        foreach ($params as $k => $v) {
          $params_as_string .= "&" . urlencode($k) . "=" . urlencode($v);
        }
      } else {
        $params_as_string = $params;
      }

      $post_body = sprintf(
        'METHOD=%s&VERSION=%s&USER=%s&PWD=%s&SIGNATURE=%s%s',
        $api_method,
        $config->paypal_api_version,
        $config->paypal_api_user,
        $config->paypal_api_password,
        $config->paypal_api_signature,
        $params_as_string
      );

      // Set the request as a POST FIELD for curl.
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
      $response = curl_exec($ch);
      if (!$response) {
        error_log("$api_method failed: ".curl_error($ch).'('.curl_errno($ch).')');
        return false;
      } else {
        return $response;
      }
    } else {
      error_log("$api_method failed: ".curl_error($ch).'('.curl_errno($ch).')');
      return false;
    }
  }

  /**
   * Send purchase information to PayPal
   *
   * @return  string                PayPal redirect url
   */
  function paypal_checkout($options=null) {
    $response = $this->paypal_post('SetExpressCheckout', array());
  }

  /**
   * Issue a refund on this purchase
   *
   * @return ?
   */
  function paypal_refund($options=null) {
    // TODO
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
