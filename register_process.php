<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

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

function _checkEmail($email, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE email='$email'");
  $check = $initDB->num($query);
  if ($check == 1) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['usergroup'])) {
  $group = $_POST['usergroup'];
  $username = $db->sanitize($_POST['username']);
  $password = $db->sanitize($_POST['password']);
  $email = $db->sanitize($_POST['email']);
  $fullname = $db->sanitize($_POST['name']);
  $gender = $db->sanitize($_POST['gender']);
  $ic = $db->sanitize($_POST['icno']);
  $phone = $db->sanitize($_POST['phone']);
  $address = $db->sanitize($_POST['address']);
  $postcode = $db->sanitize($_POST['postcode']);
  $state = $db->sanitize($_POST['state']);
  $usm = $db->sanitize($_POST['usm']);
  $edu = $db->sanitize($_POST['education']);
  $course = $db->sanitize($_POST['course']);
  $antispam = $_POST['antispam'];
  
  // verify some parameters
  if ($antispam !== '') {
    die();
  } else if ($username == '' || $password == '' || $email == '' || $fullname == '' || $gender == '' || $ic == '' || $phone == '' || $address == '' || $postcode == '' || $state == '' || $usm == '' || $edu == '' || $course == '') {
    echo 'ERROR: All fields are required!';
  } else if (!username($username)) {
    echo 'ERROR: Only alphanumerics and underscore are allowed for username.';
  } else if (_checkUsername($username, $group)) {
    echo 'ERROR: The username has been registered by someone else!';
  } else if (_checkEmail($email, $group)) {
    echo 'ERROR: The email has been used by someone else! The email must be unique for each user account.';
  } else {
  
    if ($usm == 'Yes') { $is_usm = '1'; }
    else { $is_usm = '0'; }
    
    if ($edu == 'Undergraduate') { $is_undergrad = '1'; }
    else if ($edu == 'Postgraduate') { $is_undergrad = '0'; }
    else { $is_undergrad = '2'; }
    
    $reg_date = time();
    $md5password = md5(PW_SALT . $password);
    
    $db->query("INSERT INTO jobseeker (username, password, email, ic_no, fullname, is_usm, study_field, is_undergrad, phone, address, postcode, state, reg_date, has_resume, resume_path, gender) VALUES ('$username', '$md5password', '$email', '$ic', '$fullname', '$is_usm', '$course', '$is_undergrad', '$phone', '$address', '$postcode', '$state', '$reg_date', '0', '', '$gender')");
    
    // set session to user
    $_SESSION['user_login'] = $username;
    $_SESSION['user_group'] = $group;
    
    // return 'OK' status to jQuery
    echo 'OK';
  }
}

?>