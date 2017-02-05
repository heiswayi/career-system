<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

// destroy current session
session_destroy();

// delete all cookies
setcookie('username', '', time() - 30*24*60*60);
setcookie('password', '', time() - 30*24*60*60);
setcookie('group', '', time() - 30*24*60*60);

header("location: index.php");

?>