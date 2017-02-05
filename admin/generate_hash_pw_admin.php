<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', '../'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';
require_once DIR . 'includes/functions.php';

$plain_text_password = "rarepunar";

// amik hash code ni..pergi ke myphpadmin, cari bahagian admin punya column.. replace code hash yg sedia ada ke kpd yg baru generate ni..
echo md5(PW_SALT . $plain_text_password);

  
 
?>