<?php
date_default_timezone_set('America/Lima');
require __DIR__ . "/../vendor/autoload.php";

$host= $_SERVER["HTTP_HOST"];
$url= $_SERVER["REQUEST_URI"];
$ruta="http://" . $host . $url;
//echo "http://" . $host . $url;
$parts = explode('/', $url);
$link=end($parts);
use Core\Foundation\Application;
$rootdir= dirname(__DIR__);
$_ENV['DIR_ROOT']=$rootdir;
$dotenv = \Dotenv\Dotenv::createImmutable($rootdir);
$dotenv->load();
$config = require $rootdir . "/config/app.php";
$app = Application::getInstance($rootdir, $config,"");
$app->empresa='yaquamarket';
require $rootdir . "/routes/web.php";
$app->run();