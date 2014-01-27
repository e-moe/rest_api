<?php
class Router
{
    /**
     * Serve requested url - parse params, run correspond action, controller...
     */
    public function route()
    {
        // get controller and action from request uri
        $parts = $this->parseRequest();
        list($controller, $action) = $parts;
        $controller = mb_convert_case($controller, MB_CASE_TITLE);
        // all other parts - params for action
        $params = array_slice($parts, 2);
        $method = static::getHttpMethod();
        $this->executeAction($controller, $action, $params, $method);
    }
    
    /**
     * Execute requested action
     * 
     * @param string $controller Controller name
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     */
    protected function executeAction($controller, $action, $params, $method)
    {
        // Add suffixes in controller and action names
        $controllerClass = ($controller ?: 'Index') . 'Controller';
        $actionFunction = 'action' . ($action ?: 'Index');
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass;
            // check action in controller
            if (!method_exists($controller, $actionFunction)) { // action not found, run default action
                array_unshift($params, $action);
                $actionFunction = 'defaultAction';
            }
            if ($controller->beforeAction($actionFunction, $params, $method)) {
                $controller->$actionFunction($params, $method);
            }
            $controller->afterAction($actionFunction, $params, $method);
        } else {
            // controller not found, 404 HTTP error
            App::getInstance()->error(404);
        }
    }

    /**
     * Parse request uri to parts
     * 
     * @return array Parts of request uri
     */
    protected function parseRequest()
    {
        $app = App::getInstance();
        $request = str_replace($app->getPublicBaseUrl(), '', $_SERVER['REQUEST_URI']);
        $parts = explode('/', $request);
        array_shift($parts);
        return $parts;
    }
    
    /**
     * Get HTTP request method (e.g. GET, POST, ... )
     * 
     * @return string
     */
    public static function getHttpMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

}