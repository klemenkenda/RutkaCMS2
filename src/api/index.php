<?php
// by default display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

// requires utils
require_once('../include/utils.php');

// load configs
$config = $utils->loadConfig("../config.json");

// other requires
require_once('../include/db.php');

// creating database connection
$db = new Inc\Db($config);

// require APIs
require_once("manage.php");
require_once("admin.php");
require_once("users.php");

$manage = new API\Manage($db, $utils);
$users = new API\Users($db, $utils);
$api = new API\Admin($db, $utils);

// parse URL
$uri = $utils->ParseURI($_SERVER["REQUEST_URI"]);
$method = $_SERVER["REQUEST_METHOD"];
list($function, $id) = $utils->ParseCommandFromURI($uri);

// set empty array
$obj = [];

// API multiplexer
// all modules
if ($function == "modules") {
    if ($method == "GET") {
        $obj = $manage->getModules();
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
}
// one module
else if ($function == "module") {
    if ($method == "POST") {
        $obj = $manage->installModule($id);
    } else if ($method == "DELETE") {
        $obj = $manage->uninstallModule($id);
    } else if ($method == "GET") {
        $obj = $manage->getModule($id);
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
}
// install
else if ($function == "install") {
    if ($method == "POST") {
        $obj = $manage->install();
    } else if ($method == "DELETE") {
        $obj = $manage->uninstall();
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
}
// authenticate with token
else if ($function == "auth") {
    if ($method == "GET") {
        $token = $utils->extractRequestParameter("token");
        $obj = $users->isAuth($token);
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
}
// login with username and password
else if ($function == "login") {
    if ($method == "GET") {
        $user = $utils->extractRequestParameter("user");
        $password = $utils->extractRequestParameter("password");
        $obj = $users->login($user, $password);
    } else {
        $obj = [ "message" => "Not implemented." ];
    }
}
// get data from table
else if ($function == "data") {
    if ($method == "GET") {
        $table = $utils->extractRequestParameter("table");
        $obj = $api->getData($table);
    } else {
        $obj = [ "message" => "Not implemented." ];
    };
}

// otherwise the function is not implemented
else {
    $obj = [ "message" => "Not implemented." ];
}

// return JSON
$JSON = $utils->JSON($obj);
header('Content-type: application/json');
// header("Access-Control-Allow-Origin: *");
print($JSON);

?>