<?php
class usersController extends Controller
{
    /**
     * Action for url /users/
     * @param array $params Request params
     */
    public function actionIndex($params)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // load and render list of all addresses
            $users = UserModel::findAll();
            $this->render('user/all.json.php', $users);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jr = new JsonRequest();
            // read and parse POST data in JSON format
            $data = $jr->parse(file_get_contents('php://input'));
            if (!is_null($data)) { // parsing ok
                // creating new address
                $data = $model->createNew($data);
                if (!is_null($data)) { // success
                    $this->render('user/create.json.php', $data);
                } else { // errors
                    $this->error($model->getErrors());
                }
            } else { // error during parsing input JSON data
                $this->error($jr->getErrors());
            }
        }
    }

    /**
     * Action for url /addresses/{addressId}/
     * @param array $params Request params
     */
    public function defaultAction($params)
    {
        $id = intval($params[0]); // get ID from request params
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // load address with specified id
            $user = UserModel::findByPk($id);
            if (!is_null($user)) { // success
                $this->render('user/user.json.php', $user);
            } else { // not found
                $this->error(array("Address with id '$id' does not exists"));
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $jr = new JsonRequest();
            // read and parse PUT data in JSON format
            $data = $jr->parse(file_get_contents('php://input'));
            if (!is_null($data)) { // parsing ok
                // is address exists?
                if ($model->findByPk($id)) { // address exists, updating it
                    $new = $model->updateByPk($id, $data);
                    if (!is_null($new)) { // success
                        $this->render('user/update.json.php', $id);
                    } else {
                        $this->error($model->getErrors());
                    }
                } else { // address not exists, creating new
                    $new = $model->createNew($data);
                    if (!is_null($new)) { // success
                        $this->render('user/create.json.php', $new);
                    } else {
                        $this->error($model->getErrors());
                    }
                }
            } else { // error during parsing input JSON data
                $this->error($jr->getErrors());
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            // deleting address
            $deleted = $model->deleteByPk($id);
            if ($deleted) { // success
                $this->render('user/delete.json.php', $id);
            } else {
                $this->error($model->getErrors());
            }
        }
    }
    
    public function beforeAction($action, $params)
    {
        $tokenHeader = 'HTTP_X_TOKEN';
        $app = App::getInstance();
        $headers = $app->getHeaders();
        if (isset($headers[$tokenHeader])) {
            $token = $headers[$tokenHeader];
            $user = UserModel::find('`session_token` = ?', [$token]);
            if ($user) {
                if (time() < $user->session_expire) {
                    $user->session_expire = UserModel::renewExpireTime();
                    if ($user->save()) {
                        return;
                    }
                }
            }
        }
        $app->error(403, 'Auth required');
    }

}