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
  $e_id = $db->sanitize($_GET['id']);  
  $query = $db->query("SELECT * FROM employer WHERE id='$e_id'");
  $check_exist = $db->num($query);
  if ($check_exist == 0) {
    header('Location: 404.php');
  } else {
    $row = $db->fetch_array($query);
    $cname = $row['company_name'];
    $cinfo = $row['company_info'];
    $cweb = $row['company_website'];
    $clogo = $row['company_logo_url'];
    $ctag = $row['company_tag'];
    $pprefix = $row['person_prefix'];
    $pname = $row['person_name'];
    $department = $row['department'];
    $phone = $row['phone'];
    $mobile = $row['mobile'];
    $email = $row['email'];
    $is_sponsor = $row['is_sponsor'];
    $sponsor_type = $row['sponsor_type'];
    $is_avail = $row['is_available'];
    
    renderHTML($e_id, $clogo, $cname, $cinfo, $cweb, $ctag, $pprefix, $pname, $department, $phone, $mobile, $email, $is_sponsor, $sponsor_type, $is_avail);
  }
} else {
  header('Location: 404.php');
}

function renderHTML($e_id, $clogo, $cname, $cinfo, $cweb, $ctag, $pprefix, $pname, $department, $phone, $mobile, $email, $is_sponsor, $sponsor_type, $is_avail) {
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
      
        <div class="span7">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-user-md muted"></i> Employer Profile (ID: <?php echo $e_id; ?>)</h4>
          <form class="form-horizontal profile-employer" style="margin:20px">
          
          <?php
          if ($is_sponsor == '1') {
            echo '<div style="background:#222;color:#eee;padding:5px;border:1px solid #000;"> <i class="icon-star hn-color-yellow"></i> This company has sponsored our Career Fair 2014 <span class="hn-color-yellow">('.normalize($sponsor_type).')</span></div>';
          }
          ?>
          
          <legend>Company Information</legend>
          
          <?php if ($clogo !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Company Logo:</label>
                <div class="controls">
                  <div class="pdata"><img style="max-width:300px;max-height:200px;" src="<?php echo normalize($clogo); ?>"></div>
                </div>
          </div>
          <?php } ?>
          
          <div class="control-group">
              <label class="control-label plabel">Company Name:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($cname); ?></div>
                </div>
          </div>
          
          <?php if ($cinfo !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Company Info:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($cinfo); ?></div>
                </div>
          </div>
          <?php } ?>

          <?php if ($cweb !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Website:</label>
                <div class="controls">
                <div class="pdata"><?php echo clickable_url($cweb); ?></div>
                </div>
          </div>
          <?php } ?>
          
          <div class="control-group">
              <label class="control-label plabel">Tags:</label>
                <div class="controls">
                <div class="pdata"><?php if ($ctag !== '') { echo normalize($ctag); } else { echo '-'; } ?></div>
                </div>
          </div>
          
          <legend>Contact Information</legend>
          
          <?php if ($pname !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Person Name:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($pprefix).' '.normalize($pname); ?></div>
                </div>
          </div>
          <?php } ?>
         
          <?php if ($department !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Position/Department:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($department); ?></div>
                </div>
          </div>
          <?php } ?>
          
          <?php if ($phone !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Phone Number:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($phone); ?></div>
                </div>
          </div>
          <?php } ?>
           
           <?php if ($mobile !== '') { ?>
           <div class="control-group">
              <label class="control-label plabel">Mobile Number:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($mobile); ?></div>
                </div>
          </div>
          <?php } ?>
          
          <div class="control-group">
              <label class="control-label plabel">Email:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($email); ?></div>
                </div>
          </div>
                                                       
          </form>         
         
          </div><!-- /.box -->      
                 
          </div>
        
        <div class="span5">
        
        <div class="box">
          <h4 class="box-title"><i class="icon-bullhorn muted"></i> Posted Applications (Job Vacancy / Internship)</h4>
  
          <ul id="jobList" class="unstyled jobs-vacancy">
          
          
          
<?php

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_jv = $db->query("SELECT * FROM job_vacancy WHERE company_id='$e_id'");
$total_jv = $db->num($query_jv);
if ($total_jv == 0) { echo 'This company has not posted any job vacancy yet.'; }
else {
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
  
  if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
    if ($_SESSION['user_group'] == 'jobseeker') {
      $jobseeker_id = _getUserID($_SESSION['user_login'], 'jobseeker');
      $query_applyjob = $db->query("SELECT id FROM apply_job WHERE job_id='$jv_id' AND jobseeker_id='$jobseeker_id'");
      $check_applyjob = $db->num($query_applyjob);
    } else { $check_applyjob = 0; }
  } else { $check_applyjob = 0; }
    
  if ($is_open == '1') {
  
  echo '<li id="jv-'.$jv_id.'"><div class="job-title"><span class="date pull-right"><small><strong>Posted on:</strong> ';
  echo date('j F Y', $posted_date);
  echo '</small></span><a href="jv.php?id='.$jv_id.'" class="title label label-inverse pop-tag" title="Brief Info" data-content="'.normalize($brief).'">'.normalize($title).'</a> ';
  if ($check_applyjob == 1) {
    echo '<i class="icon-ok hn-color-green tip-top" title="Applied"></i>';
  }
  echo '<br/><span class="location"><strong>Location:</strong> '.normalize($location).'</span>';
  echo '<br/><span class="specialization"><strong>Specialization:</strong> '.normalize($special).'</span></li>';
  
  }
  
}
}

$db->close();
            
?>            
            
          </ul>
          </div>
        
        </div>
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    function apply_interview(eid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'apply_interview.php',
      data: 'action=apply&employer_id='+eid+'&jobseeker_id='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#btnApplySection").html('<a href="#" id="btnApply" class="btn btn-success disabled"><i class="icon-ok"></i> Already applied</a> <a href="#" onClick="cancel_apply('+eid+','+jsid+')" id="btnCancelApply" class="btn btn-danger">Cancel apply</a>').fadeIn();
        } else {
          $("#btnApplySection").html('ERROR: Something went wrong, please refresh the browser.');
        }
      }
    });
    }
    function cancel_apply(eid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'apply_interview.php',
      data: 'action=cancel&employer_id='+eid+'&jobseeker_id='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#btnApplySection").html('<a href="#" onClick="apply_interview('+eid+','+jsid+')" id="btnApply" class="btn btn-success">Apply now</a>').fadeIn();
        } else {
          $("#btnApplySection").html('ERROR: Something went wrong, please refresh the browser.');
        }
      }
    });
    }
    </script>

  </body>
</html>

<?php } ?>