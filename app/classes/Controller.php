<?php
abstract class Controller
{
    /**
     * Render specified view with given data
     * 
     * @param string $view View name
     * @param mixed $data Data to render in view
     * @param string $type Response view type
     * @return bool
     */
    public function render($view, $data = null, $type = '.json')
    {
        return View::render($view, $data, $type);
    }
    
    /**
     * Render errors
     * 
     * @param array $errors List of errors
     */
    public function error($errors)
    {
        $this->render('errors', $errors);
    }

    /**
     * Default action
     * 
     * @param array $params Request params
     * @param string $method Request method
     */
    public function defaultAction($params, $method)
    {
        $app = App::getInstance();
        $app->error(404);
    }
    
    /**
     * Before action event
     * 
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     * @return bool 
     */
    public function beforeAction($action, $params, $method)
    {
        return true;
    }
    
    /**
     * After action event
     * 
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     */
    public function afterAction($action, $params, $method)
    {
        
    }
}