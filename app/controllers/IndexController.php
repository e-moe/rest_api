<?php
class IndexController extends Controller
{
    /**
     * Action for url /
     * 
     * @param Request $request
     */
    public function actionGetIndex(Request $request)
    {
        return $this->renderView('index');
    }
}