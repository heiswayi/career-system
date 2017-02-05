<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'jobseeker') {
  $group = 'jobseeker';
  $username = $db->sanitize($_POST['username']);
  $query = $db->query("SELECT * FROM $group WHERE username='$username'");
  // get data: username, email, name, ic, phone, address, postcode, state, course, gender, is_usm, is_undergrad
  if ($row = $db->fetch_array($query)) {
    $username = $row['username'];
    $email = $row['email'];
    $name = $row['fullname'];
    $ic = $row['ic_no'];
    $phone = $row['phone'];
    $address = $row['address'];
    $postcode = $row['postcode'];
    $state = $row['state'];
    $course = $row['study_field'];
    $gender = $row['gender'];
    $is_usm = $row['is_usm'];
    $is_undergrad = $row['is_undergrad'];
    echo $username;
    echo '[]';
    echo html_entity_decode(normalize($email));
    echo '[]';
    echo html_entity_decode(normalize($name));
    echo '[]';
    echo html_entity_decode(normalize($ic));
    echo '[]';
    echo html_entity_decode(normalize($phone));
    echo '[]';
    echo html_entity_decode(normalize($address));
    echo '[]';
    echo html_entity_decode(normalize($postcode));
    echo '[]';
    echo $state;
    echo '[]';
    echo html_entity_decode(normalize($course));
    echo '[]';
    echo $gender;
    echo '[]';
    echo $is_usm;
    echo '[]';
    echo $is_undergrad;
  } else {
    echo 'KO';
  }
} else if (isset($_POST['usergroup']) && $_POST['usergroup'] == 'employer') {
  $group = 'employer';
  $username = $db->sanitize($_POST['username']);
  $query = $db->query("SELECT * FROM $group WHERE username='$username'");
  // get data: company_name, company_info, company_web, company_logo, company_tag, person_prefix, person_name, person_position, person_phone, person_mobile, person_email, username
  if ($row = $db->fetch_array($query)) {
    $username = $row['username'];
    $cname = $row['company_name'];
    $cinfo = $row['company_info'];
    $cweb = $row['company_website'];
    $clogo = $row['company_logo_url'];
    $ctag = $row['company_tag'];
    $pprefix = $row['person_prefix'];
    $pname = $row['person_name'];
    $pposition = $row['department'];
    $pphone = $row['phone'];
    $pmobile = $row['mobile'];
    $pemail = $row['email'];
    echo html_entity_decode(normalize($cname));
    echo '[]';
    echo html_entity_decode(normalize($cinfo));
    echo '[]';
    echo html_entity_decode(normalize($cweb));
    echo '[]';
    echo html_entity_decode(normalize($clogo));
    echo '[]';
    echo html_entity_decode(normalize($ctag));
    echo '[]';
    echo html_entity_decode(normalize($pprefix));
    echo '[]';
    echo html_entity_decode(normalize($pname));
    echo '[]';
    echo html_entity_decode(normalize($pposition));
    echo '[]';
    echo html_entity_decode(normalize($pphone));
    echo '[]';
    echo html_entity_decode(normalize($pmobile));
    echo '[]';
    echo html_entity_decode(normalize($pemail));
    echo '[]';
    echo $username;
  } else {
    echo 'KO';
  }
}

$db->close();

?>