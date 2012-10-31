<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL ^ E_NOTICE);

require_once('lib/router.php');

new router();

?>
