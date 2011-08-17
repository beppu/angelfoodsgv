<?php
#
# This is a command-line install script that sets up the initial menu.
#
# Usage:
#   php install.php
#

require('init.php');
global $config;
$menu = new Menu();
$menu->year = 2011;
$menu->month = 9;
$menu->regular_price = $config->default_regular_price;
$menu->double_price  = $config->default_double_price;
$menu->create();
Menu::set_current($menu->id);
print_r($menu);
?>
