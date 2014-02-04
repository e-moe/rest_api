<?php
class IndexController extends Controller
{
    /**
     * Action for url /
     * 
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        return $this->renderView('index');
    }
}