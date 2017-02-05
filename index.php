<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
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

function _userExist($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->num($query);
  if ($row > 0) { return true; }
  else { return false; }
  $initDB->close();
}

// check if user is logged in
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
  // check if username is exist in database
  if(_userExist($_SESSION['user_login'], $_SESSION['user_group'])) {
    // redirect user to the right page
    if ($_SESSION['user_group'] == 'jobseeker') { header('Location: dashboard_jobseeker.php'); }
    if ($_SESSION['user_group'] == 'employer') { header('Location: dashboard_employer.php'); }
    if ($_SESSION['user_group'] == 'admin') { header('Location: admin/dashboard.php'); }
  }
} else {
  // if user isn't logged in, check if user tick 'Remember Me' checkbox
  if(isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_COOKIE['group'])) {
    // check if username and password in cookies are match
    if(_checkDB($_COOKIE['username'], $_COOKIE['password'], $_COOKIE['group'])) {
      $_SESSION['user_login'] = $_COOKIE['username'];
      $_SESSION['user_group'] = $_COOKIE['group'];
      // cookies matched, redirect user to the right page
      if ($_COOKIE['group'] == 'jobseeker') { header('Location: dashboard_jobseeker.php'); }
      if ($_COOKIE['group'] == 'employer') { header('Location: dashboard_employer.php'); }
    }
  }         
}
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
    <?php html_body_header('Welcome'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span4">

<div class="box jobseeker-login">
<h4 class="box-title jobseeker-login-head"><i class="icon-signin semi-opacity"></i> Login as <u>Jobseeker</u></h4>
<div id="notifyError1" class="alert alert-error hide">--Error Response--</div>
<form id="jobSeekerLogin" class="form-horizontal form-login">
  <div class="control-group">
    <label class="control-label" for="inputUsername1">Username</label>
    <div class="controls">
      <input type="text" id="inputUsername1" name="inputUsername1" placeholder="Enter username" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword1">Password</label>
    <div class="controls">
      <input type="password" id="inputPassword1" name="inputPassword1" placeholder="Enter password" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" id="checkRememberMe1" name="checkRememberMe1" value="1"> Stay logged in
      </label>
      <input type="hidden" id="antispam1" name="antispam1" value="">
      <a href="#" id="btnLogin1" class="btn btn-primary"><i class="icon-signin"></i> Login</a>
      <a href="register.php" class="btn btn-inverse"><i class="icon-edit"></i> Register</a>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <a href="forgot_password.php?user=jobseeker" class="btn-link">Forgot password? Click here!</a>
    </div>
  </div>
</form>
</div><!-- /.box -->

<div class="alert">
<u>Current Statistic</u>:
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query = $db->query("SELECT id FROM jobseeker");
$total_js = $db->num($query);
if ($total_js > 1) { echo ' <strong>'.$total_js.' jobseekers</strong> '; }
else { echo ' <strong>'.$total_js.' jobseeker</strong> '; }
$db->close();
?>
registered.
</div>

<div id="jobList">
<i class="icon-bullhorn"></i> Posted Job Vacancies / Internships
<div id="jvList" class="well well-small">
<ul id="jobList">
          
<?php

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_jv = $db->query("SELECT * FROM job_vacancy ORDER BY posted_date");
$total_jv = $db->num($query_jv);
if ($total_jv == 0) { echo 'No any posted job vacancy yet.'; }
while ($row = $db->fetch_assoc($query_jv)) {
  $jv_id = $row['id'];
  $title = $row['title'];
  $location = $row['location'];
  $special = $row['special'];
  $brief = $row['brief'];
  $details = $row['details'];
  $requirement = $row['requirement'];
  $permalink = $row['permalink'];
  $posted_date = $row['posted_date'];
  $is_open = $row['is_open'];
  $company_id = $row['company_id'];
  
  $query_e2 = $db->query("SELECT company_name,is_sponsor,sponsor_type FROM employer WHERE id='$company_id'");
  $rowe2 = $db->fetch_array($query_e2);
  $comp_name2 = $rowe2['company_name'];
  $is_sponsor2 = $rowe2['is_sponsor'];
  $sponsor_type2 = $rowe2['sponsor_type'];
  
  if ($is_open == '1') {
  
  echo '<li id="jv-'.$jv_id.'">';
  //echo date('d/m/Y', $posted_date);
  echo '<a href="jv.php?id='.$jv_id.'" class="pop-tag" title="Specialization" data-content="'.normalize($special).'">'.normalize($title).'</a>';
  echo '</li>';
  
  }
  
}
            
?>            
            
          </ul>
          </div>
</div>
          
        </div><!-- /.span4 -->
        
        <div class="span4">
        
<div class="box employer-login">
<h4 class="box-title employer-login-head"><i class="icon-signin semi-opacity"></i> Login as <u>Employer</u></h4>
<div id="notifyError2" class="alert alert-error hide">--Error Response--</div>
<form id="employerLogin" class="form-horizontal form-login">
  <div class="control-group">
    <label class="control-label" for="inputUsername2">Login ID</label>
    <div class="controls">
      <input type="text" id="inputUsername2" name="inputUsername2" placeholder="Enter username" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword2">Password</label>
    <div class="controls">
      <input type="password" id="inputPassword2" name="inputPassword2" placeholder="Enter password" class="input-block-level" value="">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" id="checkRememberMe2" name="checkRememberMe2" value="1"> Stay logged in
      </label>
      <input type="hidden" id="antispam2" name="antispam2" value="">
      <a href="#" id="btnLogin2" class="btn btn-primary"><i class="icon-signin"></i> Login</a> <a href="request_loginid.php" class="btn btn-warning">Request Login ID</a>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <a href="forgot_password.php?user=employer" class="btn-link">Forgot password? Click here!</a>
    </div>
  </div>
</form>
</div><!-- /.box -->

<div class="alert">
<u>Current Statistic</u>:
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query = $db->query("SELECT id FROM employer");
$total_e = $db->num($query);
if ($total_e > 1) { echo ' <strong>'.$total_e.' companies</strong> '; }
else { echo ' <strong>'.$total_e.' company</strong> '; }
$db->close();
?>
participated,
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query = $db->query("SELECT id FROM job_vacancy");
$total_e = $db->num($query);
if ($total_e > 1) { echo ' <strong>'.$total_e.' job vacancies/ internships</strong> '; }
else { echo ' <strong>'.$total_e.' job vacancy</strong> '; }
$db->close();
?>
posted.
</div>

<i class="icon-user-md"></i> List of Available Companies
<div id="compList" class="well well-small">
<ol id="companyList">
          
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_e = $db->query("SELECT * FROM employer ORDER BY is_sponsor DESC, company_name ASC");
$total_e = $db->num($query_e);
if ($total_e == 0) { echo 'No data.'; }
while ($rowe = $db->fetch_assoc($query_e)) {
  $comp_id = $rowe['id'];
  $comp_name = $rowe['company_name'];
  $comp_tag = $rowe['company_tag'];
  $is_sponsor = $rowe['is_sponsor'];
  $sponsor_type = $rowe['sponsor_type'];
  $is_avail = $rowe['is_available'];
  
  //if ($is_avail == '1') {
  
  echo '<li id="comp-'.$comp_id.'">';
  echo '<a href="employer.php?id='.$comp_id.'" class="pop-tag" data-title="Related tags" data-content="'.$comp_tag.'">'.normalize($comp_name).'</a> ';
  if ($is_sponsor == '1') {
    echo '<i class="icon-certificate hn-color-orange tip-top" title="'.normalize($sponsor_type).'"></i>';
  }
  echo '</li>';
  
  //}
  
}
$db->close();
            
?>
            
          </ol>
</div>
          
       </div><!-- /.span4 -->
       
        <div class="span4">
        
<?php include 'sponsors.php'; ?>
         
        </div><!-- /.span4 -->
        
      </div><!-- /.row -->

    <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    $('#btnLogin1').click(function(e){
      e.preventDefault();
      var username = $('#inputUsername1').val();
      var password = $('#inputPassword1').val();
      var antispam = $('#antispam1').val();
      if ($('#checkRememberMe1').is(':checked')){ var remember = 1; }
      else { var remember = 0; }
      var postData = 'usergroup=jobseeker&username='+urlencode(username)+'&password='+urlencode(password)+'&antispam='+urlencode(antispam)+'&remember='+urlencode(remember);
      $.ajax({
        type: 'POST',
        url: 'login.php',
        data: postData,
        success: function(responseData){
          if (responseData !== 'OK') {
            $('#notifyError1').html(responseData).fadeIn().delay(3000).fadeOut();
          } else {
            window.location.replace('dashboard_jobseeker.php');
          }
        }
      });
    });
    
    $('#btnLogin2').click(function(e){
      e.preventDefault();
      var username = $('#inputUsername2').val();
      var password = $('#inputPassword2').val();
      var antispam = $('#antispam2').val();
      if ($('#checkRememberMe2').is(':checked')){ var remember = 1; }
      else { var remember = 0; }
      var postData = 'usergroup=employer&username='+urlencode(username)+'&password='+urlencode(password)+'&antispam='+urlencode(antispam)+'&remember='+urlencode(remember);
      $.ajax({
        type: 'POST',
        url: 'login.php',
        data: postData,
        success: function(responseData){
          if (responseData !== 'OK') {
            $('#notifyError2').html(responseData).fadeIn().delay(3000).fadeOut();
          } else {
            window.location.replace('dashboard_employer.php');
          }
        }
      });
    });
    </script>

  </body>
</html>
