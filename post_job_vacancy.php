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

// check if user is logged in
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
  // check if username is exist in database
  if(!_userExist($_SESSION['user_login'], 'employer')) {
    header('Location: index.php');
  }
} else {
  header('Location: index.php');   
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['submit']) && !empty($_POST)) {
  $title = $db->sanitize($_POST['title']);
  $location = $db->sanitize($_POST['location']);
  $special = $db->sanitize($_POST['special']);
  $brief = $db->sanitize($_POST['brief']);
  $details = $db->sanitize($_POST['details']);
  $requirement = $db->sanitize($_POST['requirement']);
  $link = $db->sanitize($_POST['link']);
  $companyID = $_POST['comp_id'];
  
  if ($title == '' || $location == '' || $special == '' || $brief == '') {
    $res = '<div id="notifyError" class="alert alert-error">ERROR: All fields in Required Information are required.</div>';
    renderForm($title, $location, $special, $brief, $details, $requirement, $link, $companyID, $res, 'no');
  } else {
    $posted_date = time();
    $db->query("INSERT INTO job_vacancy (title, location, special, brief, details, requirement, permalink, posted_date, company_id) VALUES ('$title', '$location', '$special', '$brief', '$details', '$requirement', '$link', '$posted_date', '$companyID')");
    $res = '<div id="notifySuccess" class="alert alert-success">Your new job vacancy has been posted and saved in our database successfully.</div>';
    renderForm($title, $location, $special, $brief, $details, $requirement, $link, $companyID, $res, 'yes');
  }
} else {
  $username = $_SESSION['user_login'];
  $query = $db->query("SELECT * FROM employer WHERE username='$username'");
  $row = $db->fetch_array($query);
  if ($row) { $get_ID = $row['id']; }
  renderForm('', '', '', '', '', '', '', $get_ID, '', 'no');
}

function renderForm($title, $location, $special, $brief, $details, $requirement, $link, $companyID, $res, $success) {

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
    <?php html_body_header('Post Job Vacancy'); ?>

    <div class="container">

      <div class="row">
      
      <div class="span9">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Post a New Application (Job Vacancy/ Internship)</h4>
          
<form class="form-horizontal post-job-form" style="margin:20px" method="post" action="">

<?php if ($success == 'no') { ?>

<div class="alert alert-info">
  <span class="label label-info">Note!</span> <strong>Company and contact informations</strong> will be automatically displayed on each of your posted applications based on the information you provided in the profile details. Go to <em>Edit Profile</em> if you want to modify them.
  </div>
  
<?php echo $res; ?>
  
<legend>Required information</legend>
  <div class="control-group">
    <label class="control-label" for="inputJobTitle">Job title/ Application name:</label>
    <div class="controls">
      <input type="text" id="inputJobTitle" name="title" placeholder="e.g. Software Engineer, Internship" class="span4" value="<?php echo $title; ?>" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputJobLocation">Location:</label>
    <div class="controls">
      <input type="text" id="inputJobLocation" name="location" placeholder="e.g. Kuala Lumpur, Selangor" class="span4" value="<?php echo $location; ?>" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputJobSpec">Specialization:</label>
    <div class="controls">
      <input type="text" id="inputJobSpec" name="special" placeholder="e.g. C/C++, C#, Java, AJAX, JSP, HTML/CSS, SQL" class="span6" value="<?php echo $special; ?>" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputJobDesc">Brief description:</label>
    <div class="controls">
      <textarea id="inputJobDesc" name="brief" placeholder="Tell what the job is about as short as possible..." rows="4" style="resize:none" class="span4" required><?php echo $brief; ?></textarea>
    </div>
  </div>
  <legend>Optional information</legend>
  <div class="control-group">
    <label class="control-label" for="inputJobDetails">Details information:</label>
    <div class="controls">
      <textarea id="inputJobDetails" name="details" placeholder="Usually this space is used to tell them their responsibilities..." rows="6" style="resize:none" class="span4"><?php echo $details; ?></textarea>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputJobReq">Requirement(s):</label>
    <div class="controls">
      <textarea id="inputJobReq" name="requirement" rows="4" style="resize:none" class="span4"><?php echo $requirement; ?></textarea>
    </div>
  </div>
  
  <legend>External Page Referral</legend>
  <div class="control-group">
    <label class="control-label" for="inputLink">External Link:</label>
    <div class="controls">
      <input type="text" id="inputLink" name="link" placeholder="e.g. http://www.jobstreet.com.my/permalink/to/your/job_vacancy/page" class="span6" value="<?php echo $link; ?>">
      <span class="help-block">If you have any posted job vacancies or internships page on other great job sites, just paste the permalink here. So, you don't need to fill in the optional information anymore.</span>
    </div>
  </div>
  
  <div class="form-actions">
    <input type="hidden" name="comp_id" value="<?php echo $companyID; ?>">
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    <a href="dashboard_employer.php" class="btn btn-inverse">Cancel</a>
  </div>
  
<?php } else { ?>

<?php echo $res; ?>

<a href="post_job_vacancy.php" class="btn btn-success"><i class="icon-pencil"></i> Post another new job vacancy</a> <a href="dashboard_employer.php" class="btn btn-inverse">Cancel</a>

<?php } ?>

  
</form>

          </div>
          
        </div><!-- /.span4 -->

       
        <div class="span3">
        
          <?php usermenu_employer('Post Job'); ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php } ?>