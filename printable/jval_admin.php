<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

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
    header('Location: ../404.php');
  } else {
    $row = $db->fetch_array($query);
    $title = $row['title'];
    $posted_date = $row['posted_date'];
    $comp_id = $row['company_id'];
    
    $query_e = $db->query("SELECT * FROM employer WHERE id='$comp_id'");
    $rowe = $db->fetch_array($query_e);
    $comp_name = $rowe['company_name'];
    
    renderHTML($jv_id, $title, $comp_id, $comp_name);
  }
} else {
  header('Location: ../404.php');
}

function renderHTML($jv_id, $title, $comp_id, $comp_name) {

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <?php html_head_js(); ?>
  </head>

  <body>
    
    <?php html_body_header('Applicant List'); ?>

    <div class="container">

      <div class="row">
      
      <div class="span12">
           
<?php

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
if ($_SESSION['user_group'] == 'admin') {

$query_a = $db->query("SELECT * FROM apply_job WHERE job_id='$jv_id'");
$total_a = $db->num($query_a);
if ($total_a > 1) { $show_total = $total_a.' persons'; }
else { $show_total = $total_a.' person'; }

echo '<p>';
echo 'Job title: <strong>'.normalize($title).'</strong><br/>';
echo 'Company name: <strong>'.normalize($comp_name).'</strong><br/>';
echo 'Total applicant: <strong>'.$show_total.'</strong><br/>';
echo 'Category: <strong>Accept, Reject, Undefined (-)</strong> (<a href="jvala_admin.php?id='.$jv_id.'">Click here for List of Accepted-Only Applicants</a>)';
echo '</p>';

echo '<table class="table table-bordered">';
echo '<thead><tr>';
echo '<th>Fullname</th>';
echo '<th>Phone Number</th>';
echo '<th>Email Address</th>';
echo '<th>Accept Status</th>';
echo '</tr></thead>';
echo '<tbody>';
while ($rowa = $db->fetch_assoc($query_a)) {
$get_js_id = $rowa['jobseeker_id'];
$accept_status = $rowa['accept_status'];
$query_aa = $db->query("SELECT * FROM jobseeker WHERE id='$get_js_id'");
$param = $db->fetch_array($query_aa);
$js_fullname = $param['fullname'];
$js_email = $param['email'];
$js_phone = $param['phone'];
echo '<tr>';
echo '<td>'.normalize($js_fullname).'</td>';
echo '<td>'.normalize($js_phone).'</td>';
echo '<td>'.normalize($js_email).'</td>';
if ($accept_status == '1') { echo '<td>Accepted</td>'; }
else if ($accept_status == '0') { echo '<td>Rejected</td>'; }
else { echo '<td>-</td>'; }
echo '</tr>';
}
echo '</tbody>';
echo '</table>';

}
}

?>
      
      </div>
        
      </div><!-- /.row -->

    <?php printable_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>

<?php } ?>
