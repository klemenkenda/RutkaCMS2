<?php
// by default display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// requires utils
require_once('include/utils.php');
// load configs
$config = $utils->loadConfig();

// other requires
require_once('include/db.php');
require_once('./vendor/autoload.php');

// creating database connection
$db = new Inc\Db($config);

// creating twig
$loader = new Twig_Loader_Filesystem('./template/');
$twig = new Twig_Environment($loader);

echo $twig->render('index.html', array(
    'title' => 'Yo, maaaan!'
));

?>