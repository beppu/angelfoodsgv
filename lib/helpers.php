<?php

/**
 *
 */
function redirect($location = '/', $status = 302) {
  header("Status: $status");
  header("Location: $location");
}

?>
