<?php
// by default display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// requires utils
require_once('../../include/utils.php');

// load configs
$config = $utils->loadConfig("../../config.json");

// other requires
require_once('../../include/db.php');

// creating database connection
$db = new Inc\Db($config);

// require APIs
require_once("../api/manage.php");
$manage = new API\Manage($db);

echo "Attempting to install the CMS:";
echo "<pre>";
print_r($manage->install());
echo "</pre>";
echo "If installation was successfull, please remove install/index.php file.";
echo "You can log in to the administration part of your CMS at: ";
echo "<a href='/admin'>http://" . $_SERVER["HTTP_HOST"] . "/admin</a> with credentials admin/pass.";
echo "Change them ASAP.";
?>