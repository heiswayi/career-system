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
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Edit Profile</h4>
          <form class="form-horizontal edit-profile-jobseeker" style="margin:20px">
                   
          <legend>Personal Information</legend>
          
          <div class="control-group">
              <label class="control-label" for="inputName">Full Name:</label>
                <div class="controls">
                <input class="span4" type="text" id="inputName" placeholder="as in I/C">
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
               
           <div class="control-group">
              <label class="control-label" for="inputEmail">Email:</label>
                <div class="controls">
                <input class="span4" type="email" id="inputEmail" placeholder="e.g. example@domain.com" required="required">
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
                <input type="hidden" id="inputUsername" value="">
                <a href="#" id="btnUpdate" class="btn btn-primary">Update</a>
                <a href="dashboard_jobseeker.php" class="btn btn-inverse">Cancel</a>
               </div>
                                         
          </form>         
         
          </div><!-- /.box -->      
                 
          </div><!-- /.span9 -->
        
           
        <div class="span3">
        
      <?php usermenu_jobseeker('Edit Profile'); ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    // execute when user clicks on button Update
    $('#btnUpdate').click(function(e){
      e.preventDefault();
      $('#notifySuccess').fadeOut();
      var username = $('#inputUsername').val();
      var email = $('#inputEmail').val();
      var name = $('#inputName').val();
      
      if ($('#inputGenderMale').is(":checked")){var gender = 'Male';}
      if ($('#inputGenderFemale').is(":checked")){var gender = 'Female';}

      var icno = $('#inputIC').val();
      var phoneno = $('#inputPhone').val();
      var address = $('#inputAddress').val();
      var postcode = $('#inputPostcode').val();
      var state = $('#inputState :selected').val();
      
      if ($('#inputUSM1').is(":checked")){var usm = 'Yes';}
      if ($('#inputUSM2').is(":checked")){var usm = 'No';}
      
      if ($('#inputEducation1').is(":checked")){var education = 'Undergraduate';}
      if ($('#inputEducation2').is(":checked")){var education = 'Postgraduate';}
      if ($('#inputEducation3').is(":checked")){var education = 'None';}
      
      var course = $('#inputCourse').val();
      var postData = 'usergroup=jobseeker&username='+urlencode(username)+'&email='+urlencode(email)+'&name='+urlencode(name)+'&gender='+urlencode(gender)+'&icno='+urlencode(icno)+'&phone='+urlencode(phoneno)+'&address='+urlencode(address)+'&postcode='+urlencode(postcode)+'&state='+urlencode(state)+'&usm='+urlencode(usm)+'&education='+urlencode(education)+'&course='+urlencode(course);
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
      var postData = 'usergroup=jobseeker&username='+urlencode(username);
      $.ajax({
        type: 'POST',
        url: 'edit_profile_getdata.php',
        data: postData,
        success: function(responseData){
          if (responseData == 'KO') {
            $('#notifyError').html('Informations are not available!').fadeIn().delay(3000).fadeOut();
          } else {
            // get data: username, email, name, ic, phone, address, postcode, state, course, gender, is_usm, is_undergrad
            var initData = responseData.split('[]');
            $('#inputUsername').val(initData[0]);
            $('#inputEmail').val(initData[1]);
            $('#inputName').val(initData[2]);
            $('#inputIC').val(initData[3]);
            $('#inputPhone').val(initData[4]);
            $('#inputAddress').val(initData[5]);
            $('#inputPostcode').val(initData[6]);
            $('#inputState').val(initData[7]);
            $('#inputCourse').val(initData[8]);
            if (initData[9] == 'Male') { $('#inputGenderMale').prop('checked', true);$('#inputGenderFemale').prop('checked', false); }
            else { $('#inputGenderFemale').prop('checked', true);$('#inputGenderMale').prop('checked', false); }
            if (initData[10] == '1') { $('#inputUSM1').prop('checked', true);$('#inputUSM2').prop('checked', false); }
            else { $('#inputUSM2').prop('checked', true);$('#inputUSM1').prop('checked', false); }
            if (initData[11] == '1') { $('#inputEducation1').prop('checked', true);$('#inputEducation2').prop('checked', false);$('#inputEducation3').prop('checked', false); }
            else if (initData[11] == '0') { $('#inputEducation2').prop('checked', true);$('#inputEducation1').prop('checked', false);$('#inputEducation3').prop('checked', false); }
            else { $('#inputEducation2').prop('checked', false);$('#inputEducation1').prop('checked', false);$('#inputEducation3').prop('checked', true); }
          }
        }
      });
    }
    </script>

  </body>
</html>