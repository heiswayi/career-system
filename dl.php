<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

function _userExist($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->num($query);
  if ($row > 0) { return true; }
  else { return false; }
  $initDB->close();
}

function _getUserID($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->fetch_array($query);
  if ($row) { return $row['id']; }
  else { return ''; }
  $initDB->close();
}

if (isset($_GET['uid']) && !empty($_GET['uid']) && is_numeric($_GET['uid'])) {
  
  $dlid = $_GET['uid'];

  if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
    if ($_SESSION['user_group'] == 'employer' || $_SESSION['user_group'] == 'admin') {
      $db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $query = $db->query("SELECT fullname FROM jobseeker WHERE id='$dlid'");
      $row = $db->fetch_array($query);
      $fullname = filename_slug($row['fullname']);
      $db->close();
        
      $filename = 'resume_repo/'.$dlid.'.pdf'; // of course find the exact filename....        
      header('Pragma: public');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Cache-Control: private', false); // required for certain browsers 
      header('Content-Type: application/pdf');
      //header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
      header('Content-Disposition: attachment; filename="'. $fullname . '";');
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: ' . filesize($filename));
      readfile($filename);
      exit;
    }
    if ($_SESSION['user_group'] == 'jobseeker') {
      if (_getUserID($_SESSION['user_login'], 'jobseeker') == $dlid) {
        $db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
        $query = $db->query("SELECT fullname FROM jobseeker WHERE id='$dlid'");
        $row = $db->fetch_array($query);
        $fullname = filename_slug($row['fullname']);
        $db->close();
        
        $filename = 'resume_repo/'.$dlid.'.pdf'; // of course find the exact filename....        
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/pdf');
        //header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
        header('Content-Disposition: attachment; filename="'. $fullname . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;

      } else {
        die('PRIVACY MATTERS! :P');
      }
    }
  } else {
    header('Location: index.php');   
  }
  
}
?>