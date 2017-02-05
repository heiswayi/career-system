<?php

$app_name = 'Career System';
$app_ver = '1.0';

function printable_footer(){
global $app_name;
global $app_ver;
header("Content-Type: text/html; charset=utf-8");
echo '
<footer>
  <p>&copy; Career Fair 2014, Engineering Campus, USM &bullet; '.$app_name.' '.$app_ver.' was developed by <a href="http://twitter.com/0x0000wayi">Heiswayi Nrird</a> &amp; <a href="http://twitter.com/pirates_killer">Muhd Faiz Abu Bakar</a>. Maintained by Khairul &amp; Hafiza for CF 2014</p>
</footer>
';
}

function html_body_footer(){
global $app_name;
global $app_ver;
header("Content-Type: text/html; charset=utf-8");
echo '
<hr>
<footer>
  <p>&copy; Career Fair 2014, Engineering Campus, USM &bullet; '.$app_name.' '.$app_ver.' was developed by <a href="http://twitter.com/0x0000wayi">Heiswayi Nrird</a> &amp; <a href="http://twitter.com/pirates_killer">Muhd Faiz Abu Bakar</a>. Maintained by <a href="mailto:khairul.info@gmail.com">Khairul</a> &amp; <a href="mailto:subbercharming@gmail.com">Hafiza</a> for CF2014 | <a href="admin">Admin</a></p>
</footer>
';
}

function html_body_footer_js(){
header("Content-Type: text/html; charset=utf-8");
echo '
<script src="' . DIR . 'assets/js/holder/holder.js"></script>
<script src="' . DIR . 'assets/js/jquery.realperson.min.js"></script>
<script src="' . DIR . 'assets/js/custom.js"></script>
';
}

function html_head_css(){
header("Content-Type: text/html; charset=utf-8");
echo '
<link href="' . DIR . 'assets/css/bootstrap.min.css" rel="stylesheet">
<link href="' . DIR . 'assets/css/custom.css" rel="stylesheet">
<link href="' . DIR . 'assets/css/font-awesome.min.css" rel="stylesheet">
<!--[if IE 7]>
<link href="' . DIR . 'assets/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!--<link href="http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">-->
';
}

function html_head_js(){
header("Content-Type: text/html; charset=utf-8");
echo '
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="' . DIR . 'assets/js/jquery.js"></script>
<script src="' . DIR . 'assets/js/bootstrap.min.js"></script>
';
}

function html_head_meta(){
global $app_name;
global $app_ver;
header("Content-Type: text/html; charset=utf-8");
echo '
<meta charset="utf-8">
<title>Career Portal &bullet; Career Fair 2014</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="'.$app_name.' '.$app_ver.' for Career Fair 2014, Engineering Campus, USM">
<meta name="author" content="Heiswayi Nrird">
';
}

function html_body_header($text = 'Undefined'){
global $app_name;
global $app_ver;
header("Content-Type: text/html; charset=utf-8");
echo '
<div class="page-header">
  <div class="container">
    <h1>'.$text.' <small>'.$app_name.' '.$app_ver.' for Career Fair 2014, Engineering Campus, USM</small></h1>
  </div>
</div>
';
}

function html_body_topmenu(){
echo '
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="' . DIR . 'index.php" style="color:#ff6600"><i class="icon-sign-blank" style="color:#aa00cc"></i> Career Portal</a>
      <ul class="nav">
        <li class="active"><a href="' . DIR . 'index.php"><i class="icon-home"></i> Home</a></li>
';

if (isset($_SESSION['user_login']) && isset($_SESSION['user_group']) && $_SESSION['user_group'] == 'jobseeker') {
  echo '<li><a href="dashboard_jobseeker.php"><i class="icon-dashboard"></i> My Dashboard</a></li>';
}
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group']) && $_SESSION['user_group'] == 'employer') {
  echo '<li><a href="dashboard_employer.php"><i class="icon-dashboard"></i> My Dashboard</a></li>';
}
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group']) && $_SESSION['user_group'] == 'admin') {
  echo '<li><a href="dashboard.php"><i class="icon-dashboard"></i> My Dashboard</a></li>';
}

echo '
        <li><a href="http://careerfair.eng.usm.my" target="_blank"><i class="icon-globe"></i> Career Fair 2014</a></li>
        <li><a href="' . DIR . 'help_center.php"><i class="icon-question-sign"></i> Help Center</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-external-link"></i> Quick Links <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class="nav-header">About</li>
            <li><a href="http://careerfair.eng.usm.my/v3/?page_id=742" target="_blank">About Career Fair</a></li>
            <li><a href="http://careerfair.eng.usm.my/v3/?page_id=62" target="_blank">Our Committee</a></li>
            <li><a href="http://careerfair.eng.usm.my/v3/?page_id=856" target="_blank">Event Details</a></li>
            <li class="divider"></li>
            <li class="nav-header">Activities</li>
            <li><a href="http://careerfair.eng.usm.my/v3/?page_id=856" target="_blank">On The Day</a></li>
            <li><a href="http://careerfair.eng.usm.my/v3/?page_id=867" target="_blank">Company Exhibition Booth</a></li>
<!--            <li><a href="" target="_blank">Interview Booth &amp; Schedule</a></li>-->
<!--            <li><a href="" target="_blank">Career Talk Schedule</a></li>-->
<!--            <li><a href="" target="_blank">Hall Arrangement</a></li>-->
          </ul>
        </li>
      </ul>
';

if (isset($_SESSION['user_login']) && ($_SESSION['user_group'] == 'jobseeker')) {
echo '
      <div class="btn-group pull-right">
        <button class="btn"><i class="icon-user"></i> Logged in as: <span class="label label-success">'.$_SESSION['user_login'].'</span></button>
        <button class="btn dropdown-toggle btn btn-success" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="' . DIR . 'dashboard_jobseeker.php"><i class="icon-dashboard"></i> My Dashboard</a></li>
          <li><a href="' . DIR . 'preview_profile.php"><i class="icon-check"></i> Preview Profile</a></li>
          <li><a href="' . DIR . 'edit_profile_jobseeker.php"><i class="icon-edit"></i> Edit Profile</a></li>
          <li class="divider"></li>
          <li><a href="' . DIR . 'logout.php"><i class="icon-signout"></i> Logout</a><li>
        </ul>
      </div>
';
}
if (isset($_SESSION['user_login']) && ($_SESSION['user_group'] == 'employer')) {
echo '
      <div class="btn-group pull-right">
        <button class="btn"><i class="icon-user"></i> Logged in as: <span class="label label-important">'.$_SESSION['user_login'].'</span></button>
        <button class="btn dropdown-toggle btn btn-danger" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="' . DIR . 'dashboard_employer.php"><i class="icon-dashboard"></i> My Dashboard</a></li>
          <li><a href="' . DIR . 'preview_profile.php"><i class="icon-check"></i> Preview Profile</a></li>
          <li><a href="' . DIR . 'edit_profile_employer.php"><i class="icon-edit"></i> Edit Profile</a></li>
          <li class="divider"></li>
          <li><a href="' . DIR . 'logout.php"><i class="icon-signout"></i> Logout</a><li>
        </ul>
      </div>
';
}
if (isset($_SESSION['user_login']) && ($_SESSION['user_group'] == 'admin')) {
echo '
      <div class="btn-group pull-right">
        <button class="btn"><i class="icon-user"></i> Logged in as: <span class="label label-warning">'.$_SESSION['user_login'].'</span></button>
        <button class="btn dropdown-toggle btn btn-warning" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="' . DIR . 'dashboard.php"><i class="icon-dashboard"></i> My Dashboard</a></li>
          <li><a href="' . DIR . 'logout.php"><i class="icon-signout"></i> Logout</a><li>
        </ul>
      </div>
';
}

echo '      
    </div>
  </div>
</div>
';
}

function usermenu_jobseeker($name){
switch ($name) {
  case 'Dashboard': $m1 = 'class="active"'; $m2 = ''; $m3 = ''; $m4 = ''; break;
  case 'Preview Profile': $m1 = ''; $m2 = 'class="active"'; $m3 = ''; $m4 = ''; break;
  case 'Edit Profile': $m1 = ''; $m2 = ''; $m3 = 'class="active"'; $m4 = ''; break;
  case 'Upload Resume': $m1 = ''; $m2 = ''; $m3 = ''; $m4 = 'class="active"'; break;
  default: $m1 = ''; $m2 = ''; $m3 = ''; $m4 = ''; break;
}
echo '
<div class="box">
<h4 class="box-title"><i class="icon-cog muted"></i> Jobseeker Menu</h4>
<ul class="nav nav-list">
  <li '.$m1.'><a href="' . DIR . 'dashboard_jobseeker.php"><i class="icon-dashboard"></i> My Dashboard</a></li>
  <li '.$m2.'><a href="' . DIR . 'preview_profile.php"><i class="icon-check"></i> Preview Profile</a></li>
  <li '.$m3.'><a href="' . DIR . 'edit_profile_jobseeker.php"><i class="icon-edit"></i> Edit Profile</a></li>
  <li class="divider"></li>
  <li '.$m4.'><a href="' . DIR . 'upload_resume.php"><i class="icon-upload"></i> Upload Resume</a></li>
  <li class="divider"></li>
  <li><a href="' . DIR . 'change_password.php"><i class="icon-key"></i> Change Password</a><li>
  <li><a href="' . DIR . 'logout.php"><i class="icon-signout"></i> Logout</a><li>
  <li class="divider"></li>
  <li><a href="' . DIR . 'help_center.php"><i class="icon-question-sign"></i> Help Center</a></li>
</ul>
</div>
';
}

function usermenu_employer($name){
switch ($name) {
  case 'Dashboard': $m1 = 'class="active"'; $m2 = ''; $m3 = ''; $m4 = ''; break;
  case 'Preview Profile': $m1 = ''; $m2 = 'class="active"'; $m3 = ''; $m4 = ''; break;
  case 'Edit Profile': $m1 = ''; $m2 = ''; $m3 = 'class="active"'; $m4 = ''; break;
  case 'Post Job': $m1 = ''; $m2 = ''; $m3 = ''; $m4 = 'class="active"'; break;
  default: $m1 = ''; $m2 = ''; $m3 = ''; $m4 = ''; break;
}
echo '
<div class="box">
<h4 class="box-title"><i class="icon-cog muted"></i> Employer Menu</h4>
<ul class="nav nav-list">
  <li '.$m1.'><a href="' . DIR . 'dashboard_employer.php"><i class="icon-dashboard"></i> My Dashboard</a></li>
  <li '.$m2.'><a href="' . DIR . 'preview_profile.php"><i class="icon-check"></i> Preview Profile</a></li>
  <li '.$m3.'><a href="' . DIR . 'edit_profile_employer.php"><i class="icon-edit"></i> Edit Profile</a></li>
  <li class="divider"></li>
  <li '.$m4.'><a href="' . DIR . 'post_job_vacancy.php"><i class="icon-pencil"></i> Post a new application</a></li>
  <li class="divider"></li>
  <li><a href="' . DIR . 'change_password.php"><i class="icon-key"></i> Change Password</a><li>
  <li><a href="' . DIR . 'logout.php"><i class="icon-signout"></i> Logout</a><li>
  <li class="divider"></li>
  <li><a href="' . DIR . 'help_center.php"><i class="icon-question-sign"></i> Help Center</a></li>
</ul>
</div>
';
}

?>