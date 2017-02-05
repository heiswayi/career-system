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
    <?php html_body_header('Edit Profile'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span9">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Edit Company Profile</h4>
          <form class="form-horizontal edit-profile-employer" style="margin:20px">
          
          <legend>Company Information</legend>
          
          <div class="control-group">
              <label class="control-label" for="inputCompanyName">Company Name:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputCompanyName" value="" required>
                </div>
          </div>         
          
           <div class="control-group">
              <label class="control-label" for="inputCompanyInfo">Company Info:</label>
                <div class="controls">
                <textarea id="inputCompanyInfo" placeholder="Brief introduction about your company..." rows="6" style="resize:none" class="span4"></textarea>
                </div>
          </div> 
          
          <div class="control-group">
              <label class="control-label" for="inputWeb">Website:</label>
                <div class="controls">
                <input class="span4" type="url" id="inputWeb" placeholder="e.g. http://yourcompany.com" value="">
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputLogo">Company Logo URL:</label>
                <div class="controls">
                <input class="span4 pop-logo-url" type="url" id="inputLogo" placeholder="e.g. http://yourcompany.com/logo.png" title="Note!" data-content="Logo will automatically resize to our specific ratio" value="">
                </div> 
           </div>
           
          <div class="control-group">
              <label class="control-label" for="inputTag">Tags:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputTag" placeholder="e.g. Electronics, Robot, IT, Software" value="">
                </div> 
           </div>           
                   
          <legend>Contact Information</legend>
          <div class="alert alert-info">
          Your contact information which enables the jobseekers/students to contact with you.
          </div>
          <div class="control-group">
              <label class="control-label" for="inputPrefixPerson">Name Prefix:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputPrefixPerson" placeholder="e.g. Mr / Mrs / Ms / etc." value="">
                </div>
          </div>   
          
          <div class="control-group">
              <label class="control-label" for="inputNamePerson">Name:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputNamePerson" value="" required>
                </div>
          </div>          
          
          <div class="control-group">
              <label class="control-label" for="inputPositionPerson">Position/Department:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputPositionPerson" placeholder="e.g. Human Resource / Manager" value="">
                </div>                
          </div>           
          
          <div class="control-group">
              <label class="control-label" for="inputPersonPhone">Phone Number:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputPersonPhone" value="">
                </div>                
          </div>  
          
          <div class="control-group">
              <label class="control-label" for="inputPersonMobile">Mobile Number:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputPersonMobile" value="">
                </div>                
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputPersonEmail">Email:</label>
                <div class="controls">
                <input class="span4" type="email" id="inputPersonEmail" placeholder="e.g. example@domain.com" value="" required>
                </div>                
          </div>
          
          <div id="notifyError" class="alert alert-error hide">--Error Response--</div>
          <div id="notifySuccess" class="alert alert-success hide">--Success Response--</div>
          
          <div class="form-actions">
                <input type="hidden" id="username" value="">
                <a href="#" id="btnUpdate" class="btn btn-primary">Update</a>
                <a href="dashboard_employer.php" class="btn btn-inverse">Cancel</a>
               </div>
               
          </form>         
         
          </div><!-- /.box -->        
          </div><!-- /.span9 -->
        
      
        <div class="span3">
        
      <?php usermenu_employer('Edit Profile'); ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    $('#btnUpdate').click(function(e){
      e.preventDefault();
      $('#notifySuccess').fadeOut();
      var company_name = $('#inputCompanyName').val();
      var company_info = $('#inputCompanyInfo').val();
      var company_web = $('#inputWeb').val();
      var company_logo = $('#inputLogo').val();
      var company_tag = $('#inputTag').val();
      var person_prefix = $('#inputPrefixPerson').val();
      var person_name = $('#inputNamePerson').val();
      var person_position = $('#inputPositionPerson').val();
      var person_phone = $('#inputPersonPhone').val();
      var person_mobile = $('#inputPersonMobile').val();
      var person_email = $('#inputPersonEmail').val();
      var username = $('#username').val();
      var postData = 'usergroup=employer&username='+urlencode(username)+'&cname='+urlencode(company_name)+'&cinfo='+urlencode(company_info)+'&cweb='+urlencode(company_web)+'&clogo='+urlencode(company_logo)+'&ctag='+urlencode(company_tag)+'&pprefix='+urlencode(person_prefix)+'&pname='+urlencode(person_name)+'&pposition='+urlencode(person_position)+'&pphone='+urlencode(person_phone)+'&pmobile='+urlencode(person_mobile)+'&pemail='+urlencode(person_email);
      $.ajax({
        type: 'POST',
        url: 'edit_profile_process.php',
        data: postData,
        success: function(responseData){
          if (responseData !== 'OK') {
            $('#notifyError').html(responseData).fadeIn().delay(3000).fadeOut();
          } else {
            $('#notifySuccess').html('Your profile information has been updated successfully.').fadeIn();
          }
        }
      });
    });
    
    // initialize getData() function at first start
    getData('<?php echo $_SESSION['user_login']; ?>');
    function getData(username){
      var postData = 'usergroup=employer&username='+urlencode(username);
      $.ajax({
        type: 'POST',
        url: 'edit_profile_getdata.php',
        data: postData,
        success: function(responseData){
          if (responseData == 'KO') {
            $('#notifyError').html('Informations are not available!').fadeIn().delay(3000).fadeOut();
          } else {
            // get data: company_name, company_info, company_web, company_logo, company_tag, person_prefix, person_name, person_position, person_phone, person_mobile, person_email, username
            var initData = responseData.split('[]');
            $('#inputCompanyName').val(initData[0]);
            $('#inputCompanyInfo').val(initData[1]);
            $('#inputWeb').val(initData[2]);
            $('#inputLogo').val(initData[3]);
            $('#inputTag').val(initData[4]);
            $('#inputPrefixPerson').val(initData[5]);
            $('#inputNamePerson').val(initData[6]);
            $('#inputPositionPerson').val(initData[7]);
            $('#inputPersonPhone').val(initData[8]);
            $('#inputPersonMobile').val(initData[9]);
            $('#inputPersonEmail').val(initData[10]);
            $('#username').val(initData[11]);
          }
        }
      });
    }
    </script>
  </body>
</html>
