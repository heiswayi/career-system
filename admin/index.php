<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

function _checkDB($username, $password) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM admin WHERE username='$username'");
  $row = $initDB->fetch_array($query);
  if ($row && $row['password'] == $password) { return true; }
  else { return false; }
  $initDB->close();
}

function _userExist($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->num($query);
  if ($row > 0) { return true; }
  else { return false; }
  $initDB->close();
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['btnLogin']) && !empty($_POST)) {
  $username = $db->sanitize($_POST['username']);
  $password = md5(PW_SALT . $db->sanitize($_POST['password']));
  
  if ($username == '' || $password == '') {
    renderForm('<div id="notifyError" class="alert alert-error">ERROR: All fields are required!</div>');
  } else if (!_checkDB($username, $password)) {
    renderForm('<div id="notifyError" class="alert alert-error">ERROR: Username and password do not match!</div>');
  } else {
    $_SESSION['user_login'] = $username;
    $_SESSION['user_group'] = 'admin';
    header('Location: dashboard.php');
  }
} else {
  if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
    if(_userExist($_SESSION['user_login'], 'admin')) {
      header('Location: dashboard.php');
      die();
    }
  } else {
    renderForm('');
  }
}
  
function renderForm($error) {
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <?php html_head_js(); ?>
  </head>

  <body>

    <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">

          <a class="brand" href="index.php" style="color:#ff6600"><i class="icon-sign-blank" style="color:#aa00cc"></i> Career Portal</a>
            <ul class="nav">
              <li class="active"><a href="index.php"><i class="icon-home"></i> Home</a></li>
            </ul>
        </div>
      </div>
    </div>
    
    <?php html_body_header('Admin Center'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span4">
        
<div class="box jobseeker-login">
<h4 class="box-title jobseeker-login-head"><i class="icon-signin semi-opacity"></i> Authorized Personnel Only</h4>

<?php echo $error; ?>

<form id="adminLogin" class="form-horizontal form-login" method="post" action="">
  <div class="control-group">
    <label class="control-label" for="inputUsername1">Login ID</label>
    <div class="controls">
      <input type="text" id="inputUsername1" name="username" placeholder="Enter username" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword1">Password</label>
    <div class="controls">
      <input type="password" id="inputPassword1" name="password" placeholder="Enter password" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" name="btnLogin" class="btn btn-primary"><i class="icon-signin"></i> Login</button>
    </div>
  </div>
</form>
</div><!-- /.box -->
          
        </div><!-- /.span4 -->
        
        <div class="span4">
          
       </div><!-- /.span4 -->
       
        <div class="span4">
         
        </div><!-- /.span4 -->
        
      </div><!-- /.row -->

    <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php } ?>