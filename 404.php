<?php
if (!defined('DIR')) {	define('DIR', './'); }
require_once DIR . 'includes/static_structure.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <?php html_head_js(); ?>
  </head>

  <body>

    <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">

          <a class="brand" href="index.php" style="color:#ff6600"><i class="icon-sign-blank" style="color:#aa00cc"></i> Career Portal</a>
            <ul class="nav">
              <li class="active"><a href="index.php"><i class="icon-home"></i> Home</a></li>
            </ul>
        </div>
      </div>
    </div>
    
    <?php html_body_header('404'); ?>

    <div class="container">

      <div class="row">
      
      <div class="span2"></div>
      
      <div class="span8">
<div class="hero-unit">
  <h1>Error 404</h1>
  <p>Opss, something must went wrong!</p>
  <p>
    <a href="index.php" class="btn btn-primary btn-large">
      Back to Homepage
    </a>
  </p>
</div>
      </div>
      
      <div class="span2"></div>        
        
      </div><!-- /.row -->

    <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>
