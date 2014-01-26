<?php
include_once dirname(__FILE__) . '/configuration.php';
include_once dirname(__FILE__) . '/defines.php';
include_once dirname(__FILE__) . '/autoloader.php';

class App
{
    protected static $instance; // object instance

    protected $publicBasePath = ''; // base url for application

    private function __construct()
    {
        $this->initPublicBaseUrl();
    }

    private function __clone() {}
    private function __wakeup() {}

    /**
     * Get App instance
     * @return App
     */
    public static function getInstance()
    { // returns single class instance.
        if (is_null(self::$instance)) {
            self::$instance = new App;
        }
        return self::$instance;
    }

    /**
     * Run application
     */
    public function run()
    {
        $router = new Router();
        $router->route();
    }

    /**
     * @return array Associative array of all the HTTP headers for the current request
     */
    public function getHeaders()
    {
        return getallheaders();
    }
    
    /**
     * @return string Client IP addr
     */
    public function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    public function getRequestUri()
    {
        return str_replace($this->getPublicBaseUrl(), '', $_SERVER['REQUEST_URI']);
    }

    /**
     * Determine public base url for application
     */
    protected function initPublicBaseUrl()
    {
        $this->publicBasePath = str_replace(
            str_replace(
                INDEX_PATH,
                "",
                $_SERVER['SCRIPT_FILENAME']),
            "",
            $_SERVER['SCRIPT_NAME']);
    }
    
    public function getPublicBaseUrl()
    {
        return $this->publicBasePath;
    }

    /**
     * Render error and show message
     * @param int $code
     * @param string $msg
     * @param bool $die Die or not
     */
    public function error($code, $msg = '', $die = true)
    {
        $file = intval($code) . '.php';
        $file_path = APP_PATH . '/errors/' . $file;
        if (file_exists($file_path) && is_readable($file_path)) {
            include_once $file_path;
        }
        if ($die) {
            die;
        }
    }
}