<?php
class Router
{
    protected $app;
    protected $request;
    protected $response;
    protected $controllerFactory;
    protected $routs;

    public function __construct(App $app, Request $request, Response $response, ControllerFactory $controllerFactory, array $routs = [])
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
        $this->controllerFactory = $controllerFactory;
        $this->routs = $routs;
    }

    /**
     * Serve requested url - run correspond action, return response data
     */
    public function route()
    {
        try {
            $out = $this->execute();
            $this->response->setBody($out);
        } catch (NotFoundException $ex) {
            $this->response->setCode($ex->getCode());
        }
        $this->response->send();
    }
    
    /**
     * Execute controller->action and return content
     * 
     * @return string Content
     * @throws NotFoundException
     */
    protected function execute()
    {
        $matchedRoute = $this->matchRoute();
        if (!$matchedRoute) {
            throw new NotFoundException($this->request->getUri());
        }
        $controllerClass = key($matchedRoute);
        $actionName = reset($matchedRoute);
        $controller = $this->controllerFactory->getController($controllerClass);
        $action = $this->prepareActionName($controller, $actionName);
        $out = '';
        if ($controller->beforeAction($action, $this->request)) {
            $params = $this->getParamsForAction();
            $out = call_user_method_array($action, $controller, $params);
        }
        $controller->afterAction($action, $this->request);
        return $out;
    }

    /**
     * Merge reques and all parameters from request
     * 
     * @return array Parameters for action call
     */
    protected function getParamsForAction()
    {
        return array_merge([$this->request], $this->request->getParams());
    }


    /**
     * Generate regexp for routing matching
     * 
     * @param string $path
     * @return string
     */
    protected function getPathRegExp($path)
    {
        return sprintf(
            '|^%s$|u',
            preg_replace('/\{[^\/]+?\}/u', '([^\/]+?)', $path)
        );
    }
    
    
    /**
     * Check action existance and add method name
     * 
     * @param Controller $controller
     * @param string $actionName
     * @return string
     * @throws NotFoundException
     */
    protected function prepareActionName(Controller $controller, $actionName)
    {
        $method = mb_convert_case($this->request->getHttpMethod(), MB_CASE_TITLE);
        $action = str_replace('{method}', $method, $actionName);
        if (!method_exists($controller, $action)) {
            throw new NotFoundException($this->request->getUri());
        }
        return $action;
    }

    /**
     * Match request uri for possible routes
     * 
     * @return array|false Settings for matched route (controller name and acton)
     */
    protected function matchRoute()
    {
        $uri = $this->request->getUri();
        foreach ($this->routs as $path => $settings) {
            $regExp = $this->getPathRegExp($path);
            if (preg_match($regExp, $uri, $matches)) {
                $params = array_slice($matches, 1);
                $this->request->setParams($params);
                return $settings;
            }
        }
        return false;
    }

}