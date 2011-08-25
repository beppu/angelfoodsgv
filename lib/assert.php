#!/usr/bin/env php
<?php

require('init.php');
require('notification.php');
require('test.php');

$n = new Notification();
$xml = file_get_contents('1.xml');

$n->parse($xml);
ok($n->google_order_number,  "should have google_order_number");
ok($n->google_serial_number, "should have google_serial_number ");
ok($n->type,                 "should have type ");
ok($n->timestamp,            "should have timestamp ");
ok($n->xml,                  "should have xml ");
ok($n->is_handled == false,  "is_handled should be false ");

$n->handle();
error_log("gon: " . $n->google_order_number);
$purchase = Purchase::find_by_google_order_number($n->google_order_number);
ok($purchase,                      "should be able to load Purchase object");
ok($purchase->google_order_number, "Purchase should have a google_order_number");
ok($n->is_handled,                 "is_handled should now be true");

$n->create();
ok($n->id, "should have an id");

?>
