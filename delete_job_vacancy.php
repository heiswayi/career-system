<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

// check username if exists
if (isset($_POST['jv_id'])) {
  $id = $_POST['jv_id'];
  $db->query("DELETE FROM job_vacancy WHERE id='$id'");
  $db->query("DELETE FROM apply_job WHERE job_id='$id'");
  echo 'OK';
}

$db->close();

?>