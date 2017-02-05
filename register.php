<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

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
    <?php html_body_header('Registration'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span8">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Register as Job Seeker</h4>
          <form class="form-horizontal register-jobseeker" style="margin:20px">
          
          <legend>Login Information</legend>
          
          <div class="control-group">
                <div class="controls">
                <span class="help-block"><span class="label label-info">Attention!</span> Your Username will be permanent, please choose wisely.</span>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputUsername">Login ID / Username:</label>
                <div class="controls">
                <input class="span2" type="text" id="inputUsername" value="" placeholder="Enter username">
                <span id="checkUsername" class="help-inline hide">--Available Response--</span>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputPassword1">Password:</label>
                <div class="controls">
                <input class="span2" type="password" id="inputPassword1" value="" placeholder="Enter password">
                <input class="span2" type="password" id="inputPassword2" value="" placeholder="Retype password">
                <span id="checkPassword" class="help-inline hide">--Match Response--</span>
                </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="inputEmail">Email:</label>
                <div class="controls">
                <input class="span3" type="email" id="inputEmail" placeholder="e.g. example@domain.com">
                </div>
          </div>
          
          <legend>Personal Information</legend>
          
          <div class="control-group">
              <label class="control-label" for="inputName">Full Name:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputName" placeholder="as in I/C with uppercase and lowercase letters">
                </div>
          </div>

          <div class="control-group">
          <div class="form-inline">              
              <label class="control-label" for="inputGender">Gender:</label>
                <div class="controls">
                  <label class="radio inline">
                  <input type="radio" name="optionsGender" id="inputGenderMale" value="Male">
                  Male </label>    
                  <label class="radio inline">                           
                  <input type="radio" name="optionsGender" id="inputGenderFemale" value="Female">
                  Female </label>
           </div></div></div>


          <div class="control-group">
              <label class="control-label" for="inputIC">I/C Number:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputIC" placeholder="e.g. 890101-01-1010">
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label" for="inputPhone">Phone Number:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputPhone" placeholder="e.g. 012-3456789">
                </div>
          </div>
         
          <div class="control-group">
              <label class="control-label" for="inputAddress">Address:</label>
                <div class="controls">
                <textarea class="span4" id="inputAddress" placeholder="" rows="3" style="resize:none"></textarea>
                </div>
          </div>
               
          <div class="control-group">
              <label class="control-label" for="inputPostcode">Postcode:</label>
                <div class="controls">
                <input class="span2" type="text" id="inputPostcode" placeholder="e.g. 14300">
                </div>
          </div>  
              
           <div class="control-group">
              <label class="control-label" for="inputState">State:</label>
                <div class="controls">
                <select id="inputState">
                  <option>-- Select State --</option>
                  <option id="stateJohor" value="Johor">Johor</option>
                  <option id="stateKedah" value="Kedah">Kedah</option>
                  <option id="stateKelantan" value="Kelantan">Kelantan</option>
                  <option id="stateKL" value="Kuala Lumpur">Kuala Lumpur</option>
                  <option id="stateMelaka" value="Melaka">Melaka</option>
                  <option id="stateN9" value="Negeri Sembilan">Negeri Sembilan</option>
                  <option id="statePahang" value="Pahang">Pahang</option>
                  <option id="statePenang" value="Penang">Penang</option>
                  <option id="statePerak" value="Perak">Perak</option>
                  <option id="statePerlis" value="Perlis">Perlis</option>
                  <option id="stateSabah" value="Sabah">Sabah</option>
                  <option id="stateSarawak" value="Sarawak">Sarawak</option>
                  <option id="stateSelangor" value="Selangor">Selangor</option>
                  <option id="stateTerengganu" value="Terengganu">Terengganu</option>             
                </select>                
                </div>
               </div>     
          
          <legend>Education Information</legend>              
               
             <div class="control-group">
             <div class="form-inline">              
              <label class="control-label" for="inputUSM">USM Student:</label>
                <div class="controls">
                  <label class="radio inline">
                  <input type="radio" name="optionsUSM" id="inputUSM1" value="Yes">
                  Yes </label>    
                  <label class="radio inline">                           
                  <input type="radio" name="optionsUSM" id="inputUSM2" value="No">
                  No </label>
                 </div>
                                       
              </div>
              </div> 
              
             <div class="control-group">
             <div class="form-inline">              
              <label class="control-label" for="inputEducation">Education:</label>
                <div class="controls">
                  <label class="radio inline">
                  <input type="radio" name="optionsEducation" id="inputEducation1" value="Undergraduate">
                  Undergraduate </label>    
                  <label class="radio inline">                           
                  <input type="radio" name="optionsEducation" id="inputEducation2" value="Postgraduate">
                  Postgraduate </label>
                  <label class="radio inline">                           
                  <input type="radio" name="optionsEducation" id="inputEducation3" value="None">
                  None </label>
                 </div>
                                       
              </div>
              </div> 
              
              <div class="control-group">
              <label class="control-label" for="inputCourse">Course:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputCourse" placeholder="e.g. Mechatronics Engineering">
                </div>
               </div>
              
              <div id="notifyError" class="alert alert-error hide">--Error Response--</div>
          <div id="notifySuccess" class="alert alert-success hide">--Success Response--</div>
                
               <div class="form-actions">
                <input type="hidden" id="antispam" name="antispam" value="">
                <a href="#" id="btnRegister" class="btn btn-primary">Submit</a>
                <a href="index.php" class="btn btn-inverse">Cancel</a>
               </div>
                                         
          </form>         
         
          </div><!-- /.box -->        
          </div><!-- /.span9 -->
          
          <div class="span4">
        
      <?php include 'sponsors.php'; ?>
         
        </div><!-- /.span4 -->
      
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    // when user clicks button Submit to register
    $('#btnRegister').click(function(e){
      e.preventDefault();
      var username = $('#inputUsername').val();
      var password = $('#inputPassword2').val();
      var email = $('#inputEmail').val();
      var name = $('#inputName').val();
      var gender = getRadioVal('gender');
      var icno = $('#inputIC').val();
      var phoneno = $('#inputPhone').val();
      var address = $('#inputAddress').val();
      var postcode = $('#inputPostcode').val();
      var state = $('#inputState :selected').val();
      var usm = getRadioVal('usm');
      var education = getRadioVal('education');
      var course = $('#inputCourse').val();
      var antispam = $('#antispam').val();
      var postData = 'usergroup=jobseeker&username='+urlencode(username)+'&password='+urlencode(password)+'&email='+urlencode(email)+'&name='+urlencode(name)+'&gender='+urlencode(gender)+'&icno='+urlencode(icno)+'&phone='+urlencode(phoneno)+'&address='+urlencode(address)+'&postcode='+urlencode(postcode)+'&state='+urlencode(state)+'&usm='+urlencode(usm)+'&education='+urlencode(education)+'&course='+urlencode(course)+'&antispam='+urlencode(antispam);
      $.ajax({
        type: 'POST',
        url: 'register_process.php',
        data: postData,
        success: function(responseData){
          if (responseData !== 'OK') {
            $('#notifyError').html(responseData).fadeIn().delay(3000).fadeOut();
          } else {
            // if success, redirect user to dashboard
            window.location.replace('dashboard_jobseeker.php');
          }
        }
      });
    });
    function getRadioVal(cat){
      if (cat == 'gender'){
        if ($('#inputGenderMale :checked')) { return 'Male'; }
        else if ($('#inputGenderFemale :checked')) { return 'Female'; }
        else { return ''; }
      }
      if (cat == 'usm'){
        if ($('#inputUSM1 :checked')) { return 'Yes'; }
        else if ($('#inputUSM2 :checked')) { return 'No'; }
        else { return ''; }
      }
      if (cat == 'education'){
        if ($('#inputEducation1 :checked')) { return 'Undergraduate'; }
        else if ($('#inputEducation2 :checked')) { return 'Postgraduate'; }
        else { return 'None'; }
      }
    }
    // check username while user is typing if the username is available or taken
    $('#inputUsername').keyup(check_username);
    function check_username(){
      var username = $('#inputUsername').val();
      if (username.length > 0){
      if (username.length < 4){
        $('#checkUsername').css({'color' : '#d00'}); // color:red
        $('#checkUsername').html('<i class="icon-warning-sign"></i> Username must be 4 characters and above').fadeIn();
      } else {
        $.ajax({
        type: 'POST',
        url: 'check_username.php',
        data: 'username='+ urlencode(username),
        cache: false,
        success: function(response){
          if(response == 'OK'){
            $('#checkUsername').css({'color' : '#3c0'}); // color:green
            $('#checkUsername').html('<i class="icon-ok-sign"></i> '+username+' is available').fadeIn();
          } else {
            $('#checkUsername').css({'color' : '#d00'}); // color:red
            $('#checkUsername').html('<i class="icon-remove-sign"></i> '+username+' has been taken').fadeIn();
          }
        }
        });
      }
      } else {
        $('#checkUsername').hide();
      }
    }
    
    // check password if both are match
    $('#inputPassword2').keyup(check_password);
    function check_password(){
      var password1 = $('#inputPassword1').val();
      var password2 = $('#inputPassword2').val();
      if (password1.length > 0 && password2.length > 0) {
        if (password2 == password1) {
          $('#checkPassword').css({'color' : '#3c0'}); // color:green
          $('#checkPassword').html('<i class="icon-ok"></i>').fadeIn();
        } else {
          $('#checkPassword').css({'color' : '#d00'}); // color:red
          $('#checkPassword').html('<i class="icon-remove"></i>').fadeIn();
        }
      } else {
        $('#checkPassword').hide();
      }
    }
    </script>

  </body>
</html>