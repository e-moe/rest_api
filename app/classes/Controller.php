<?php
abstract class Controller extends AppAware
{
    use JsonControllerTrait;
    
    /**
     * @var View
     */
    protected $view = null;
    
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->view = $app['view'];
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