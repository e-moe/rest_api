<?php
class loginController extends Controller
{
    /**
     * Action for url /login/
     * @param array $params Request params
     */
    public function actionIndex($params)
    {
        $app = App::getInstance();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jr = new JsonRequest();
            // read and parse POST data in JSON format
            $request = $jr->parse(file_get_contents('php://input'));
            if (!is_null($request)) { // parsing ok
                if ($token = UserModel::login($request)) {
                    $this->render('login/success.json.php', $token);
                    return;
                }
            }
        }
        $app->error(403, 'Wrong email/password');
    }

}