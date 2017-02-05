<?php

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['action'])) {
  $id = $_POST['rlid'];
  if ($_POST['action'] == 'done') {
    $db->query("UPDATE request_loginid SET status='1' WHERE id='$id'");
    echo 'OK';
  }
  if ($_POST['action'] == 'delete') {
    $db->query("DELETE FROM request_loginid WHERE id='$id'");
    echo 'OK';
  }
}

$db->close();

?>