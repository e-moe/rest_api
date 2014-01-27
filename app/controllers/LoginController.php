<?php
class LoginController extends Controller
{
    /**
     * Action for url /login/
     * 
     * @param array $params Request params
     * @param string $method Request method
     */
    public function actionIndex($params, $method)
    {
        $app = App::getInstance();
        if ('POST' == $method) {
            $jr = new JsonRequest();
            // read and parse POST data in JSON format
            $request = $jr->parse(file_get_contents('php://input'));
            if (!is_null($request)) { // parsing ok
                if ($token = UserModel::login($request)) {
                    $this->render('login/success', $token);
                    return;
                }
            }
        }
        $app->error(403, 'Auth error');
    }
    
    /**
     * After action event
     * 
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     */
    public function afterAction($action, $params, $method)
    {
        parent::afterAction($action, $params, $method);
        $app = App::getInstance();
        $data = [
            'ip' => $app->getClientIp(),
            'endpoint' => $method . ' ' . $app->getRequestUri(),
            'result' => http_response_code(),
        ];
        LogModel::log($data);
    }

}