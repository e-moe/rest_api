<?php
class View
{
    /**
     * Render specified view with given data
     *
     * @param string $view View name
     * @param mixed $data Data to render in view
     * @return bool
     */
    static public function render($view, $data = null, $type = '')
    {
        $file_path = APP_PATH . '/views/' . $view . $type . '.phtml';
        if (file_exists($file_path) && is_readable($file_path)) {
            if (is_array($data)) {
                extract($data, EXTR_PREFIX_SAME, 'data');
            } else {
                $data = $data;
            }
            include $file_path;
            return true;
        }
        $app = App::getInstance();
        $app->error(500, "View '$view' not found");
        return false;
    }
    
    /**
     * Create absolute URL from relative
     *
     * @param string $url Relative url
     * @return string Absolute url
     */
    static public function url($url)
    {
        $app = App::getInstance();
        return "http://" . $_SERVER['SERVER_NAME'] . $app->getPublicBaseUrl() . $url;
    }
    
}
