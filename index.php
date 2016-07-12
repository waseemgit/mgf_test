<?php
ini_set('display_errors', TRUE);
error_reporting(E_ALL);
 /*** define the site path constant ***/
$site_path = realpath(dirname(__FILE__));
define ('__SITE_PATH', $site_path);
include 'includes/init.php'; 
?>