<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

function _getUserID($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->fetch_array($query);
  if ($row) { return $row['id']; }
  else { return ''; }
  $initDB->close();
}

if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
  if ($_SESSION['user_group'] == 'jobseeker') {
    $jobseeker_id = _getUserID($_SESSION['user_login'], 'jobseeker');
    header('Location: jobseeker.php?id='.$jobseeker_id);
  } else if ($_SESSION['user_group'] == 'employer') {
    $employer_id = _getUserID($_SESSION['user_login'], 'employer');
    header('Location: employer.php?id='.$employer_id);
  } else {
    echo 'This page is not for admin use.';
  }
} else {
  header('Location: index.php');
}

?>