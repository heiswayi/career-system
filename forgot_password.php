<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

if (isset($_GET['user']) && !empty($_GET['user'])) {
  $group = $_GET['user'];
  if ($group == 'jobseeker') { renderHTML($group); }
  else if ($group = 'employer') { renderHTML($group); }
  else { header('Location: index.php'); }
} else {
  header('Location: index.php');
}

function renderHTML($group) {
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
    <?php html_body_header('Forgot Password'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span8">

<div class="box jobseeker-login">
<h4 class="box-title jobseeker-login-head"><i class="icon-cog semi-opacity"></i> Request New Reset Password</h4>

<form class="form-horizontal" style="margin:50px 50px 50px 0">
  <div class="control-group">
    <div class="controls">
      <div id="notifyError" class="alert alert-error hide">--Error Response--</div>
      <div id="notifySuccess" class="alert alert-success hide">--Success Response--</div>
      To obtain a new reset password, please enter your e-mail address and the new reset password will be emailed to you.
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email:</label>
    <div class="controls">
      <input class="span4" type="email" id="inputEmail" placeholder="e.g. example@domain.com" value="">
      <input type="hidden" id="inputGroup" value="<?php echo $group; ?>">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <input type="hidden" id="antispam" name="antispam" value="">
      <a id="btnSend" href="#" class="btn btn-primary">Send</a>
      <a href="index.php" class="btn btn-inverse">Cancel</a>
    </div>
  </div>
</form>

</div><!-- /.box -->
          
       </div><!-- /.span4 -->
       
        <div class="span4">
        
<?php include 'sponsors.php'; ?>
         
        </div><!-- /.span4 -->
        
      </div><!-- /.row -->

    <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>
    <script>
    $('#btnSend').click(function(e){
      e.preventDefault();
      $('#notifySuccess').fadeOut();
      var email = $('#inputEmail').val();
      var group = $('#inputGroup').val();
      var antispam = $('#antispam').val();
      var postData = 'usergroup='+urlencode(group)+'&email='+urlencode(email)+'&antispam='+urlencode(antispam);
      $.ajax({
        type: 'POST',
        url: 'forgot_password_process.php',
        data: postData,
        success: function(responseData){
          if (responseData !== 'OK') {
            $('#notifyError').html(responseData).fadeIn().delay(3000).fadeOut();
          } else {
            $('#notifySuccess').html('Your new reset password has been successfully sent to your email.').fadeIn();
          }
        }
      });
    });
    </script>

  </body>
</html>

<?php } ?>