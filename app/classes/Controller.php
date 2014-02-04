<?php
abstract class Controller extends AppAware
{
    use JsonControllerTrait;
    
    /**
     * @var View
     */
    protected $view = null;
    
    /**
     * @var Response
     */
    protected $response = null;


    public function __construct(App $app, Response $response, View $view)
    {
        parent::__construct($app);
        $this->response = $response;
        $this->view = $view;
    }

    /**
     * @return Response
     */
    protected function getResponse()
    {
        return $this->response;
    }

    /**
     * @return View
     */
    protected function getView()
    {
        return $this->view;
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