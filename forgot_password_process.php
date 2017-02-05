<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';
require_once DIR . 'includes/phpmailer.class.php';

function _checkEmail($email, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE email='$email'");
  $check = $initDB->num($query);
  if ($check == 1) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['email'])) {
  $group = $_POST['usergroup'];
  $email = $db->sanitize($_POST['email']);
  $antispam = $_POST['antispam'];
  
  // verify some parameters
  if ($antispam !== '') {
    die();
  } else if ($email == '') {
    echo 'ERROR: Please enter email address!';
  } else if (!_checkEmail($email, $group)) {
    echo 'ERROR: This email does not exist in our database!';
  } else {
    // generate new random password
    $newpass = generate_password();
    $md5newpass = md5(PW_SALT . $newpass);
    // get username of the owner of the email
    $query = $db->query("SELECT * FROM $group WHERE email='$email'");
    $row = $db->fetch_array($query);
    if ($row) { $username = $row['username']; }
    
    // send email
    $mail = new PHPMailer;
    $mail->From = 'careerfair@eng.usm.my';
    $mail->FromName = 'Career Fair App';
    $mail->AddAddress($email, $username); // Add a recipient
    $mail->IsHTML(true); // Set email format to HTML
    $mail->Subject = 'Your New Reset Password for Career System';
    $mail->Body    = '<span style="color:#888;text-style:italic">Hi, I\'m a Robot, please don\'t reply!</span>';
    $mail->Body    .= '<hr style="border:0;border-bottom:1px dashed #555;margin:20px auto">';
    $mail->Body    .= '<b><u>Career Portal - Career Fair 2014, Engineering Campus, USM</u></b>';
    $mail->Body    .= '<br/><br/>Username: <b>'.$username.'</b>';
    $mail->Body    .= '<br/>New password: <b style="color:red;">'.$newpass.'</b>';
    $mail->Body    .= '<hr style="border:0;border-bottom:1px dashed #555;margin:20px auto">';
    $mail->Body    .= 'App Name: <i>Career System 1.0</i>';
    $mail->Body    .= '<br/>App URL: <i><a href="http://careerfair.eng.usm.my/app">http://careerfair.eng.usm.my/app</a></i>';

    if(!$mail->Send()) {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
      // update the password in user database
      $db->query("UPDATE $group SET password='$md5newpass' WHERE email='$email'");
      echo 'OK';
    }
    
  }
}

?>