<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';
require_once DIR . 'includes/phpmailer.class.php';

// check if user is logged in
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {  
  if($_SESSION['user_group'] !== 'admin') { header('Location: index.php'); }
} else {
  header('Location: index.php');
}

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['btnAdd']) && !empty($_POST)) {
  $group = 'employer';
  $username = $db->sanitize($_POST['username']);
  $password = $db->sanitize($_POST['password']);
  $cname = $db->sanitize($_POST['company_name']);
  $email = $db->sanitize($_POST['email']);
  if (isset($_POST['is_sponsor']) == 'Yes' && isset($_POST['sponsor_type'])) {
    $is_sponsor = '1';
    $sponsor_type = $db->sanitize($_POST['sponsor_type']);
  } else {
    $is_sponsor = '0';
    $sponsor_type = '';
  }
  
  if ($cname == '') {
    $display_response = '<div id="notifyError" class="alert alert-error">ERROR: Company Name is required!</div>';
    renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response);
  } else if ($email == '') {
    $display_response = '<div id="notifyError" class="alert alert-error">ERROR: Contact Email is required!</div>';
    renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response);
  } else if (!username($username)) {
    $display_response = '<div id="notifyError" class="alert alert-error">ERROR: Only alphanumerics and underscore are allowed for username.</div>';
    renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response);
  } else {
    $md5pass = md5(PW_SALT . $password);
    $db->query("INSERT INTO $group (username, password, company_name, email, is_sponsor, sponsor_type) VALUES ('$username', '$md5pass', '$cname', '$email', '$is_sponsor', '$sponsor_type')");
    
    // send email
    $mail = new PHPMailer;
    $mail->From = 'careerfair@eng.usm.my';
    $mail->FromName = 'Career Fair App';
    $mail->AddAddress($email, $username); // Add a recipient
    $mail->AddCC('mfaiz.pirates@gmail.com');
    $mail->IsHTML(true); // Set email format to HTML
    $mail->Subject = 'Your New Account for Career System';
    $mail->Body    = '<span style="color:#888;text-style:italic">Hi, I\'m a Robot, please don\'t reply!</span>';
    $mail->Body    .= '<hr style="border:0;border-bottom:1px dashed #555;margin:20px auto">';
    $mail->Body    .= '<b><u>Career Portal - Career Fair 2014, Engineering Campus, USM</u></b>';
    $mail->Body    .= '<br/><br/>Username: <b>'.$username.'</b>';
    $mail->Body    .= '<br/>Default Password: <b style="color:red;">'.$password.'</b>';
    $mail->Body    .= '<br/>Company Name: <b style="color:red;">'.$cname.'</b>';
    $mail->Body    .= '<br/>Contact Email: <b style="color:red;">'.$email.'</b>';
    $mail->Body    .= '<hr style="border:0;border-bottom:1px dashed #555;margin:20px auto">';
    $mail->Body    .= '<b><u>NOTE<u>:</b> Your account has been created as <b>Employer account</b> by Administrator and this is your login information. To login, please visit our <b>Career Portal</b> by <a href="http://careerfair.eng.usm.my/app">clicking here</a>.';
    $mail->Body    .= '<br/>App Name: <i>Career System 1.0</i>';
    $mail->Body    .= '<br/>App URL: <i><a href="http://careerfair.eng.usm.my/app">http://careerfair.eng.usm.my/app</a></i>';

    if(!$mail->Send()) {
      $display_response = '<div id="notifySuccess" class="alert alert-error">Mailer Error: ' . $mail->ErrorInfo . '</div>';
      renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response);
    } else {
      $display_response = '<div id="notifySuccess" class="alert alert-success">New employer has been successfully added into database and a copy of login information has been sent to the email.</div>';
      renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response);
    }

  }
} else {
  $password = generate_password();
  renderForm('', $password, '', '', '0', '', '');
}

function renderForm($username, $password, $cname, $email, $is_sponsor, $sponsor_type, $display_response) {
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
    <?php html_body_header('Add Employer'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span6">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Add New Company Record <a href="dashboard.php" class="pull-right">Back to Dashboard <i class="icon-arrow-right"></i></a></h4>
          
          <form class="form-horizontal edit-profile-employer" style="margin:20px" method="post" action="">
          
          <?php echo $display_response; ?>
          
          <legend>Login Information</legend>
          
          <div class="control-group">
              <label class="control-label" for="inputUsername">Login ID:</label>
                <div class="controls">
                <input class="span2" type="text" id="inputUsername" name="username" value="<?php echo $username; ?>" required>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputPassword">Password:</label>
                <div class="controls control-row">
                <input class="span2" type="text" id="inputPassword" name="password" value="<?php echo $password; ?>" required> <a href="#" onClick="genRandPW()" class="btn btn-link"><i class="icon-refresh"></i> Regenerate</a>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputCompanyName">Company Name:</label>
                <div class="controls">
                <input class="span3" type="text" id="inputCompanyName" name="company_name" value="<?php echo $cname; ?>" required>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputEmail">Contact Email:</label>
                <div class="controls">
                <input class="span3" type="email" id="inputEmail" name="email" value="<?php echo $email; ?>" required>
                <span class="help-block">A copy of this login information also will be sent to this contact email.</span>
                </div>
          </div>        
                   
          <legend>Sponsor Information</legend>
          <div class="control-group">
              <label class="control-label" for="is_sponsor">Is Sponsor?</label>
                <div class="controls">
                <select id="is_sponsor" name="is_sponsor">
                <?php
                if ($is_sponsor == '0') {
                  echo '<option id="noSponsor" value="No">No</option>';
                  echo '<option id="yesSponsor" value="Yes">Yes</option>';
                } else {
                  echo '<option id="noSponsor" value="No">No</option>';
                  echo '<option id="yesSponsor" value="Yes" selected>Yes</option>';
                }
                ?>
                </select>                
                </div>
               </div>
               
          <div class="control-group">
              <label class="control-label" for="sponsorType">Sponsor Type:</label>
                <div class="controls">
                <input class="span3" type="text" id="sponsorType" placeholder="e.g. Gold Sponsor / Silver Sponsor / Bronze Sponsor" name="sponsor_type" value="<?php echo $sponsor_type; ?>">
                </div>                
          </div>
          
          <div class="form-actions">
                <button type="submit" name="btnAdd" class="btn btn-primary">Add</button>
                <a href="index.php" class="btn btn-inverse">Cancel</a>
               </div>
               
          </form>         
         
          </div><!-- /.box -->        
          </div><!-- /.span6 -->
        
      
        <div class="span6">
        
      <div id="employerList"></div>
          
        </div><!-- /.span6 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    var update_sponsor = function () {
      if ($("#yesSponsor").is(":selected")) {
        $('#sponsorType').prop('disabled', false);
      }
      else {
        $('#sponsorType').prop('disabled', 'disabled');
      }
    };
    $(update_sponsor);
    $("#is_sponsor").change(update_sponsor);
    
    function genRandPW(){
      $.ajax({
        type: 'POST',
        url: 'generate_random_password.php',
        data: 'generate=1',
        success: function(responseData){
          $('#inputPassword').val(responseData);
        }
      });
    }
    
    $('#employerList').jtable({
				title: 'Table of Employers',
				paging: true,
				pageSize: 10,
				sorting: true,
				defaultSorting: 'company_name ASC',
				actions: {
					listAction: 'jtable/employer_addsection.php?action=list',
					updateAction: 'jtable/employer_addsection.php?action=update',
					deleteAction: 'jtable/employer_addsection.php?action=delete'
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
						title: 'Password Hash (MD5)',
						list: false
					},
					company_name: {
						title: 'Company Name',
						display: function (data) {
              return '<a href="../employer.php?id='+data.record.id+'" target="_blank" title="Visit this company profile page">'+data.record.company_name+'</a>';
            }
					},
					email: {
						title: 'Email',
						list: false
					},
					is_sponsor: {
						title: 'Sponsor',
						options: { '1': 'Yes', '0': 'No' },
						list: false
					},
					sponsor_type: {
						title: 'Sponsor Type',
						list: false
					},
					is_available: {
						title: 'Available',
						options: { '1': 'Yes', '0': 'No' },
						list: false
					}
				}
			});
			$('#employerList').jtable('load');
    </script>
   
  </body>
</html>

<?php } ?>