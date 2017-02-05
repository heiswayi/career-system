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

if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'jobseeker') {
  $group = 'jobseeker';
  $username = $db->sanitize($_POST['username']);
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
  
  // verify some parameters
  if ($username == '' || $email == '' || $fullname == '' || $gender == '' || $ic == '' || $phone == '' || $address == '' || $postcode == '' || $state == '' || $usm == '' || $edu == '' || $course == '') {
    echo 'ERROR: All fields are required!';
  } else {
    if ($usm == 'Yes') { $is_usm = '1'; }
    else { $is_usm = '0'; }
    
    if ($edu == 'Undergraduate') { $is_undergrad = '1'; }
    else if ($edu == 'Postgraduate') { $is_undergrad = '0'; }
    else { $is_undergrad = '2'; }
    
    $db->query("UPDATE jobseeker SET email='$email', ic_no='$ic', fullname='$fullname', is_usm='$is_usm', study_field='$course', is_undergrad='$is_undergrad', phone='$phone', address='$address', postcode='$postcode', state='$state', gender='$gender' WHERE username='$username'");
    // return 'OK' status to jQuery
    echo 'OK';
  }
}

if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'employer') {
  $group = 'employer';
  $username = $db->sanitize($_POST['username']);
  $cname = $db->sanitize($_POST['cname']);
  $cinfo = $db->sanitize($_POST['cinfo']);
  $cweb = $db->sanitize($_POST['cweb']);
  $clogo = $db->sanitize($_POST['clogo']);
  $ctag = $db->sanitize($_POST['ctag']);
  $pprefix = $db->sanitize($_POST['pprefix']);
  $pname = $db->sanitize($_POST['pname']);
  $pphone = $db->sanitize($_POST['pphone']);
  $pmobile = $db->sanitize($_POST['pmobile']);
  $pemail = $db->sanitize($_POST['pemail']);
  $pposition = $db->sanitize($_POST['pposition']);
  
  // verify some parameters
  if ($cname == '') {
    echo 'ERROR: Company Name is required!';
  } else if ($pname == '') {
    echo 'ERROR: Person Name to be contacted is required!';
  } else if ($pemail == '') {
    echo 'ERROR: Contact Email is required!';
  } else {
    $db->query("UPDATE employer SET company_name='$cname', company_info='$cinfo', company_website='$cweb', company_logo_url='$clogo', company_tag='$ctag', person_prefix='$pprefix', person_name='$pname', phone='$pphone', mobile='$pmobile', email='$pemail', department='$pposition' WHERE username='$username'");
    // return 'OK' status to jQuery
    echo 'OK';
  }
}

?>