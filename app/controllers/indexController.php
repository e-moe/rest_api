<?php
class indexController extends Controller
{
    /**
     * Action for url /
     * @param array $params Request params
     */
    public function actionIndex($params)
    {
        $this->render('index.html.php');
    }
}