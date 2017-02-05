<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

// check username if exists
if (isset($_POST['jv_id'])) {
  $id = $_POST['jv_id'];
  $status = $_POST['status'];
  if ($status == 1) {
    $db->query("UPDATE job_vacancy SET is_open='1' WHERE id='$id'");
    echo 'OK';
  }
  if ($status == 0) {
    $db->query("UPDATE job_vacancy SET is_open='0' WHERE id='$id'");
    echo 'OK';
  }
}

$db->close();

?>