<?php


/**
 * What is a purchase?
 */
class Purchase {
  public $id;
  public $menu_id;
  public $session_id;
  public $receipt_id;
  public $google_serial_number;
  public $family_name;
  public $phone_number;
  public $status;
  public $created_on;
  public $modified_on;

  public static $google_checkout_purchase_f = "
  <?xml version=\"1.0\" encoding=\"UTF-8\"?>
  <checkout-shopping-cart xmlns=\"http://checkout.google.com/schema/2\">
    <shopping-cart>
      <items>
  %s
      </items>
    </shopping-cart>
    <checkout-flow-support>
      <merchant-checkout-flow-support>
        <shipping-methods>
          <pickup name=\"Pickup\">
            <price currency=\"USD\">0.00</price>
          </pickup>
        </shipping-methods>
      </merchant-checkout-flow-support>
    </checkout-flow-support>
  </checkout-shopping-cart>
  ";

  public static $google_checkout_purchase_item_f = "
        <item>
          <item-name>%s</item-name>
          <item-description>%s</item-description>
          <unit-price currency=\"USD\">%.2f</unit-price>
          <quantity>%d</quantity>
        </item>
  ";

  /**
   * Retrieve a purchase from the database by id.
   *
   * @param   integer $id   the purchase id
   * @return  Purchase      a Purchase object
   */
  static function find($id) {
    $rs = mysql_query(sprintf("SELECT * FROM purchase WHERE id = %d", q($id)));
    $purchase = mysql_fetch_object($rs, 'Purchase');
    return $purchase;
  }

  /**
   * Retrieve a purchase from the database by receipt_id.
   *
   * @param   string  $id   the receipt_id of this purchase
   * @return  Purchase      a Purchase object
   */
  static function find_by_receipt_id($receipt_id) {
    $rs = mysql_query(sprintf("SELECT * FROM purchase WHERE receipt_id = '%s'", q($receipt_id)));
    $purchase = mysql_fetch_object($rs, 'Purchase');
    return $purchase;
  }

  /**
   * Insert an purchase into the database.
   *
   * @return  boolean       Was the insert successful?
   */
  function create() {
    $rs = mysql_query(sprintf("
      INSERT INTO purchase (menu_id, session_id, family_name, phone_number, status, created_on)
      VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
      q($this->menu_id), q($this->session_id), q($this->family_name), q($this->phone_number), q($this->status), now()
    ));
    if ($rs) {
      $this->id = mysql_insert_id();
      $receipt_id = sha1($config->receipt_id_salt . $this->id);
      $rs2 = mysql_query(sprintf("UPDATE purchase SET receipt_id = '%s' WHERE id = %d", q($receipt_id), q($this->id)));
      if ($rs2) {
        $this->receipt_id = $receipt_id;
      }
      return $rs2;
    }
    return $rs;
  }

  /**
   * Perform a limited update of the purchase record.
   * (If we need more fields updated, this method will be updated.)
   *
   * @return boolean        Was the update successful?
   */
  function update() {
    $rs = mysql_query(sprintf(
      "UPDATE purchase SET google_serial_number = '%s' WHERE id = %d",
      q($this->google_serial_number), q($this->id)
    ));
    return $rs;
  }

  /**
   * Return the Menu object associated with this purchase.
   *
   * @return Menu     Menu object for this purchase
   */
  function menu() {
    return Menu::find($this->menu_id);
  }

  /**
   * Cancel this order.
   *
   * @return  boolean       Was the update successful?
   */
  function cancel() {
    $rs = mysql_query(sprintf("UPDATE purchase SET status = 'cancelled' WHERE id = %d", q($this->id)));
    if ($rs) {
      $this->status = 'cancelled';
    }
    return $rs;
  }

  /**
   * Note that this purchase has been paid for.
   *
   * @return  boolean       Was the update successful?
   */
  function paid() {
    $rs = mysql_query(sprintf("UPDATE purchase SET status = 'paid' WHERE id = %d", q($this->id)));
    if ($rs) {
      $this->status = 'paid';
    }
    return $rs;
  }

  /**
   * Add an item to the purchase
   *
   * @param   PurchaseItem  $item   the thing that is being bought
   * @return  boolean               Was the insert successful?
   */
  function add_item($item) {
    $rs = mysql_query(sprintf("
      INSERT INTO purchase_item (purchase_id, day, t, child_name, child_grade, price, created_on)
      VALUES (%d, %d, '%s', '%s', %d, %0.2f, '%s')",
      q($this->id), q($item->day), q($item->t), q($item->child_name), q($item->child_grade), $item->price, now()
    ));
    $item->id = mysql_insert_id();
    return $rs;
  }

  /**
   * Get items
   *
   * @return  array                 an array of PurchaseItem objects.
   */
  function items() {
    $rs = mysql_query(sprintf("
      SELECT pi.*,
             mi.title
        FROM purchase_item pi
             JOIN purchase p   ON pi.purchase_id = p.id
             JOIN menu m       ON p.menu_id = m.id
             JOIN menu_item mi ON (m.id = mi.menu_id AND pi.day = mi.day)
       WHERE purchase_id = %d
       ORDER BY pi.id",
      q($this->id)
    ));
    $items = array();
    while ($item = mysql_fetch_object($rs, 'PurchaseItem')) {
      array_push($items, $item);
    }
    return $items;
  }

  /**
   * Get items organized by child.
   *
   * @return  array                 an array of array of PurchaseItem objects keyed by child_name
   */
  function items_by_child() {
    $items = $this->items();
    if (!$items) return false;

    $items_by_child = array();
    foreach ($items as $i) {
      $key = sprintf('%s|%s', $i->child_name, $i->child_grade);
      if (!array_key_exists($key, $items_by_child)) {
        $items_by_child[$key] = array();
      }
      array_push($items_by_child[$key], $i);
    }
    return $items_by_child;
  }

  /**
   * Total amount of this purchase
   *
   * @return  float                 dollar amount of this purchase
   */
  function amount() {
    $rs = mysql_query(sprintf("SELECT sum(price) as usd FROM purchase_item WHERE purchase_id = %d", q($this->id)));
    if ($rs) {
      $amount = mysql_fetch_object($rs);
      return $amount->usd;
    } else {
      return $rs;
    }
  }

  /**
   * Summary of items suitable for sending to a payment processing system.
   *
   * @return array[stdClass] summarized list of items
   */
  function item_summary() {
    $menu = Menu::find($this->menu_id);
    if (!$menu) {
      error_log("Menu not found");
      return false;
    }

    $rs = mysql_query(sprintf("
      SELECT purchase_id, child_name, child_grade, t,
             SUM(price) AS price,
             COUNT(t)   AS quantity
        FROM purchase_item
       WHERE purchase_id = %d
       GROUP BY purchase_id, child_name, child_grade, t
       ORDER BY child_name, child_grade, t",
      q($this->id)
    ));

    $items = array();
    while ($s = mysql_fetch_object($rs)) {
      $item = new stdClass();
      $item->name     = sprintf("%s %s - %s Meals", $s->child_name, grade($s->child_grade), ucfirst($s->t));
      $item->amount   = ($s->t == "regular") ? $menu->regular_price : $menu->double_price;
      $item->quantity = $s->quantity;
      array_push($items, $item);
    }
    return $items;
  }

  /**
   * Send a POST request to PayPal
   *
   * @param   string  $api_method   Name of PayPal API method
   * @param   array   $params       POST parameters
   * @param   array   $headers      HTTP headers
   * @return  array                 PayPal API response as key/value pairs
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
        urlencode($config->paypal_api_version),
        urlencode($config->paypal_api_user),
        urlencode($config->paypal_api_password),
        urlencode($config->paypal_api_signature),
        $params_as_string
      );

      // Set the request as a POST FIELD for curl.
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
      $response = curl_exec($ch);
      if (!$response) {
        error_log("$api_method failed: ".curl_error($ch).'('.curl_errno($ch).')');
        return false;
      } else {
        $r = array();
        $kv_list = explode("&", $response);
        foreach ($kv_list as $kv) {
          $pair = explode("=", $kv);
          if (count($pair) > 1) {
            $r[$pair[0]] = $pair[1];
          }
        }
        if((0 == sizeof($r)) || !array_key_exists('ACK', $r)) {
          error_log("Invalid HTTP Response for POST request($post_body)");
          return false;
        }
        return $r;
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
  function paypal_set_express_checkout($options=null) {
    global $config;
    $amount  = $this->amount();
    $details = array(
      "PAYMENTACTION"     => "Sale",
      "RETURNURL"         => sprintf("http://%s/review.php?r=%s", $_SERVER['HTTP_HOST'], urlencode($this->receipt_id)),
      "CANCELURL"         => sprintf("http://%s/cancel.php?r=%s", $_SERVER['HTTP_HOST'], urlencode($this->receipt_id)),
      "ITEMAMT"           => $amount,
      "TAXAMT"            => 0.00,
      "SHIPPINGAMT"       => 0.00,
      "HANDLINGAMT"       => 0.00,
      "NOSHIPPING"        => 1,
      "ALLOWNOTE"         => 0,
      "GIFTMESSAGEENABLE" => 0,
      "AMT"               => $amount,
    );
    $menu = Menu::find($this->menu_id);
    if (!$menu) {
      error_log("Menu not found");
      return false;
    }
    $rs = mysql_query(sprintf("
      SELECT purchase_id, child_name, child_grade, t,
             SUM(price) AS price,
             COUNT(t)   AS quantity
        FROM purchase_item
       WHERE purchase_id = %d
       GROUP BY purchase_id, child_name, child_grade, t
       ORDER BY child_name, child_grade, t",
      q($this->id)
    ));
    $i = 0;
    while ($s = mysql_fetch_object($rs)) {
      $details["L_NAME$i"] = sprintf("%s %s - %s Meals", $s->child_name, grade($s->child_grade), ucfirst($s->t));
      $details["L_AMT$i"]  = ($s->t == "regular") ? $menu->regular_price : $menu->double_price;
      $details["L_QTY$i"]  = $s->quantity;
      $i++;
    }
    error_log(var_export($details, true));
    $response = $this->paypal_post('SetExpressCheckout', $details);
    if("SUCCESS" == strtoupper($response["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response["ACK"])) {
      $token = urldecode($response["TOKEN"]);
      $paypal_url = sprintf($config->paypal_api_redirect_url, $token);
      error_log("TOKEN    : $token");
      error_log("REDIRECT : $paypal_url");
      return $paypal_url;
    } else {
      error_log("ERROR: " . var_export($response, true));
      return false;
    }
  }

  /**
   * Finalize the purchase
   *
   * @return boolean      Was the purchase successful?
   */
  function paypal_do_express_checkout($token, $payer_id) {
    global $config;
    $amount  = $this->amount();
    $details = array(
      "TOKEN"         => $token,
      "PAYERID"       => $payer_id,
      "PAYMENTACTION" => "Sale",
      "AMT"           => $amount,
    );
    error_log("DETAILS: " . var_export($details, true));
    $response = $this->paypal_post('DoExpressCheckoutPayment', $details);
    if("SUCCESS" == strtoupper($response["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response["ACK"])) {
      // now what?
      error_log("SUCCESS: " . var_export($response, true));
      $this->paid();
      return true;
    } else {
      error_log("ERROR: " . var_export($response, true));
      return false;
    }
  }

  /**
   * Issue a refund on this purchase
   *
   * @return ?
   */
  function paypal_refund($options=null) {
    global $config;
    // TODO
  }

  /**
   * Send a POST request to Google Checkout
   *
   * @param  string $xml
   * @param  array  $headers
   * @return string $response
   */
  function google_checkout_post($xml="", $headers=null) {
    global $config;
    $ch = curl_init($config->google_checkout_api_url);
    if ($ch) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERPWD, $config->google_merchant_id . ":" . $config->google_merchant_key);

      // Turn off the server and peer verification (TrustManager Concept).
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

      $post_body = $xml;

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
   * Send an order to google checkout
   *
   * @return string Google Checkout redirect URL
   */
  function google_checkout_purchase() {
    global $config;

    $items = $this->item_summary();
    $item_xml = "";
    foreach ($items as $i) {
      $item_xml .= sprintf(
        Purchase::$google_checkout_purchase_item_f,
        "Lunch",
        $i->name,
        $i->amount,
        $i->quantity
      );
    }

    $purchase_xml = sprintf(Purchase::$google_checkout_purchase_f, $item_xml);
    $response     = $this->google_checkout_post($purchase_xml);

    $serial_number = null;
    $redirect_url  = null;

    if ($response) {
      $doc = new DOMDocument();
      $doc->loadXML($response);
      $xpath = new DOMXPath($doc);
      $nodes = $xpath->query('//*');
      foreach ($nodes as $n) {
        if ($n->nodeName == 'checkout-redirect') {
          $serial_number = $n->attributes->getNamedItem('serial-number')->nodeValue;
        } elseif ($n->nodeName == 'redirect-url') {
          $redirect_url = $n->nodeValue;
        }
      }

      if ($serial_number && $redirect_url) {
        // success
        $this->google_serial_number = $serial_number;
        $this->update();
        return $redirect_url;
      } else {
        error_log("google_checkout_purchase: bad response");
        error_log($response);
        return false;
      }

    } else {
      error_log("google_checkout_purchase: no response");
      return false;
    }
  }
}

/**
 * An object representing one meal for one student on a particular day.
 * A Purchase has many PurchaseItems.
 */
class PurchaseItem {
  public $id;
  public $purchase_id;
  public $day;
  public $child_name;
  public $child_grade;
  public $t;
  public $title;

}

?>
