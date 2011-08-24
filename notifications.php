<?php
require_once('init.php');
require_once('lib/notification.php');

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
 * Perform an action based on the Notification type.
 * Also, save the Notification to the database.
 *
 * @param  string       $xml    XML for the notification
 * @return Notification         resulting Notification object
 */
function notification($xml) {
  $notification = new Notification(); 
  try {
    $notification->parse($xml)->handle()->create();
  }
  catch(Exception $e) {
    error_log("[notification error]\n");
    error_log($e->getMessage());
    error_log($xml);
  }
  return $notification;
}

# main _______________________________________________________________________

# get serial number
$serial_number = $_POST['serial-number'];
error_log("notification $serial_number");

# ask for notification via serial number
$xml = google_checkout_notification($serial_number);

# parse and handle the notification
$notification = notification($xml);

# send google an xml response letting them know the notification has been handled
if ($notification->is_handled) {
  header('Status: 200');
  header('Content-Type: text/xml');
  print google_checkout_notification_acknowledgement($serial_number);
} else {
  header('Status: 500');
  header('Content-Type: text/plain');
  error_log("notification processing FAIL: $xml");
  error_log(var_export($notification, true));
  echo(var_export($notification, true));
}

?>
