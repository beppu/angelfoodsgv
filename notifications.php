<?php
require_once('init.php');

/**
 * ask google for a notification by serial number
 *
 * @param  integer $serial_number   A serial number for a notification
 * @return string                   XML
 */
function google_checkout_notification($serial_number) {
  static $request_f = '<notification-history-request xmlns="http://checkout.google.com/schema/2"><serial-number>%s</serial-number></notification-history-request>';
  $body = sprintf($request_f, $serial_number);
  global $config;
  $ch = curl_init(sprintf($config->google_checkout_api_url, "reports"));
  if ($ch) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $config->google_merchant_id . ":" . $config->google_merchant_key);

    // Turn off the server and peer verification (TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // Set the request as a POST FIELD for curl.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $response = curl_exec($ch);
    if (!$response) {
      error_log("google failed: ".curl_error($ch).'('.curl_errno($ch).')');
      return false;
    } else {
      return $response;
    }
  } else {
    error_log("google failed: ".curl_error($ch).'('.curl_errno($ch).')');
    return false;
  }
}

/**
 * Return XML document that lets google know we saw the notification
 * and handled it.
 *
 * @return string   XML
 */
function google_checkout_notification_acknowledgement($serial_number) {
  static $response_f = '<notification-acknowledgment xmlns="http://checkout.google.com/schema/2" serial-number="%s" />';
  return sprintf($response_f, $serial_number);
}

/**
 * perform an action based on the notification type
 *
 * @param  DOMXpath $xpath  XPath object for a notification 
 * @return stdClass         result of action
 */
function action_for_notification($xpath) {
  $type = "";
  $r = new stdClass();
  $r->success = false;
  return $r;
}

# get serial number
$serial_number = $_POST['serial-number'];
error_log("notification $serial_number");

# ask for notification via serial number
$xml = google_checkout_notification($serial_number);

# parse notification
$doc = new DOMDocument();
$doc->loadXML($xml);
$xpath = new DOMXpath($doc);

# figure out what to do with notification
$r = action_for_notification($xpath);

# send google an xml response letting them know the notification has been handled
if ($r->success) {
  header('Status: 200');
  print google_checkout_notification_acknowledgement($serial_number);
} else {
  header('Status: 500');
  error_log("notification processing FAIL: $xml");
  error_log(var_export($r, true));
}

?>
