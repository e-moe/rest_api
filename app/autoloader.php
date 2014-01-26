<?php
// register class autoloader
spl_autoload_register(function ($class) {
    // array of directories where can be located class files
    $include_dirs = array('models', 'controllers', 'classes');
    // search class file
    foreach ($include_dirs as $dir) {
        $file_path = APP_PATH . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file_path) && is_readable($file_path)) { // found
            include_once $file_path;
            break;
        }
    }
});