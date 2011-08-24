<?php

class Notification {
  public $id;
  public $purchase_id;
  public $google_serial_number;
  public $google_order_number;
  public $type;
  public $timestamp;
  public $xml;
  public $is_handled;
  public $created_on;
  public $modified_on;

  /**
   * Fill in a Notification object in memory from an XML document.
   *
   * @param string $xml   XML for Notification
   */
  function parse($xml) {
    # parse notification
    $doc = new DOMDocument();
    $doc->loadXML($xml);
    $xpath = new DOMXpath($doc);
    $xpath->registerNamespace("g", "http://checkout.google.com/schema/2");

    // always look at the root node to find type
    $list = $xpath->query('/');
    $node = $list[0];
    $this->type = "";

    // order number
    $list = $xpath->query('/g:google-order-number');
    $node = $list[0];
    $this->google_order_number = null;

    // always look at the timestamp
    $list = $xpath->query('/g:timestamp');
    $this->timestamp = "";

    // always save the raw xml
    $this->xml = $xml;

    // always set is_handled to false
    $this->is_handled = false;

    return $this;
  }

  /**
   * Perform an appropriate action for this Notification.
   *
   * @return boolean      true if Notification was handled successfully.
   */
  function handle() {
    
    error_log("type: " . $this->type);
    switch ($this->type) {
      case "new-order-notification":
        // TODO - associate a google order number to a Purchase
        // look for the receipt_id we stashed in merchant-notes
        break;

      case "order-state-change-notification":
        // TODO - if new-financial-order-state is CHARGED 
        // then set the associated Purchase to paid

        // XXX - In Purchase, I don't need serial number..  just need order number.
        break;

      default:
    }

    $this->is_handled = true;
    return $this;
  }

  /**
   * Insert a Notification into the database for our own records.
   *
   * return boolean   true when the insert succeeded.
   */
  function create() {
    $rs = mysql_query(sprintf("
      INSERT INTO notification (google_serial_number, purchase_id, type, timestamp, xml, is_handled, created_on)
      VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
      q($this->google_serial_number), q($this->purchase_id), q($this->type), q($this->timestamp), q($this->xml), q($this->is_handled), now()
    ));
    if ($rs) {
      $this->id = mysql_insert_id();
    }
    return $rs;
  }
}

?>
