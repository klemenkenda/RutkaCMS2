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
require_once("manage.php");
require_once("admin.php");

$manage = new API\Manage($db);
$api = new API\Admin($db);

// parse URL
$uri = $utils->ParseURI($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];
$function = $utils->ParseCommandFromURI($uri);

// set empty array
$obj = [];

// multiplexer
if ($function == "modules") {
    if ($method == "GET") {
        $obj = $manage->getModules();
    }
}

// return JSON
$JSON = $utils->JSON($obj);
header('Content-type: application/json');
print($JSON);

?>