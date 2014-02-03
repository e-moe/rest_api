<?php
class Router extends DIAble
{
    /**
     * Serve requested url - parse params, run correspond action, controller...
     */
    public function route()
    {
        $request = $this->app['request'];         
        // get controller and action from request uri
        $parts = $this->parseRequest($request);
        $controller = array_shift($parts);
        $action = array_shift($parts);
        $controller = mb_convert_case($controller, MB_CASE_TITLE);
        // all other parts - params for action
        $params = $parts;
        $this->executeAction($controller, $action, $params);
    }
    
    /**
     * Execute requested action
     * 
     * @param string $controllerName Controller name
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     */
    protected function executeAction($controllerName, $action, $params)
    {
        $request = $this->app['request']; 
        $response = $this->app['response'];
        $method = $request->getHttpMethod();
        // Add suffixes in controller and action names
        $controllerClass = ($controllerName ?: 'Index') . 'Controller';
        $actionFunction = 'action' . mb_convert_case($method, MB_CASE_TITLE) . ($action ?: 'Index');
        try {
            $controller = $this->app['controllerFactory']->getController($controllerClass);
            // check action in controller
            if (!method_exists($controller, $actionFunction)) { // action not found, run default action
                array_unshift($params, $action);
                $actionFunction = 'defaultAction';
            }
            if ($controller->beforeAction($actionFunction, $request)) {
                $html = $controller->$actionFunction($request);
                $response->setBody($html);
            }
            $controller->afterAction($actionFunction, $request);
        } catch (InvalidArgumentException $e) {
            // controller not found, 404 HTTP error
            $this->app->error(404);
        }
        $response->send();
    }

    /**
     * Parse request uri to parts
     * 
     * @return array Parts of request uri
     */
    protected function parseRequest(Request $request)
    {
        $parts = explode('/', $request->getUri());
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