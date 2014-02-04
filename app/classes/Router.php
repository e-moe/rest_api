<?php
class Router
{
    protected $app;
    protected $request;
    protected $response;
    protected $controllerFactory;


    public function __construct(App $app, Request $request, Response $response, ControllerFactory $controllerFactory)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * Serve requested url - parse params, run correspond action, controller...
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

    protected function getParamsForAction()
    {
        return array_merge([$this->request], $this->request->getParams());
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

    protected $routs = [
        '/'             => ['IndexController' => 'indexAction'],
        '/users/'       => ['UsersController' => 'list{method}Action'],
        '/users/{id}/'  => ['UsersController' => 'user{method}Action'],
    ];
    
    protected function getPathRegExp($path)
    {
        return sprintf(
            '|^%s$|u',
            preg_replace('/\{[^\/]+?\}/u', '([^\/]+?)', $path)
        );
    }
    
    protected function prepareActionName(Controller $controller, $actionName)
    {
        $method = mb_convert_case($this->request->getHttpMethod(), MB_CASE_TITLE);
        $action = str_replace('{method}', $method, $actionName);
        if (!method_exists($controller, $action)) {
            throw new NotFoundException($this->request->getUri());
        }
        return $action;
    }

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