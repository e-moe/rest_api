<?php
class View extends AppAware
{
    /**
     * @var string View template name
     */
    protected $templateName = '';
    
    /**
     * @var mixed View data
     */
    protected $data = null;
    
    /**
     * Get view data
     * 
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set view data
     * 
     * @param mixed $data
     * @return \View
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set view template
     * 
     * @param string $templateName
     */
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
            $this->setData($data);
            ob_start();
            include $file_path;
            return ob_get_clean();
        }
        throw new Exception("View '$this->templateName' not found", 500);
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
