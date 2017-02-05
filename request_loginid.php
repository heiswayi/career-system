<?php

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

if (isset($_POST['submit'])) {
  $email = $db->sanitize($_POST['email']);
  $person_name = $db->sanitize($_POST['your_name']);
  $comp_name = $db->sanitize($_POST['comp_name']);
  $antispam = $db->sanitize($_POST['antispam']);
  
  if ($antispam !== '') {
    die();
  } else {
    $db->query("INSERT INTO request_loginid (email, person_name, comp_name) VALUES ('$email', '$person_name', '$comp_name')");
    $success = 'Your request has been recorded.';
    $error = '';
  }
} else {
  $success = '';
  $error = '';
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
    <?php html_body_header('Request Login ID'); ?>

    <div class="container">

      <div class="row">
      
        <div class="span8">

<div class="box jobseeker-login">
<h4 class="box-title jobseeker-login-head"><i class="icon-cog semi-opacity"></i> Request Login ID</h4>

<?php
if ($success !== '') {
  echo '<div class="alert alert-success">'.$success.'</div>';
}
if ($error !== '') {
  echo '<div class="alert alert-error">'.$error.'</div>';
}
?>

<form class="form-horizontal" style="margin:50px 50px 50px 0" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div class="control-group">
    <div class="controls">
      <div class="alert" style="margin:0"><strong>Only participating companies can register with us.</strong> After you registered with us, you should be received the login information via email from us. If you don't, you may apply it manually here anyway. For any technical problem, you may contact <a href="mailto:mfaiz.pirates@gmail.com" class="tip-top" title="Email: mfaiz.pirates@gmail.com">Muhd Faiz Abu Bakar</a> (Administrator).</div>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="your_name">Your name:</label>
    <div class="controls">
      <input class="span4" type="text" id="your_name" name="your_name" value="" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="email">Email:</label>
    <div class="controls">
      <input class="span4" type="email" id="email" name="email" value="" required>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="comp_name">Company name:</label>
    <div class="controls">
      <input class="span4" type="text" id="comp_name" name="comp_name" value="" required>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <input type="hidden" id="antispam" name="antispam" value="">
      <button type="submit" id="btnSend" name="submit" class="btn btn-primary">Submit</button>
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

  </body>
</html>
