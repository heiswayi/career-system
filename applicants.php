<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['cid']) && isset($_POST['jid']) && !empty($_POST['cid']) && !empty($_POST['jid'])) {

$cid = $db->sanitize($_POST['cid']);
$jid = $db->sanitize($_POST['jid']);

$query_aj = $db->query("SELECT * FROM apply_job WHERE job_id='$jid'");
$total_aj = $db->num($query_aj);

$query_jv = $db->query("SELECT title FROM job_vacancy WHERE id='$jid'");
$row_jv = $db->fetch_array($query_jv);
$job_title = $row_jv['title'];

echo '<div id="applicantListBox" class="box">';
echo '<h4 class="box-title"><i class="icon-reorder muted"></i> Applicant List <small><a href="printable/jval.php?id='.$jid.'" target="_blank"><i class="icon-print"></i> Printable Version</a></small> <small class="pull-right pointer" onClick="removeThis()"><i class="icon-remove"></i></small></h4>';

if ($total_aj == 0) { echo 'No applicant yet.'; }
else {

echo '<div class="alert" style="margin-top:10px">';
echo '<h4><i class="icon-hand-right"></i> '.normalize($job_title).'</h4>';
echo 'The name of applicants who applied for <strong>an interview with your company</strong> to get this job.';
echo '</div>';
echo '<ol id="applicantList" class="applicant-list">';
          
while ($rowa = $db->fetch_assoc($query_aj)) {
  $row_id = $rowa['id'];
  $a_id = $rowa['jobseeker_id'];
  $accept_status = $rowa['accept_status'];
  
  $query_js = $db->query("SELECT * FROM jobseeker WHERE id='$a_id'");
  $jsd = $db->fetch_array($query_js);
  $fullname = $jsd['fullname'];
  $has_resume = $jsd['has_resume'];
  $resume_path = $jsd['resume_path'];
  
  echo '<li id="ai-'.$a_id.'">';
  echo '<a href="jobseeker.php?id='.$a_id.'">'.normalize($fullname).'</a> ';
  if ($has_resume == '1' && $resume_path !== '') {
    echo '<a href="dl.php?uid='.$a_id.'"><i class="icon-download-alt hn-color-purple tip-top" title="Download resume/CV"></i></a> ';
  }
  echo '<span id="aiControl" class="pull-right">';
  if ($accept_status == '1') {
    echo '<i class="icon-ok hn-color-green tip-top" title="Approved"></i> ';
    echo '<i onClick="reject_a('.$jid.','.$a_id.')" class="icon-remove-sign pointer hn-color-red tip-top muted" title="Reject this applicant"></i>';
  } else if ($accept_status == '0') {
    echo '<i onClick="accept_a('.$jid.','.$a_id.')" class="icon-ok-sign pointer hn-color-green tip-top" title="Approve this applicant for attending the interview"></i> ';
    echo '<i class="icon-remove hn-color-red tip-top" title="Rejected"></i>';
  } else {
    echo '<i onClick="accept_a('.$jid.','.$a_id.')" class="icon-ok-sign pointer hn-color-green tip-top" title="Approve this applicant for attending the interview"></i> ';
    echo '<i onClick="reject_a('.$jid.','.$a_id.')" class="icon-remove-sign pointer hn-color-red tip-top" title="Reject this applicant"></i>';
  }
  echo '</span>';
  
  echo '</li>';
  
}
            
echo '</ol>';

}

echo '</div>';

?>

<script>
function accept_a(jid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'accept_status.php',
      data: 'status=1&jid='+jid+'&jsid='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("li#ai-"+jsid+" span#aiControl").html('<i class="icon-ok hn-color-green tip-top" title="Approved"></i> <i onClick="reject_a('+jid+','+jsid+')" class="icon-remove-sign pointer hn-color-red tip-top muted" title="Reject this applicant"></i>').fadeIn();
        }
      }
    });
}
function reject_a(jid,jsid) {
    $.ajax({
      type: 'POST',
      url: 'accept_status.php',
      data: 'status=0&jid='+jid+'&jsid='+jsid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("li#ai-"+jsid+" span#aiControl").html('<i onClick="accept_a('+jid+','+jsid+')" class="icon-ok-sign pointer hn-color-green tip-top" title="Approve this applicant for attending the interview"></i> <i class="icon-remove hn-color-red tip-top" title="Rejected"></i>').fadeIn();
        }
      }
    });
}
function removeThis() {
  $("#applicantListBox").remove().fadeOut();
}
    
<?php

}

?>