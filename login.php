<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

function _checkDB($username, $password, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->fetch_array($query);
  if ($row && $row['password'] == $password) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

// user logged in as jobseeker
if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'jobseeker') {
  $group = 'jobseeker';
  $username = $db->sanitize($_POST['username']);
  $password = $db->sanitize($_POST['password']);
  $antispam = $_POST['antispam'];
  $hashed_password = md5(PW_SALT . $password);
  
  // verify some parameters
  if ($antispam !== '') {
    die();
  } else if ($username == '' || $password == '') {
    echo 'ERROR: Username and password are required!';
  } else if (!_checkDB($username, $hashed_password, $group)) {
    echo 'ERROR: Username and password do not match!';
  } else {
    // login is success, set session
    $_SESSION['user_login'] = $username;
    $_SESSION['user_group'] = $group;
    
    // set cookie if checkbox 'Remember Me' is ticked
    if (isset($_POST['remember']) == 1) {
      //set the cookies for 30 days, ie, 30*24*60*60 secs
      setcookie('username', $username, time() + 30*24*60*60);
      setcookie('password', $hashed_password, time() + 30*24*60*60);
      setcookie('group', $group, time() + 30*24*60*60);
    } else {
      //destroy any previously set cookie
      setcookie('username', '', time() - 30*24*60*60);
      setcookie('password', '', time() - 30*24*60*60);
      setcookie('group', '', time() - 30*24*60*60);
    }
    // return 'OK' status to jQuery
    echo 'OK';
  }
}
// user logged in as employer
if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'employer') {
  $group = 'employer';
  $username = $db->sanitize($_POST['username']);
  $password = $db->sanitize($_POST['password']);
  $antispam = $_POST['antispam'];
  $hashed_password = md5(PW_SALT . $password);
  
  // verify some parameters
  if ($antispam !== '') {
    die();
  } else if ($username == '' || $password == '') {
    echo 'ERROR: Username and password are required!';
  } else if (!_checkDB($username, $hashed_password, $group)) {
    echo 'ERROR: Username and password do not match!';
  } else {
    // login is success, set session
    $_SESSION['user_login'] = $username;
    $_SESSION['user_group'] = $group;
    
    // set cookie if checkbox 'Remember Me' is ticked
    if (isset($_POST['remember']) == 1) {
      //set the cookies for 30 days, ie, 30*24*60*60 secs
      setcookie('username', $username, time() + 30*24*60*60);
      setcookie('password', $hashed_password, time() + 30*24*60*60);
      setcookie('group', $group, time() + 30*24*60*60);
    } else {
      //destroy any previously set cookie
      setcookie('username', '', time() - 30*24*60*60);
      setcookie('password', '', time() - 30*24*60*60);
      setcookie('group', '', time() - 30*24*60*60);
    }
    // return 'OK' status to jQuery
    echo 'OK';
  }
}

?>