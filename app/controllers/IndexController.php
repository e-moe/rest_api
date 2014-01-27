<?php
class IndexController extends Controller
{
    /**
     * Action for url /
     * 
     * @param array $params Request params
     * @param string $method Request method
     */
    public function actionIndex($params, $method)
    {
        $this->render('index', null, '.html');
    }
}