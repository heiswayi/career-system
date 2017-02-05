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
    <?php html_body_header('Help Center'); ?>

    <div class="container">

      <div class="row">
      
<div class="span12">

<iframe src="https://docs.google.com/forms/d/1z238yG_f7l86S3NKJHkIrwekZcZ08GypFuLiDIPk1xM/viewform?embedded=true" width="100%" height="750" frameborder="0" marginheight="0" marginwidth="0" style="margin:0 auto;border:1px solid #ccc;">Loading...</iframe>

</div>
        
      </div><!-- /.row -->

    <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>
