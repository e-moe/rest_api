<?php
class UsersController extends SecuredController
{
    /**
     * Action for url /users/
     * @param array $params Request params
     * @param string $method Request method
     */
    public function actionIndex($params, $method)
    {
        if ('GET' == $method) {
            // load and render list of all users
            $users = UserModel::findAll();
            $this->render('user/all', $users);
        }
        if ('POST' == $method) {
            $jr = new JsonRequest();
            // read and parse POST data in JSON format
            $data = $jr->parse(file_get_contents('php://input'));
            if (!is_null($data)) { // parsing ok
                // creating new user
                $user = new UserModel();
                $user->populate((array)$data);
                if ($user->save(true, ['email', 'password'])) { // success
                    $this->render('user/create', $user);
                } else { // errors
                    $this->error($user->getErrors());
                }
            } else { // error during parsing input JSON data
                $this->error($jr->getErrors());
            }
        }
    }

    /**
     * Action for url /users/{userId}/
     * @param array $params Request params
     * @param string $method Request method
     */
    public function defaultAction($params, $method)
    {
        $id = intval($params[0]); // get ID from request params
        if ('GET' == $method) {
            // load user with specified id
            $user = UserModel::findByPk($id);
            if (!is_null($user)) { // success
                $this->render('user/user', $user);
            } else { // not found
                $this->error(array("Address with id '$id' does not exists"));
            }
        }

        if ('PUT' == $method) {
            $jr = new JsonRequest();
            // read and parse PUT data in JSON format
            $data = $jr->parse(file_get_contents('php://input'));
            if (!is_null($data)) { // parsing ok
                // is user exists?
                if ($user = UserModel::findByPk($id)) { // user exists, updating it
                    $user->populate((array)$data);
                    if ($user->save(true, ['email', 'password'])) { // success
                        $this->render('user/update', $user);
                    } else {
                        $this->error($user->getErrors());
                    }
                } else { // user not exists, creating new
                    $user = new UserModel();
                    $user->populate((array)$data);
                    if ($user->save(true, ['email', 'password'])) { // success
                        $this->render('user/create', $user);
                    } else {
                        $this->error($user->getErrors());
                    }
                }
            } else { // error during parsing input JSON data
                $this->error($jr->getErrors());
            }
        }

        if ('DELETE' == $method) {
            // deleting user
            if (UserModel::deleteByPk($id)) { // success
                $this->render('user/delete', $id);
            } else {
                $this->error([
                    sprintf('Can\'t remove user with id = %d', $id)
                ]);
            }
        }
    }

}