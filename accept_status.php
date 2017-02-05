<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

// check username if exists
if (isset($_POST['status'])) {
  $status = $db->sanitize($_POST['status']);
  $job_id = $db->sanitize($_POST['jid']);
  $js_id = $db->sanitize($_POST['jsid']);
  $db->query("UPDATE apply_job SET accept_status='$status' WHERE job_id='$job_id' AND jobseeker_id='$js_id'");
  echo 'OK';
}

$db->close();

?>