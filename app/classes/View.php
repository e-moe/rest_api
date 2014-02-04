<?php
class View extends AppAware
{
    protected $templateName = '';
    protected $data = null;
    
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setTempleate($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Render view with given data
     *
     * @param mixed $data Data to render in view
     * @return string
     */
    public function render($data = null)
    {
        $this->setData($data);
        $file_path = APP_PATH . '/views/' . $this->templateName . '.phtml';
        if (file_exists($file_path) && is_readable($file_path)) {
            if (is_array($data)) {
                extract($data, EXTR_PREFIX_SAME, 'data');
            }
            ob_start();
            include $file_path;
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        }
        $this->app->error(500, "View '$view' not found");
    }
    
    /**
     * Create absolute URL from relative
     *
     * @param string $url Relative url
     * @return string Absolute url
     */
    public function url($url)
    {
        $app = $this->app;
        return "http://" . $_SERVER['SERVER_NAME'] . $app['publicBaseUrl'] . $url;
    }
    
}
