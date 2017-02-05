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

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
  $js_id = $db->sanitize($_GET['id']);
  
  // This function restricts the view of jobseeker profile to public
  // Only the owner, employers and admins are able to view this jobseeker profile
  if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
    if ($_SESSION['user_group'] == 'jobseeker') {
      if ($js_id !== _getUserID($_SESSION['user_login'], 'jobseeker')) { header('Location: index.php'); }
    }
  } else {
    header('Location: index.php');   
  }
  
  $query = $db->query("SELECT * FROM jobseeker WHERE id='$js_id'");
  $check_exist = $db->num($query);
  if ($check_exist == 0) {
    renderError();
  } else {
    $row = $db->fetch_array($query);
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
    $has_resume = $row['has_resume'];
    $resume_path = $row['resume_path'];
    $reg_date = $row['reg_date'];
    
    renderHTML($js_id, $name, $gender, $ic, $phone, $address, $postcode, $state, $email, $is_usm, $is_undergrad, $course, $has_resume, $resume_path, $reg_date);
  }
} else {
  renderError();
}

function renderHTML($js_id, $name, $gender, $ic, $phone, $address, $postcode, $state, $email, $is_usm, $is_undergrad, $course, $has_resume, $resume_path, $reg_date) {
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

    <div class="container" style="margin-top:50px">

      <div class="row">
      
      <div class="span2"></div>
      
        <div class="span8">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-user muted"></i> Jobseeker Profile (ID: <?php echo $js_id; ?>) <small class="pull-right">Registered on: <?php echo date('j F Y', $reg_date); ?></small></h4>
          <form class="form-horizontal profile-jobseeker" style="margin:20px">
          
          <legend>Personal Information</legend>
          
          <div class="control-group">
              <label class="control-label plabel">Full Name:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($name); ?></div>
                </div>
          </div>

          <div class="control-group">
              <label class="control-label plabel">Gender:</label>
                <div class="controls">
                <div class="pdata"><?php echo $gender; ?></div>
                </div>
          </div>


          <div class="control-group">
              <label class="control-label plabel">I/C Number:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($ic); ?></div>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label plabel">Phone Number:</label>
                <div class="controls">
                <div class="pdata"><?php echo $phone; ?></div>
                </div>
          </div>
         
          <div class="control-group">
              <label class="control-label plabel">Address:</label>
                <div class="controls">
                <div class="pdata"><?php echo address($address); ?></div>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label plabel">Postcode:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($postcode); ?></div>
                </div>
          </div>
              
           <div class="control-group">
              <label class="control-label plabel">State:</label>
                <div class="controls">
                <div class="pdata"><?php echo $state; ?></div>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label plabel">Email:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($email); ?></div>
                </div>
          </div> 
          
          <legend>Education Information</legend>              
               
           <div class="control-group">
              <label class="control-label plabel">USM Student:</label>
                <div class="controls">
                <div class="pdata">
                <?php
                if ($is_usm == '1') { echo 'Yes'; }
                else { echo 'No'; }
                ?>
                </div>
                </div>
          </div>
              
          <div class="control-group">
              <label class="control-label plabel">Education:</label>
                <div class="controls">
                <div class="pdata">
                <?php
                if ($is_undergrad == '1') { echo 'Undergraduate'; }
                else if ($is_undergrad == '0') { echo 'Postgraduate'; }
                else { echo 'N/A'; }
                ?>
                </div>
                </div>
          </div>
              
          <div class="control-group">
              <label class="control-label plabel">Course:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($course); ?></div>
                </div>
          </div>
          
          <legend>Resume/CV Availability</legend>
          
          <div class="control-group">
              <label class="control-label plabel">Filename:</label>
                <div class="controls">
                <?php
                if ($has_resume == '1') {
                  echo '<div class="pdata"><i class="icon-file"></i> '.filename_slug($name).'.pdf</div>';
                  echo '<div style="margin-top:10px"><a href="dl.php?uid='.$js_id.'" class="btn btn-success"><i class="icon-save"></i> Download Resume/CV File</a></div>';
                } else {
                  echo '<div class="pdata"><i class="icon-warning-sign hn-color-yellow"></i> No File Found!</div>';
                }
                ?>
                </div>
          </div>
                                                       
          </form>         
         
          </div><!-- /.box -->      
                 
          </div><!-- /.span8 -->
        
        <div class="span2"></div>
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php

}

function renderError() {

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

    <div class="container" style="margin-top:50px">

      <div class="row">
      
      <div class="span2"></div>
      
        <div class="span8">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-user muted"></i> Jobseeker Profile (ID: N/A)</h4>
          <form class="form-horizontal profile-jobseeker" style="margin:20px">
          
          <div class="alert alert-error">ERROR: No data found for this user ID!</div>
          
          <legend>Personal Information</legend>
          
          <div class="control-group">
              <label class="control-label plabel">Full Name:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>

          <div class="control-group">
              <label class="control-label plabel">Gender:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>


          <div class="control-group">
              <label class="control-label plabel">I/C Number:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label plabel">Phone Number:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
         
          <div class="control-group">
              <label class="control-label plabel">Address:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label plabel">Postcode:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
              
           <div class="control-group">
              <label class="control-label plabel">State:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label plabel">Email:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div> 
          
          <legend>Education Information</legend>              
               
           <div class="control-group">
              <label class="control-label plabel">USM Student:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
              
          <div class="control-group">
              <label class="control-label plabel">Education:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
              
          <div class="control-group">
              <label class="control-label plabel">Course:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
          
          <legend>Resume/CV Availability</legend>
          
          <div class="control-group">
              <label class="control-label plabel">Filename:</label>
                <div class="controls">
                <div class="pdata">N/A</div>
                </div>
          </div>
                                                       
          </form>         
         
          </div><!-- /.box -->      
                 
          </div><!-- /.span8 -->
        
        <div class="span2"></div>
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php } ?>