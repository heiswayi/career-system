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
  if(!_userExist($_SESSION['user_login'], 'jobseeker')) {
    header('Location: index.php');
  }
} else {
  header('Location: index.php');   
}

$js_username = $_SESSION['user_login'];
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_jobseeker = $db->query("SELECT id FROM jobseeker WHERE username='$js_username'");
$get_param = $db->fetch_array($query_jobseeker);
$jobseeker_id = $get_param['id'];
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
    <?php html_body_header('Dashboard'); ?>

    <div class="container">
   

      <div class="row">
      
        <div class="span4">
        
  <div class="alert alert-info">
  <span class="label label-info">Notice !</span> Even if your application has been approved, please wait for the company representative to contact and arrange you for your interview.
  </div>
          
          <div class="box">
          <h4 class="box-title"><i class="icon-reorder muted"></i> List of Companies</h4>
          <ol id="companyList">
          
<?php
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
  
  //$query_ai = $db->query("SELECT accept_status FROM apply_interview WHERE employer_id='$comp_id' AND jobseeker_id='$jobseeker_id'");
  //$rowai = $db->fetch_array($query_ai);
  //$check_apply = $db->num($query_ai);
  //$accept_status = $rowai['accept_status'];
  
  //if ($is_avail == '1') {
  
  echo '<li id="comp-'.$comp_id.'">';
  //if ($check_apply == 1) {
  //  echo '<i class="icon-ok hn-color-green tip-top" title="Applied"></i>';
  //}
  echo '<a href="employer.php?id='.$comp_id.'" class="pop-tag" data-title="Related tags" data-content="'.$comp_tag.'">'.normalize($comp_name).'</a> ';
  if ($is_sponsor == '1') {
    echo '<i class="icon-certificate hn-color-orange tip-top" title="'.normalize($sponsor_type).'"></i> ';
  }
  /*
  if ($check_apply == 1) {
    if ($accept_status == '1') {
      echo '<i class="icon-ok-sign hn-color-green tip-top" title="Application status: Accepted"></i>';
    } else if ($accept_status == '0') {
      echo '<i class="icon-remove-sign hn-color-red tip-top" title="Application status: Rejected"></i>';
    } else {
      echo '<i class="icon-circle-blank muted tip-top" title="Application status: No Response"></i>';
    }
  }
  */
  echo '</li>';
  
  //}
  
}
            
?>
            
          </ol>
          </div>
          
        </div><!-- /.span4 -->
        
        <div class="span5">
        
        <div class="alert">
          Any available job vacancy or internship posted by company will be displayed here. To apply, click on the job vacancy title and you will be redirected to particular job vacancy details page. If the <u>application status</u> displays <strong style="color:#468847">ACCEPT</strong>, <strong>that means your application has been accepted</strong>, otherwise it will display <strong style="color:#b94a48">REJECT</strong> or <strong>NO RESPONSE</strong>. 
          </div>
        
          <div class="box">
          <h4 class="box-title"><i class="icon-bullhorn muted"></i> Posted Applications (Job Vacancies/ Internships)</h4>
  
          <ul id="jobList" class="unstyled jobs-vacancy">
          
<?php

$query_jv = $db->query("SELECT * FROM job_vacancy ORDER BY posted_date DESC");
$total_jv = $db->num($query_jv);
if ($total_jv == 0) { echo 'No job vacancies posted yet..'; }
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
  
  $query_applyjob = $db->query("SELECT * FROM apply_job WHERE job_id='$jv_id' AND jobseeker_id='$jobseeker_id'");
  $check_applyjob = $db->num($query_applyjob);
  $getrow = $db->fetch_array($query_applyjob);
  $apply_status = $getrow['accept_status'];
  
  $query_e2 = $db->query("SELECT company_name,is_sponsor,sponsor_type FROM employer WHERE id='$company_id'");
  $rowe2 = $db->fetch_array($query_e2);
  $comp_name2 = $rowe2['company_name'];
  $is_sponsor2 = $rowe2['is_sponsor'];
  $sponsor_type2 = $rowe2['sponsor_type'];
  
  echo '<li id="jv-'.$jv_id.'"><div class="job-title"><span class="date pull-right"><small><strong>Posted on:</strong> ';
  echo date('j F Y', $posted_date);
  echo '</small></span><a href="jv.php?id='.$jv_id.'" class="title label label-inverse pop-tag" title="Specialization" data-content="'.normalize($special).'">'.normalize($title).'</a> ';
  if ($check_applyjob == 1) {
    echo '<i class="icon-ok hn-color-green tip-top" title="You already applied for this job"></i>';
  }
  echo '<br/><span class="company"><strong>Employer:</strong> '.normalize($comp_name2).' ';
  if ($is_sponsor2 == '1') {
    echo '<i class="icon-certificate hn-color-orange tip-top" title="'.normalize($sponsor_type2).'"></i>';
  }
  echo '</span><br/><span class="location"><strong>Location:</strong> '.normalize($location).'</span>';
  if ($check_applyjob == 1) {
    if ($apply_status == '1') {
      echo '<div class="astatus" style="border-top:1px dashed #ccc"><strong><u>Application Status</u>:</strong> <span style="color:#468847">ACCEPTED</span></div>';
    } else if ($apply_status == '0') {
      echo '<div class="astatus" style="border-top:1px dashed #ccc"><strong><u>Application Status</u>:</strong> <span style="color:#b94a48">REJECTED</span></div>';
    } else {
      echo '<div class="astatus" style="border-top:1px dashed #ccc"><strong><u>Application Status</u>:</strong> NO RESPONSE</span>';
    }
  }
  if ($is_open == '0') {
    echo '<br/><span style="color:#e00">*** This job vacancy has been closed by the respective employer.</div>';
  }
  echo '</li>';
  
}
            
?>            
            
          </ul>
          </div>
          
       </div><!-- /.span5 -->
       
        <div class="span3">
        
        <?php usermenu_jobseeker('Dashboard'); ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>
