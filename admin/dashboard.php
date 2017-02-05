<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

// check if user is logged in
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {  
  if($_SESSION['user_group'] !== 'admin') { header('Location: index.php'); }
} else {
  header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <link href="jquery-ui/css/start/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="jtable/scripts/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
    
    <?php html_head_js(); ?>
    <script src="jquery-ui/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="jtable/scripts/jquery.jtable.js" type="text/javascript"></script>
  </head>

  <body>

    <?php html_body_topmenu(); ?>
    <?php html_body_header('Dashboard'); ?>

    <div class="container">

      <div class="row">
      <div class="span12">
        
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
  <li><a href="#jobseeker" data-toggle="tab">Jobseeker List</a></li>
  <li><a href="#employer" data-toggle="tab">Employer List</a></li>
  <li><a href="#jobVacancy" data-toggle="tab">Job Vacancy List</a></li>
  <li><a href="#admin" data-toggle="tab">Admin List</a></li>
  <li><a href="#request_loginid" data-toggle="tab">Request Login ID</a></li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="home">
  <div class="row">
  
  
  <div class="span3">
  <h4>Jobseeker List Section</h4>
  <p>In this section, don't touch anything since all jobseeker profiles are fully controlled by their respective owner. The Edit and Delete functions are needed for emergency/moderating reason.</p>
  
  <h4>Request Login ID Section</h4>
  <p>Just go to this section, everything has been explained over there.</p>
  </div>
  
  
  <div class="span3">
  <h4>Employer List Section</h4>
  <p>To add a new company record, use the button given at the top in this section and you will be redirected to <a href="add_employer.php">Add New Company page</a>. The Edit function in the table only will be used when necessary, otherwise don't touch anything since other information fields will be filled by the respective person who's in charge for the company account.</p>
  
  <h4>Emergency Contact</h4>
  <p>For any technical problem such as found the bugs or system errors, you may contact <a href="http://twitter.com/0x0000wayi">@wayi</a> (Mobile: 019-9559252). Support/maintenance is for 2 months - before and after Career Fair 2013.</p>
  </div>
  
  
  <div class="span3">
  <h4>Job Vacancy List Section</h4>
  <p>In this section, all posted job vacancies will be displayed. Any posted job vacancy that is highlighted with red means that it has been closed by the respective company to avoid any application by the jobseekers anymore and it will be automatically hidden from the jobseekers dashboard.</p>
  
   <h4>Career System Manual (Doc.)</h4>
  <p>For documentations on how to use this Career System is <a href="../manual_cs1.pdf" class="tip-top" title="Download Career System 1.0 Manual"><i class="icon-download"></i> available here</a> in PDF format. Just download it.</p>
  </div>
  
  
  <div class="span3">
  <h4>Admin List Section</h4>
  <p>In this section, don't touch anything unless to add/edit any admin login account/profile. Make sure to use the Password Hashing Tool given to hash the plain password for adding a new admin account. The plain password will be hashed together with predefined salt in MD5 algorithm for security reason.</p>
  </div>
  
  
  </div>
  </div>
  
  <div class="tab-pane" id="jobseeker">
  <div id="jobseekerList">
  </div>
  </div>
  
  <div class="tab-pane" id="employer">
  <p><a href="add_employer.php" class="btn btn-primary"><i class="icon-plus"></i> Add new company record</a></p>
  <!-- THIS PART IS CANCELLED. REPLACED BY SPECIAL PAGE FOR ADDING NEW RECORD
  <div class="row">
  <div class="span4">
  <div class="alert alert-info">To add a new company, click on <span class="label label-info tip-top" title="At the right bottom of table">+ Add new record</span> link, then fill in the data into the fields which are marked by (*) symbol only. Please use <strong>Password Hashing Tool</strong> to hash the plain password with Career System predefined salt. Otherwise, login may not work!</div>
  </div>
  <div class="span8">
  <div class="well well-small">
  <h4>Password Hashing Tool</h4>
  <form class="form-inline">
  <input type="text" id="plainPassword1" class="input-medium" placeholder="Enter plain password">
  <a href="#" onClick="hashPW(1)" class="btn btn-danger tip-top" title="Hashing"><i class="icon-chevron-right"></i> MD5 <i class="icon-chevron-right"></i></a>
  <input type="text" id="hashedPassword1" class="input-xlarge" placeholder="Result will be here">
  </form>
  Random plain password (<a href="#" id="genRandPW" onClick="genRandPW()">Regenerate</a>): <span id="genRandPass" style="font-weight:bold"></span>
  </div>
  </div>
  </div>
  -->
  <div id="employerList">
  </div>
  </div>
  
  <div class="tab-pane" id="jobVacancy">
  <div id="jobVacancyList">
  <div class="alert"><span class="label label-info">Note!</span> <span class="label label-important">Red Highlight</span> means the particular job vacancy has been closed by the respective company. The closed job vacancy will be automatically hidden from the jobseekers dashboard and the button to apply will be completely disabled. This function is to avoid from any application by the jobseekers.</div>
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$queryjv = $db->query("SELECT * FROM job_vacancy ORDER BY posted_date DESC, company_id ASC");
$totaljv = $db->num($queryjv);
if ($totaljv == 0) { echo 'No any posted job yet.'; }
else {
echo '<table class="table table-hover table-bordered table-condensed"><thead>';
echo '<tr><th></th><th style="text-align:center">Job Title</th><th style="text-align:center">Applicant <i class="icon-print"></i></th><th style="text-align:center">Posted Date</th><th style="text-align:center">Company Name</th><th></th></tr>';
echo '</thead><tbody>';
$count = 0;
while ($jv = $db->fetch_assoc($queryjv)) {
  $count++;
  $jvid = $jv['id'];
  $jvtitle = $jv['title'];
  $jvcompid = $jv['company_id'];
  $jvdate = $jv['posted_date'];
  $jvopen = $jv['is_open'];
  $query_comp = $db->query("SELECT company_name FROM employer WHERE id='$jvcompid'");
  $comp = $db->fetch_array($query_comp);
  $comp_name = $comp['company_name'];
  
  $query_a = $db->query("SELECT * FROM apply_job WHERE job_id='$jvid'");
  $total_a = $db->num($query_a);

  if ($jvopen == '0') {
    echo '<tr id="jvid-'.$jvid.'" class="error">';
  } else {
    echo '<tr id="jvid-'.$jvid.'">';
  }
  echo '<td style="text-align:center">'.$count.'</td>';
  echo '<td><a href="../jv.php?id='.$jvid.'" target="_blank">'.normalize($jvtitle).'</a></td>';
  echo '<td style="text-align:center">';
  if ($total_a > 0) {
    if ($total_a == 1) {
      echo '<a href="../printable/jval_admin.php?id='.$jvid.'" target="_blank">1 person</a>';
    } else {
      echo '<a href="../printable/jval_admin.php?id='.$jvid.'" target="_blank">'.$total_a.' persons</a>';
    }
  } else {
    echo '0';
  }
  echo '</td>';
  echo '<td>'.date('j F Y', $jvdate).'</td>';
  echo '<td>'.normalize($comp_name).'</td>';
  echo '<td style="text-align:center"><a href="#" class="btn btn-danger btn-mini" onClick="delete_jv('.$jvid.')"><i class="icon-trash"></i> Delete</a></td>';
  echo '</tr>';
}
echo '</tbody></table>';
}
$db->close();
  
?>
  </div>
  </div>
  
  <div class="tab-pane" id="admin">
  <div class="well well-small">
  <h4>Password Hashing Tool</h4>
  <form class="form-inline">
  <input type="text" id="plainPassword2" class="input-medium" placeholder="Enter plain password">
  <a href="#" onClick="hashPW(2)" class="btn btn-danger tip-top" title="Hashing"><i class="icon-chevron-right"></i> MD5 <i class="icon-chevron-right"></i></a>
  <input type="text" id="hashedPassword2" class="input-xlarge" placeholder="Result will be here">
  </form>
  </div>
  <div id="adminList">
  </div>
  </div>
  
  <div class="tab-pane" id="request_loginid">
  <div id="rlidList">
  <div class="alert">
  <ul>
  <li>This section has been prepared because of the use of 'Request Login ID' button at the index page in 'Login as Employer' section.'</li>
  <li>Every company who officially participated in our Career Fair event should be already received the login information via email.</li>
  <li>Just in case they don't receive it, so they can manually request it from the 'Request Login ID' button.</li>
  <li>ONLY CLICK ON 'DONE?' BUTTON AFTER YOU HAS SENT THEM THE LOGIN INFORMATION. OTHERWISE, DON'T DO ANYTHING, BECAUSE ONCE CLICKED, IT'S CONSIDERED THE TASK IS DONE.</li>
  <li>These records are just for to-do list for our works as Administrator.</li>
  </ul>
  </div>
<?php
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$queryrlid = $db->query("SELECT * FROM request_loginid ORDER BY status");
$totalrlid = $db->num($queryrlid);
if ($totalrlid == 0) { echo 'No any request yet.'; }
else {
echo '<table class="table table-hover table-bordered table-condensed"><thead>';
echo '<tr><th></th><th style="text-align:center">Company Name</th><th style="text-align:center">Contact Name</th><th style="text-align:center">Email Address</th><th style="text-align:center">Status</th></tr>';
echo '</thead><tbody>';
$count2 = 0;
while ($rlid = $db->fetch_assoc($queryrlid)) {
  $count2++;
  $rlid_id = $rlid['id'];
  $rlid_name = $rlid['person_name'];
  $rlid_comp = $rlid['comp_name'];
  $email = $rlid['email'];
  $rlid_status = $rlid['status'];
  if ($rlid_status == '1') {
    echo '<tr id="rlid-'.$rlid_id.'" class="success">';
  } else {
    echo '<tr id="rlid-'.$rlid_id.'">';
  }
  echo '<td style="text-align:center">'.$count2.'</td>';
  echo '<td>'.normalize($rlid_comp).'</td>';
  echo '<td>'.normalize($rlid_name).'</td>';
  echo '<td>'.normalize($email).'</td>';
  if ($rlid_status == '1') {
    echo '<td style="text-align:center" id="rlidDone-'.$rlid_id.'"><a href="#" class="btn btn-success btn-mini disabled"><i class="icon-ok"></i> DONE</a> <a href="#" class="btn btn-danger btn-mini" onClick="delete_request_sent('.$rlid_id.')"><i class="icon-trash"></i></a></td>';
  } else {
    echo '<td style="text-align:center" id="rlidDone-'.$rlid_id.'"><a href="#" class="btn btn-success btn-mini" onClick="request_sent('.$rlid_id.')">DONE?</a></td>';
  }
  echo '</tr>';
}
echo '</tbody></table>';
}
$db->close();
  
?>
  </div>
  </div>
  
</div>

      </div><!-- /.span12 -->
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    
      $('#jobseekerList').jtable({
				title: 'Table of Jobseekers',
				paging: true,
				pageSize: 10,
				sorting: true,
				defaultSorting: 'fullname ASC',
				actions: {
					listAction: 'jtable/jobseeker.php?action=list',
					createAction: 'jtable/jobseeker.php?action=create',
					updateAction: 'jtable/jobseeker.php?action=update',
					deleteAction: 'jtable/jobseeker.php?action=delete'
				},
				fields: {
					id: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					username: {
						title: 'Username'
					},
					password: {
						title: 'Password Hash (MD5)',
						list: false
					},
					fullname: {
						title: 'Fullname',
						display: function (data) {
              return '<a href="../jobseeker.php?id='+data.record.id+'" target="_blank" title="Visit this jobseeker profile page">'+data.record.fullname+'</a>';
            }
					},
					ic_no: {
						title: 'IC No.',
						list: false
					},
					gender: {
						title: 'Gender',
						options: { 'Male': 'Male', 'Female': 'Female' },
						list: false
					},
					email: {
						title: 'Email'
					},
					phone: {
						title: 'Phone No.'
					},
					address: {
						title: 'Address',
						type: 'textarea',
						list: false
					},
					postcode: {
						title: 'Postcode',
						list: false
					},
					state: {
						title: 'State',
						options: { 'Johor': 'Johor', 'Kedah': 'Kedah', 'Kelantan': 'Kelantan', 'Kuala Lumpur': 'Kuala Lumpur', 'Melaka': 'Melaka', 'Negeri Sembilan': 'Negeri Sembilan', 'Pahang': 'Pahang', 'Penang': 'Penang', 'Perak': 'Perak', 'Perlis': 'Perlis', 'Sabah': 'Sabah', 'Sarawak': 'Sarawak', 'Selangor': 'Selangor', 'Terengganu': 'Terengganu' },
						list: false
					},
					is_usm: {
						title: 'USM Student',
						list: false,
						options: { '1': 'Yes', '0': 'No' }
					},
					study_field: {
						title: 'Course',
						list: false
					},
					is_undergrad: {
						title: 'Education',
						list: false,
						options: { '1': 'Undergraduate', '0': 'Postgraduate', '2': 'None' }
					},
					has_resume: {
						title: 'Resume Upload',
						list: false,
						options: { '1': 'Yes', '0': 'No' }
					},
					resume_path: {
						title: 'Resume File Path',
						list: false
					},
					reg_date: {
						title: 'Registered',
						type: 'date',
						create: false,
						edit: false,
						list: false
					}
				}
			});
			$('#jobseekerList').jtable('load');
			
			$('#employerList').jtable({
				title: 'Table of Employers',
				paging: true,
				pageSize: 10,
				sorting: true,
				defaultSorting: 'company_name ASC',
				actions: {
					listAction: 'jtable/employer.php?action=list',
					//createAction: 'jtable/employer.php?action=create',
					updateAction: 'jtable/employer.php?action=update',
					deleteAction: 'jtable/employer.php?action=delete'
				},
				fields: {
					id: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					username: {
						title: 'Login ID *'
					},
					password: {
						title: 'Password Hash (MD5) *',
						list: false
					},
					company_name: {
						title: 'Company Name *',
						display: function (data) {
              return '<a href="../employer.php?id='+data.record.id+'" target="_blank" title="Visit this company profile page">'+data.record.company_name+'</a>';
            }
					},
					person_prefix: {
						title: 'Contact Name Prefix',
						list: false
					},
					person_name: {
						title: 'Contact Name'
					},
					email: {
						title: 'Email',
						list: false
					},
					phone: {
						title: 'Phone No.',
						list: false
					},
					mobile: {
						title: 'Mobile No.',
						list: false
					},
					department: {
						title: 'Position/Department',
						list: false
					},
					company_info: {
						title: 'Company Info',
						type: 'textarea',
						list: false
					},
					company_website: {
						title: 'Company Website',
						list: false
					},
					company_logo_url: {
						title: 'Company Logo URL',
						list: false
					},
					company_tag: {
						title: 'Company Tags',
						list: false
					},
					is_sponsor: {
						title: 'Sponsor *',
						options: { '1': 'Yes', '0': 'No' }
					},
					sponsor_type: {
						title: 'Sponsor Type *'
					},
					is_available: {
						title: 'Available *',
						options: { '1': 'Yes', '0': 'No' }
					}
				}
			});
			$('#employerList').jtable('load');
			
			$('#adminList').jtable({
				title: 'Table of Admins',
				paging: true,
				pageSize: 10,
				sorting: true,
				defaultSorting: 'username ASC',
				actions: {
					listAction: 'jtable/admin.php?action=list',
					createAction: 'jtable/admin.php?action=create',
					updateAction: 'jtable/admin.php?action=update',
					deleteAction: 'jtable/admin.php?action=delete'
				},
				fields: {
					id: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					username: {
						title: 'Login ID'
					},
					password: {
						title: 'Password Hash (MD5)'
					},
					fullname: {
						title: 'Fullname'
          },
					email: {
						title: 'Email'
					},
					phone: {
						title: 'Phone'
					}
				}
			});
			$('#adminList').jtable('load');
			
   function delete_jv(jv_id) {
    $.ajax({
      type: 'POST',
      url: '../delete_job_vacancy.php',
      data: 'jv_id='+jv_id,
      success: function(responseData){
        if (responseData == 'OK') {
          $("tr#jvid-"+jv_id).remove().fadeOut();
        }
      }
    });
    }
    
    function request_sent(rlid) {
    $.ajax({
      type: 'POST',
      url: 'request_loginid_sent.php',
      data: 'action=done&rlid='+rlid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("td#rlidDone-"+rlid).html('<a href="#" class="btn btn-success btn-mini disabled"><i class="icon-ok"></i> DONE</a> <a href="#" class="btn btn-danger btn-mini" onClick="delete_request_sent('+rlid+')"><i class="icon-trash"></i></a>');
        }
      }
    });
    }
    function delete_request_sent(rlid) {
    $.ajax({
      type: 'POST',
      url: 'request_loginid_sent.php',
      data: 'action=delete&rlid='+rlid,
      success: function(responseData){
        if (responseData == 'OK') {
          $("tr#rlid-"+rlid).remove().fadeOut();
        }
      }
    });
    }
    
   function hashPW(number){
      var plainpass = $('#plainPassword' + number).val();
      if (plainpass.length == 0) { alert('ERROR: Please enter plain password to hash.'); }
      else {
      $.ajax({
        type: 'POST',
        url: 'password_hashing.php',
        data: 'input=' + urlencode(plainpass),
        success: function(responseData){
          $('#hashedPassword' + number).val(responseData);
        }
      });
      }
    }
    
    function genRandPW(){
      $.ajax({
        type: 'POST',
        url: 'generate_random_password.php',
        data: 'generate=1',
        success: function(responseData){
          $('#genRandPass').text(responseData);
        }
      });
    }
    
function stripslashes(str) {
  return (str + '').replace(/\\(.?)/g, function (s, n1) {
    switch (n1) {
    case '\\':
      return '\\';
    case '0':
      return '\u0000';
    case '':
      return '';
    default:
      return n1;
    }
  });
}
			
    </script>
    

  </body>
</html>
