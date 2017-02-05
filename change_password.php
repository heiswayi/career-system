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

function _checkPassword($username, $password, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->fetch_array($query);
  if ($row && $row['password'] == $password) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['submit']) && !empty($_POST)) {
  $oldpass = $db->sanitize($_POST['oldpass']);
  $newpass = $db->sanitize($_POST['newpass']);
  $cnewpass = $db->sanitize($_POST['cnewpass']);
  $username = $db->sanitize($_POST['username']);
  $group = $db->sanitize($_POST['group']);
  $md5oldpass = md5(PW_SALT . $oldpass);
  $md5newpass = md5(PW_SALT . $cnewpass);
  
  if ($oldpass == '' || $newpass == '' || $cnewpass == '') {
    $res = '<div id="notifyError" class="alert alert-error">ERROR: All fields are required.</div>';
    renderForm($oldpass, $newpass, $cnewpass, $username, $group, $res);
  } else if (!_checkPassword($username, $md5oldpass, $group)) {
    $res = '<div id="notifyError" class="alert alert-error">ERROR: Invalid current password!</div>';
    renderForm($oldpass, $newpass, $cnewpass, $username, $group, $res);
  } else if ($cnewpass !== $newpass) {
    $res = '<div id="notifyError" class="alert alert-error">ERROR: Confirm New Password does not match with New Password.</div>';
    renderForm($oldpass, $newpass, $cnewpass, $username, $group, $res);
  } else {
    $db->query("UPDATE $group SET password='$md5newpass' WHERE username='$username'");
    $res = '<div id="notifySuccess" class="alert alert-success">Your new password has been updated. Please re-login! In 10 seconds you will be automatically logged out.</div>';
    renderForm($oldpass, $newpass, $cnewpass, $username, $group, $res);
    sleep(10);
    header('Location: logout.php');
  }
} else {
  if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
    if ($_SESSION['user_group'] == 'jobseeker') {
      if(!_userExist($_SESSION['user_login'], 'jobseeker')) { header('Location: index.php'); }
      else {
        $username = $_SESSION['user_login'];
        $group = 'jobseeker';
        renderForm('', '', '', $username, $group, '');
      }
    } else if ($_SESSION['user_group'] == 'employer') {
      if(!_userExist($_SESSION['user_login'], 'employer')) { header('Location: index.php'); }
      else {
        $username = $_SESSION['user_login'];
        $group = 'employer';
        renderForm('', '', '', $username, $group, '');
      }
    } else { header('Location: index.php'); }
  } else { header('Location: index.php'); }
}

function renderForm($oldpass, $newpass, $cnewpass, $username, $group, $res) {

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <?php html_head_js(); ?>
  </head>

  <body>

    <?php html_body_topmenu(); ?>
    <?php html_body_header('Change Password'); ?>

    <div class="container">

      <div class="row">
      
      <div class="span9">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Change Password</h4>
          
<form class="form-horizontal change-password-form" style="margin:20px" method="post" action="">

<?php echo $res; ?>
  
  <div class="control-group">
    <label class="control-label" for="oldPassword">Current Password:</label>
    <div class="controls">
      <input type="password" id="oldPassword" name="oldpass" class="span4" value="<?php echo $oldpass; ?>" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="newPassword">New Password:</label>
    <div class="controls">
      <input type="password" id="newPassword" name="newpass" class="span4" value="<?php echo $newpass; ?>" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="cnewPassword">Confirm New Password:</label>
    <div class="controls">
      <input type="password" id="cnewPassword" name="cnewpass" class="span4" value="<?php echo $cnewpass; ?>" required>
    </div>
  </div>
  
  <div class="form-actions">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="group" value="<?php echo $group; ?>">
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    <?php
    if ($_SESSION['user_group'] == 'jobseeker') {
      echo '<a href="dashboard_jobseeker.php" class="btn btn-inverse">Cancel</a>';
    } else {
      echo '<a href="dashboard_employer.php" class="btn btn-inverse">Cancel</a>';
    }
    ?>
  </div>
  
</form>

          </div>
          
        </div><!-- /.span4 -->

       
        <div class="span3">
        
        <?php
        if ($_SESSION['user_group'] == 'jobseeker') {
          usermenu_jobseeker('');
        } else {
          usermenu_employer('');
        }
        ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php } ?>