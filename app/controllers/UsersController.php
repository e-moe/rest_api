<?php
class UsersController extends Controller
{
    /**
     * Before action event
     * 
     * @param string $action Action name
     * @param Request $request
     * @return bool 
     */
    public function beforeAction($action, Request $request)
    {
        if (!$request->getIsValid()) {
            /**
             * @var Response
             */
            $response = $this->app['response'];
            $errors = $request->getErrors();
            $data = (object)[
                'errors' => $errors,
                'total' => count($errors),
            ];
            $response->setBody($this->json($data, 400));
        }
        return $request->getIsValid();
    }
    
    /**
     * Action for url /users/
     * 
     * @param Request $request
     */
    public function actionGetIndex(Request $request)
    {
        $users = UserModel::findAll();
        $data = (object)[
            'users' => $users,
            'total' => count($users),
        ];
        return $this->json($data);
    }
    
    /**
     * Action for url /users/
     * 
     * @param Request $request
     */
    public function actionPostIndex(Request $request)
    {
        $user = new UserModel();
        $user->populate((array)$request->getInput());
        if (!$user->save(true, ['email', 'password'])) { // success
            $errors = $user->getErrors();
            $data = (object)[
                'errors' => $errors,
                'total' => count($errors),
            ];
            return $this->json($data, 400);
        }
        $data = (object)[
            'success' => true,
            'url' => $this->view->url('/users/' . $user->id),
        ];
        return $this->json($data, 201);
    }
    
    /**
     * Action for url /users/{userId}/
     *
     * @param array $params Request params
     * @param string $method Request method
     */
    public function defaultAction($params, $method)
    {
        $id = intval($params[0]); // get ID from request params
        if ('GET' == $method) {
            // load user with specified id
            $user = UserModel::findByPk($id);
            if ($user) {
                $this->render('user/user', $user);
            } else {
                $this->error(array("Address with id '$id' does not exists"));
            }
        }

        if ('PUT' == $method) {
            $jr = new JsonRequest();
            // read and parse PUT data in JSON format
            $data = $jr->parse(file_get_contents('php://input'));
            if (!is_null($data)) { // parsing ok
                $user = UserModel::findByPk($id);
                if ($user) { // user exists, updating it
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
            if (UserModel::deleteByPk($id)) {
                $this->render('user/delete', $id);
            } else {
                $this->error([
                    sprintf('Can\'t remove user with id = %d', $id)
                ]);
            }
        }
    }

}