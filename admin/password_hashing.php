<?php

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/config.php';

if (isset($_POST['input'])) {
  $plainpass = $_POST['input'];
  $hashed = md5(PW_SALT . $plainpass);
  echo $hashed;
}

?>