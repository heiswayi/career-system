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

$comp_username = $_SESSION['user_login'];
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_employer = $db->query("SELECT * FROM employer WHERE username='$comp_username'");
$get_param = $db->fetch_array($query_employer);
$comp_id = $get_param['id'];
$is_avail = $get_param['is_available'];

$pname = $get_param['person_name'];
$cweb = $get_param['company_website'];
$cinfo = $get_param['company_info'];
$ctag = $get_param['company_tag'];
$phone = $get_param['phone'];
if ($pname == '' && $cweb == '' && $cinfo == '' && $ctag == '' && $phone == '') { header('Location: welcome.php'); }

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
    
    <div class="row"><div class="span12"><div class="alert alert-info">
  <span class="label label-info">Notice!</span> After you have approved an applicant, please contact him/her to arrange for the interview session. Our secretariat will not be involved in arranging the details for your applicants' interview session. Thank You. </div></div></div>

      <div class="row">
      
        <div class="span4">
         
<div class="accordion" id="accordion2">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        Tip 1: Accept or Reject the Applicants
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">
        To accept or reject the applicants, you may click on button <i class="icon-ok-sign pointer hn-color-green"></i> or <i class="icon-remove-sign pointer hn-color-red"></i> respectively. This function will notify them either their application is <u>accepted</u> or <u>rejected</u> for attending an interview with your company. Once approved or rejected, the applicant's name will be marked as <i class="icon-ok hn-color-green"></i> or <i class="icon-remove hn-color-red"></i> respectively.
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
        Tip 2: View the Applicant Details
      </a>
    </div>
    <div id="collapseTwo" class="accordion-body collapse">
      <div class="accordion-inner">
        To view the applicant details, you just have to click on their names and you will be redirected to their profile page. Applicants' details can be seen by the employers only, not available to the public.
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
        Tip 3: Download the Applicant's Resume/CV
      </a>
    </div>
    <div id="collapseThree" class="accordion-body collapse">
      <div class="accordion-inner">
        To download their resume/CV, you may go their profile page and there will be a button for that or just click on the button <i class="icon-download-alt pointer hn-color-purple"></i> which is next to their names. This button is only available when the applicant has uploaded his/her resume/CV.
      </div>
    </div>
  </div>
</div>
          
          
          
        <div id="applicantListbyJob">
        <div class="alert">When you click on <button type="button" class="btn btn-mini btn-primary"><i class="icon-user"></i> <strong>Applicant:</strong> X person(s)</button> button (if available) for <u>any job vacancy you have posted</u>, this message will be disappeared then <strong>the list of applicant names and option to ACCEPT (<i class="icon-ok-sign pointer hn-color-green"></i>) or REJECT (<i class="icon-remove-sign pointer hn-color-red"></i>) will be displayed here</strong>.</div>
        </div>  
          
          
        </div><!-- /.span4 -->
        
        <div class="span5">
        
          <div class="box">
          <h4 class="box-title"><i class="icon-bullhorn muted"></i> Your Posted Application (Job Vacancy/ Internship)</h4>
          
          <div class="alert alert-info">
  <span class="label label-info">Tip!</span> The list of applicants are available on each of the posted application details page.
  </div>
  
          <ul id="jobVacancy" class="unstyled jobs-vacancy">

<?php

$query_jv = $db->query("SELECT * FROM job_vacancy WHERE company_id='$comp_id' ORDER BY posted_date DESC");
$total_jv = $db->num($query_jv);
if ($total_jv == 0) { echo 'No applications posted yet..'; }
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
  
  $query_applyjob = $db->query("SELECT id FROM apply_job WHERE job_id='$jv_id'");
  $total_applicant = $db->num($query_applyjob);
  
  echo '<li id="jv-'.$jv_id.'"><div class="job-title"><span class="date pull-right"><small><strong>Date:</strong> ';
  echo date('j F Y', $posted_date);
  echo '</small></span><a href="jv.php?id='.$jv_id.'" class="title label label-inverse">';
  echo normalize($title);
  echo '</a>';
  echo '<br/><span class="location"><strong>Location:</strong> ';
  echo normalize($location);
  echo '</span><br/><span class="specialization"><strong>Specialization:</strong>  ';
  echo normalize($special);
  echo '<br/><strong><u>STATUS:</u></strong> <span id="openStatus-'.$jv_id.'">';
  if ($is_open == '1') {
    echo ' <span style="color:#468847"><i class="icon-folder-open tip-top" title="Status: Open"></i>OPEN</span>';
  } else {
    echo ' <span style="color:#b94a48"><i class="icon-folder-close tip-top" title="Status: Closed"></i>CLOSED</span>';
  }
  echo '</span>';
  echo '</span></div><div class="employer-button">';
  
  if ($total_applicant > 1) {
    echo '<button type="button" onClick="showApplicant('.$comp_id.','.$jv_id.')" class="btn btn-mini btn-primary applicant-status pull-left tip-top" title="Click here to display the list of applicant names at the left side"><i class="icon-user"></i> <strong>Applicant:</strong> '.$total_applicant.' persons</button>';
  } else if ($total_applicant == 1) {
    echo '<button type="button" onClick="showApplicant('.$comp_id.','.$jv_id.')" class="btn btn-mini btn-primary applicant-status pull-left tip-top" title="Click here to display the applicant name at the left side"><i class="icon-user"></i> <strong>Applicant:</strong> '.$total_applicant.' persons</button>';
  } else {
    echo '<span class="applicant-status pull-left"><i class="icon-user"></i> No applicant yet</span>';
  }
  
  echo '<div class="btn-group">';
  echo '<a href="jv.php?id='.$jv_id.'" class="btn btn-mini btn-info" type="button"><i class="icon-zoom-in"></i> View details</a> ';
  //echo '<a href="#" class="btn btn-mini btn-success" type="button"><i class="icon-edit"></i> Edit</a> ';
  if ($is_open == '1') {
    echo '<span id="openButton-'.$jv_id.'"><a href="#" onClick="close_jv('.$jv_id.')" class="btn btn-mini btn-warning tip-top" type="button" title="Close this job vacancy">Close</a></span> ';
  } else {
    echo '<span id="openButton-'.$jv_id.'"><a href="#" onClick="open_jv('.$jv_id.')" class="btn btn-mini btn-warning tip-top" type="button" title="Re-open this job vacancy">Re-open</a></span> ';
  }
  echo '<a href="edit_job_vacancy.php?id='.$jv_id.'" id="btnEditJV" class="btn btn-mini btn-success tip-top" type="button" title="Edit"><i class="icon-pencil"></i></a>';
  echo '<a href="#" id="btnDelJV" onClick="delete_jv('.$jv_id.')" class="btn btn-mini btn-danger tip-top" type="button" title="Delete"><i class="icon-trash"></i></a>';
  echo '</div></div></li>';
  
}

?>

          </ul>
          </div>
          
       </div><!-- /.span5 -->
       
        <div class="span3">
        
        <?php usermenu_employer('Dashboard'); ?>
        
        <!--
        <div class="well well-small">
        If you're no longer accepting any application for interviews, click the button below and the jobseekers will not be able to apply anymore.
        <div id="availBtn" style="margin-top:10px">
        <?php
        if ($is_avail == '1') {
          echo '<a href="#" onClick="NotAvail('.$comp_id.')" class="btn btn-danger">DISABLE APPLICATION</a>';
        } else {
          echo '<a href="#" onClick="Avail('.$comp_id.')" class="btn btn-success">RE-ENABLE APPLICATION</a>';
        }
        ?>
        </div>
        </div>
        -->
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    /*
    function NotAvail(comp_id) {
    $.ajax({
      type: 'POST',
      url: 'availability.php',
      data: 'status=0&comp_id='+comp_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#availBtn").html('<a href="#" onClick="Avail('+comp_id+')" class="btn btn-success">RE-ENABLE APPLICATION</a>').fadeIn();
          $("#availRes").html('<span style="color:#b94a48"><i class="icon-sign-blank"></i> DISABLED</span>').fadeIn();
        }
      }
    });
    }
    function Avail(comp_id) {
    $.ajax({
      type: 'POST',
      url: 'availability.php',
      data: 'status=1&comp_id='+comp_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#availBtn").html('<a href="#" onClick="NotAvail('+comp_id+')" class="btn btn-danger">DISABLE APPLICATION</a>').fadeIn();
          $("#availRes").html('<span style="color:#468847"><i class="icon-sign-blank"></i> ENABLED</span>').fadeIn();
        }
      }
    });
    }
    */
    function delete_jv(jv_id) {
    $.ajax({
      type: 'POST',
      url: 'delete_job_vacancy.php',
      data: 'jv_id='+jv_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("ul#jobVacancy li#jv-"+jv_id).remove().fadeOut("slow");
        }
      }
    });
    }
    function close_jv(jv_id) {
    $.ajax({
      type: 'POST',
      url: 'open_close_job_vacancy.php',
      data: 'status=0&jv_id='+jv_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#openStatus-"+jv_id).html(' <span style="color:#b94a48"><i class="icon-folder-close tip-top" title="Status: Closed"></i>CLOSED</span>');
          $("#openButton-"+jv_id).html('<a href="#" onClick="open_jv('+jv_id+')" class="btn btn-mini btn-warning tip-top" type="button" title="Re-open this job vacancy">Re-open</a>');
        }
      }
    });
    }
    function open_jv(jv_id) {
    $.ajax({
      type: 'POST',
      url: 'open_close_job_vacancy.php',
      data: 'status=1&jv_id='+jv_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("#openStatus-"+jv_id).html(' <span style="color:#468847"><i class="icon-folder-open tip-top" title="Status: Open"></i>OPEN</span>');
          $("#openButton-"+jv_id).html('<a href="#" onClick="close_jv('+jv_id+')" class="btn btn-mini btn-warning tip-top" type="button" title="Close this job vacancy">Close</a>');
        }
      }
    });
    }
    function showApplicant(cid,jid) {
    $.ajax({
      type: 'POST',
      url: 'applicants.php',
      data: 'cid='+cid+'&jid='+jid,
      success: function(responseData){
        $("#applicantListbyJob").html(responseData).fadeIn();
      }
    });
    }
    </script>

  </body>
</html>
