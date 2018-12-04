<?php
// by default display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

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
list($function, $id) = $utils->ParseCommandFromURI($uri);

// set empty array
$obj = [];

// API multiplexer
if ($function == "modules") {
    if ($method == "GET") {
        $obj = $manage->getModules();
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
} else if ($function == "module") {
    if ($method == "POST") {
        $obj = $manage->installModule($id);
    } else if ($method == "DELETE") {
        $obj = $manage->uninstallModule($id);
    } else if ($method == "GET") {
        $obj = $manage->getModule($id);
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
} else if ($function == "install") {
    if ($method == "POST") {
        $obj = $manage->install();
    } else if ($method == "DELETE") {
        $obj = $manage->uninstall();
    }
}

// return JSON
$JSON = $utils->JSON($obj);
header('Content-type: application/json');
print($JSON);

?>