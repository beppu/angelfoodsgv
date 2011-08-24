<?php

class Notification {
  public $id;
  public $purchase_id;
  public $serial_number;
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
    // always look at the timestamp
    // always save the raw xml
    // always set is_handled

    $this->is_handled = false;
    return $this;
  }

  /**
   * Perform an appropriate action for this Notification.
   *
   * @return boolean      true if Notification was handled successfully.
   */
  function handle() {
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
      INSERT INTO notification (serial_number, purchase_id, type, timestamp, xml, is_handled, created_on)
      VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
      q($this->serial_number), q($this->purchase_id), q($this->type), q($this->timestamp), q($this->xml), q($this->is_handled), now()
    ));
    if ($rs) {
      $this->id = mysql_insert_id();
    }
    return $rs;
  }
}

?>
