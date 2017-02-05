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
  $jv_id = $db->sanitize($_GET['id']);
  $query = $db->query("SELECT * FROM job_vacancy WHERE id='$jv_id'");
  $check_exist = $db->num($query);
  if ($check_exist == 0) {
    header('Location: 404.php');
  } else {
    $row = $db->fetch_array($query);
    $title = $row['title'];
    $location = $row['location'];
    $special = $row['special'];
    $brief = $row['brief'];
    $details = $row['details'];
    $requirement = $row['requirement'];
    $permalink = $row['permalink'];
    $posted_date = $row['posted_date'];
    $comp_id = $row['company_id'];
    $is_open = $row['is_open'];
    
    $query_e = $db->query("SELECT * FROM employer WHERE id='$comp_id'");
    $rowe = $db->fetch_array($query_e);
    $comp_name = $rowe['company_name'];
    $is_sponsor = $rowe['is_sponsor'];
    $sponsor_type = $rowe['sponsor_type'];
    $pprefix = $rowe['person_prefix'];
    $pname = $rowe['person_name'];
    $department = $rowe['department'];
    $phone = $rowe['phone'];
    $mobile = $rowe['mobile'];
    $email = $rowe['email'];
    
    renderHTML($jv_id, $title, $location, $special, $brief, $details, $requirement, $permalink, $posted_date, $is_open, $comp_id, $comp_name, $is_sponsor, $sponsor_type, $pprefix, $pname, $department, $phone, $mobile, $email);
  }
} else {
  header('Location: 404.php');
}

function renderHTML($jv_id, $title, $location, $special, $brief, $details, $requirement, $permalink, $posted_date, $is_open, $comp_id, $comp_name, $is_sponsor, $sponsor_type, $pprefix, $pname, $department, $phone, $mobile, $email) {
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
          <h4 class="box-title" style="background:#eee"><i class="icon-bullhorn muted"></i> Job Vacancy (ID: <?php echo $jv_id; ?>) <small class="pull-right">Posted on: <?php echo date('j F Y', $posted_date); ?></small></h4>
          <form class="form-horizontal job-vacancy" style="margin:20px">
          
          <?php
          if ($is_sponsor == '1') {
            echo '<div style="background:#222;color:#eee;padding:5px;border:1px solid #000;"> <i class="icon-star hn-color-yellow"></i> The company who posted this job vacancy has sponsored our Career Fair 2014 <span class="hn-color-yellow">('.normalize($sponsor_type).')</span></div>';
          }
          ?>
          
          <legend>Job Information</legend>
          
          <div class="control-group">
              <label class="control-label plabel">Employer:</label>
                <div class="controls">
                <div class="pdata"><a href="employer.php?id=<?php echo $comp_id; ?>" class="tip-top" title="Visit company profile page for details"><?php echo normalize($comp_name); ?></a></div>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label plabel">Job title:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($title); ?></div>
                </div>
          </div>

          <div class="control-group">
              <label class="control-label plabel">Location:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($location); ?></div>
                </div>
          </div>


          <div class="control-group">
              <label class="control-label plabel">Specialization:</label>
                <div class="controls">
                <div class="pdata"><?php echo normalize($special); ?></div>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label plabel">Brief description:</label>
                <div class="controls">
                <div class="pdata"><?php echo address($brief); ?></div>
                </div>
          </div>
         
          <?php if ($details !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Details information:</label>
                <div class="controls">
                <div class="pdata"><?php echo address($details); ?></div>
                </div>
          </div>
          <?php } ?>
          
          <?php if ($requirement !== '') { ?>
          <div class="control-group">
              <label class="control-label plabel">Requirement(s):</label>
                <div class="controls">
                <div class="pdata"><?php echo address($requirement); ?></div>
                </div>
          </div>
          <?php } ?>
           
           <?php if ($permalink !== '') { ?>
           <div class="control-group">
              <label class="control-label plabel">External page:</label>
                <div class="controls">
                <div class="pdata"><i class="icon-external-link"></i> <?php echo clickable_url($permalink); ?></div>
                </div>
          </div>
          <?php } ?>
          
          <div class="control-group">
              <label class="control-label plabel">Contact information:</label>
                <div class="controls">
                <div class="pdata">
                <?php
                echo normalize($pprefix).' '.normalize($pname).' <br />';
                if ($department !== '') { echo '<em>'.normalize($department).'</em> <br />'; }
                if ($phone !== '') { echo '<span style="margin-right:20px"><i class="icon-phone"></i> : '.normalize($phone).'</span>'; }
                if ($mobile !== '') { echo '<span style="margin-right:20px"><i class="icon-mobile-phone"></i> : '.normalize($mobile).'</span>'; }
                echo '<br />';
                echo '<i class="icon-envelope"></i> : <a href="mailto:'.normalize($email).'">'.normalize($email).'</a>';
                ?>
                </div>
                </div>
          </div> 
          
          <legend>Apply for this Job</legend>
          
          <div class="control-group">
                <div class="controls">
                <div id="btnApplySection">
                <?php
                if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
                  if ($_SESSION['user_group'] == 'jobseeker') {
                    $db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
                    $js_id = _getUserID($_SESSION['user_login'], 'jobseeker');
                    $query_aj = $db->query("SELECT * FROM apply_job WHERE job_id='$jv_id' AND jobseeker_id='$js_id'");
                    $check_apply = $db->num($query_aj);
                    if ($is_open == '1') {
                    if ($check_apply > 0) {
                      echo '<a href="#" id="btnApply" class="btn btn-success disabled"><i class="icon-ok"></i> Already applied</a> <a href="#" onClick="cancel_apply('.$jv_id.','.$js_id.')" id="btnCancelApply" class="btn btn-danger">Cancel apply</a>';
                    } else {
                      echo '<a href="#" onClick="apply_job('.$jv_id.','.$js_id.')" id="btnApply" class="btn btn-success">Apply now</a>';
                    }
                    } else {
                      echo 'This job vacancy application has been closed.';
                    }
                    $db->close();
                  } else {
                    echo 'This section is only for jobseekers.';
                  }
                } else {
                  echo '<a href="index.php">Please login to apply.</a>';
                }
                ?>
                </div>
                </div>
          </div>
                                                       
          </form>         
         
          </div><!-- /.box -->
          
<?php

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
if ($_SESSION['user_group'] == 'employer') {
$current_eid = _getUserID($_SESSION['user_login'], 'employer');
if ($current_eid == $comp_id) {

$query_a = $db->query("SELECT * FROM apply_job WHERE job_id='$jv_id'");
$total_a = $db->num($query_a);
if ($total_a > 1) { $show_total = $total_a.' persons'; }
else { $show_total = $total_a.' person'; }

echo '<div class="box">';
echo '<h4 class="box-title" style="background:#eee"><i class="icon-group muted"></i> List of Applicants <small><span class="hn-color-blue tip-top" title="This section is only available to you, other people cannot see this section."><i class="icon-info-sign"></i> Info</span> / <a href="printable/jval.php?id='.$jv_id.'" target="_blank"><i class="icon-print"></i> Printable Version</a></small><small class="pull-right">Total: '.$show_total.'</small></h4>';

echo '<div style="padding:20px">';
if ($total_a == 0) { echo 'No applicant yet.'; }
else {
echo '<table class="table table-condensed table-striped">';
echo '<thead><tr>';
echo '<th>Fullname</th>';
echo '<th>Phone Number</th>';
echo '<th>Email Address</th>';
echo '</tr></thead>';
echo '<tbody>';
while ($rowa = $db->fetch_assoc($query_a)) {
$get_js_id = $rowa['jobseeker_id'];
$query_aa = $db->query("SELECT * FROM jobseeker WHERE id='$get_js_id'");
$param = $db->fetch_array($query_aa);
$js_fullname = $param['fullname'];
$js_email = $param['email'];
$js_phone = $param['phone'];
$has_resume = $param['has_resume'];
$resume_path = $param['resume_path'];
echo '<tr>';
echo '<td><a href="jobseeker.php?id='.$get_js_id.'" class="tip-top" title="Visit user profile page for details">'.normalize($js_fullname).'</a> ';
if ($has_resume == '1') {
  echo '<a href="dl.php?uid='.$get_js_id.'"><i class="icon-download-alt hn-color-purple tip-top" title="Download resume/CV"></i></a> ';
}
echo '</td>';
echo '<td><i class="icon-phone-sign"></i> '.normalize($js_phone).'</td>';
echo '<td>'.normalize($js_email).'</td>';
echo '</tr>';
}
echo '</tbody>';
echo '</table>';
}
echo '</div>';
      
echo '</div>';

}
}
}

?>
                 
          </div><!-- /.span8 -->
        
        <div class="span2"></div>
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    function apply_job(jvid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'apply_job.php',
      data: 'action=apply&job_id='+jvid+'&jobseeker_id='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#btnApplySection").html('<a href="#" id="btnApply" class="btn btn-success disabled"><i class="icon-ok"></i> Already applied</a> <a href="#" onClick="cancel_apply('+jvid+','+jsid+')" id="btnCancelApply" class="btn btn-danger">Cancel apply</a>').fadeIn();
        } else {
          $("#btnApplySection").html('ERROR: Something went wrong, please refresh the browser.');
        }
      }
    });
    }
    function cancel_apply(jvid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'apply_job.php',
      data: 'action=cancel&job_id='+jvid+'&jobseeker_id='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#btnApplySection").html('<a href="#" onClick="apply_job('+jvid+','+jsid+')" id="btnApply" class="btn btn-success">Apply now</a>').fadeIn();
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