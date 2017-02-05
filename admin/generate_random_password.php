<?php

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/functions.php';

if (isset($_POST['generate']) == '1') {
  echo generate_password();
}

?>