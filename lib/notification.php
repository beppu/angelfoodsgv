<?php

class Notification {
  public $id;
  public $google_serial_number;
  public $google_order_number;
  public $type;
  public $timestamp;
  public $xml;
  public $is_handled;
  public $created_on;
  public $modified_on;

  // DOMDocument
  public $xpath;

  /**
   * Fill in a Notification object in memory from an XML document.
   *
   * @param string $xml   XML for Notification
   */
  function parse($xml) {

    // parse notification
    $doc = new DOMDocument();
    $doc->loadXML($xml);
    $xpath = new DOMXpath($doc);
    $xpath->registerNamespace("g", "http://checkout.google.com/schema/2");

    // store $xpath for later
    $this->xpath = $xpath;

    // always look at the root node to find type
    $list = $xpath->query('/');
    $root = $list->item(0)->documentElement;
    $this->type = $root->tagName;
    $this->google_serial_number = $root->getAttribute('serial-number');

    // order number
    $list = $xpath->query('//g:google-order-number');
    $this->google_order_number = $list->item(0)->nodeValue;

    // always look at the timestamp
    $list = $xpath->query('//g:timestamp');
    $this->timestamp = $list->item(0)->nodeValue;

    // always save the raw xml
    $this->xml = $xml;

    // always set is_handled to false
    $this->is_handled = false;

    return $this;
  }

  /**
   * Perform an appropriate action for this Notification.
   * This isn't OO, but being OO would be bloated.
   * It would be designing for something that will never come.
   * This will remain a small system, because that's what makes the most sense to me.
   *
   * @return boolean      true if Notification was handled successfully.
   */
  function handle() {
    $xpath = $this->xpath;
    
    error_log("type: " . $this->type);
    switch ($this->type) {
      /* */
      case "new-order-notification":
        // associate a google order number to a Purchase
        // look for the receipt_id we stashed in the merchant-note element
        $nl - $xpath->query('//g:merchant-note');
        if ($nl->length > 0) {
          $receipt_id = $nl->item(0)->nodeValue;
          $purchase   = Purchase::find_by_receipt_id($receipt_id);
          $purchase->google_order_number($this->google_order_number);
          $purchase->update();
        } else {
          $sn = $this->google_serial_number;
          error_log("google_serial_number: $sn // hope this is a debug new-order-notification event");
        }
        break;

      /* */
      case "order-state-change-notification":
        // IF   new-financial-order-state is CHARGED 
        // THEN set the associated Purchase to paid
        $nl - $xpath->query('//g:new-financial-order-state');
        if ($nl->length > 0) {
          $state = $nl->item(0)->nodeValue;
          if ($state == "CHARGED") {
            error_log("CHARGED " . $this->google_serial_number);
            $purchase = Purchase::find_by_google_order_number($this->google_order_number);
            $purchase->paid();
          }
        } else {
          $sn = $this->google_serial_number;
          error_log("google_serial_number: $sn // no order-state-change-notification?");
        }
        break;

      default:
        // We don't care about any other tags for now.
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
      INSERT INTO notification (google_serial_number, google_order_number, type, timestamp, xml, is_handled, created_on)
      VALUES ('%s', '%s', '%s', '%s', '%s', %s, '%s')",
      q($this->google_serial_number), q($this->google_order_number), q($this->type), q($this->timestamp), q($this->xml), ($this->is_handled ? 'true' : 'false'), now()
    ));
    if ($rs) {
      $this->id = mysql_insert_id();
    }
    return $rs;
  }
}

?>
