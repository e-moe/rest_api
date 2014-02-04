<?php
$config = include 'config.php';
define('ROOT_PATH', str_replace('\\', '/', dirname(dirname(__FILE__))));
define('APP_PATH', ROOT_PATH . $config['app_path']);
define('INDEX_PATH', ROOT_PATH . $config['www_path']);
