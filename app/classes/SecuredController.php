<?php
class SecuredController extends Controller
{
    const ACCESS_TOKEN_HEADER_NAME = 'HTTP_X_TOKEN';

    /**
     * Before action event
     * 
     * @param string $action Action name
     * @param array $params Request params
     * @param string $method Request method
     */
    public function beforeAction($action, $params, $method)
    {
        return $this->checkAccessToken() && parent::beforeAction($action, $params, $method);
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
        $app = App::getInstance();
        $data = [
            'ip' => $app->getClientIp(),
            'endpoint' => $method . ' ' . $app->getRequestUri(),
            'token' => $this->getAccessToken(),
            'result' => http_response_code(),
        ];
        LogModel::log($data);
    }
    
    /**
     * Check access rights using token value passed via headers
     */
    protected function checkAccessToken()
    {
        $token = $this->getAccessToken();
        if (null !== $token) {
            $user = UserModel::find('`session_token` = ?', [$token]);
            if ($user) {
                if (time() < $user->session_expire) {
                    $user->session_expire = UserModel::renewExpireTime();
                    if ($user->save()) {
                        return true;
                    }
                }
            }
        }
        App::getInstance()->error(401, 'Access denied', false);
        return false;
    }
    
    protected function getAccessToken()
    {
        $app = App::getInstance();
        $headers = $app->getHeaders();
        if (isset($headers[self::ACCESS_TOKEN_HEADER_NAME])) {
            return $headers[self::ACCESS_TOKEN_HEADER_NAME];
        }
        return null;
    }

}