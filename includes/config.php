<?php

// Setup database
if (!defined('DB_SERVER')) {	define('DB_SERVER', 'localhost'); }
if (!defined('DB_USERNAME')) {	define('DB_USERNAME', ''); }
if (!defined('DB_PASSWORD')) {	define('DB_PASSWORD', ''); }
if (!defined('DB_NAME')) {	define('DB_NAME', 'cfair_web'); }

if (!defined('PW_SALT')) {	define('PW_SALT', 'k4mpu5U5M'); } // User password salt - md5(salt.password)

?>