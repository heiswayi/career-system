<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

function _checkUsername($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $check = $initDB->num($query);
  if ($check == 1) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

// check username if exists
if (isset($_POST['username'])) {
  $group = 'jobseeker';
  $username = $db->sanitize($_POST['username']);
  
  if (_checkUsername($username, $group)) {
    echo 'ERROR: This username already exists!';
  } else {
    // return 'OK' status to jQuery
    echo 'OK';
  }
}

?>