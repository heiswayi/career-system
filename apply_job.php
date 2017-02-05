<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['action'])) {
  $action = $_POST['action'];
  $j_id = $_POST['job_id'];
  $js_id = $_POST['jobseeker_id'];
  
  if ($action == 'apply') {
    $db->query("INSERT INTO apply_job (job_id, jobseeker_id, accept_status) VALUES ('$j_id', '$js_id', '2')");
    echo 'OK';
  } else if ($action == 'cancel') {
    $db->query("DELETE FROM apply_job WHERE job_id='$j_id' AND jobseeker_id='$js_id'");
    echo 'OK';
  } else {
    echo 'KO';
  }
}

$db->close();

?>