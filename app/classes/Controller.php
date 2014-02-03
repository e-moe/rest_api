<?php
abstract class Controller extends DIAble
{
    /**
     * @var View
     */
    protected $view = null;
    public function __construct(\App $app)
    {
        parent::__construct($app);
        $this->view = $app['view'];
    }

        /**
     * 
     * @param mixed $data
     * @param int $responseCode
     * @return string
     */
    public function json($data = null, $responseCode = 200)
    {
        /**
         * @var Response
         */
        $response = $this->app['response'];
        $response->setHeader('Content-Type', 'application/json');
        $response->setCode($responseCode);
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * 
     * @param string $viewName
     * @param mixed $data
     * @return string
     */
    public function renderView($viewName, $data = null)
    {
        $view = $this->app['view'];
        $view->setTempleate($viewName);
        return $view->render($data);
    }

    /**
     * Default action
     * 
     * @param Request $request
     */
    public function defaultAction(Request $request)
    {
         /**
         * @var Response
         */
        $response = $this->app['response'];
        $response->setCode(404);
        return $this->renderView('error/404', $request->getUri() . ' - can not be found.');
    }
    
    /**
     * Before action event
     * 
     * @param string $action Action name
     * @param Request $request
     * @return bool 
     */
    public function beforeAction($action, Request $request)
    {
        return true;
    }
    
    /**
     * After action event
     * 
     * @param string $action Action name
     * @param Request $request
     */
    public function afterAction($action, Request $request)
    {
        
    }
}