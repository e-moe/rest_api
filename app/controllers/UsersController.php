<?php
class UsersController extends Controller
{
    /**
     * @var ModelsProvider
     */
    private $usersProvider;
    
    public function __construct(App $app, Response $response, View $view)
    {
        parent::__construct($app, $response, $view);
        $this->usersProvider = $this->app['usersProvider'];
    }

    /**
     * Before action event, check input body
     * 
     * @param string $action Action name
     * @param Request $request
     * @return bool 
     */
    public function beforeAction($action, Request $request)
    {
        if (!$request->getIsValid()) {
            $this->getResponse()->setBody(
                $this->jsonList('errors', $request->getErrors(), Response::HTTP_BAD_REQUEST)
            );
        }
        return $request->getIsValid();
    }
    
    /**
     * Action for GET /users/
     * 
     * @param Request $request
     */
    public function listGetAction(Request $request)
    {
        $users = $this->usersProvider->findAll();
        return $this->jsonList('users', $users);
    }
    
    /**
     * Action for POST /users/
     * 
     * @param Request $request
     */
    public function listPostAction(Request $request)
    {
        $user = $this->usersProvider->create();
        $user->populate((array)$request->getInput());
        if (!$user->save(true, ['email', 'password'])) { // success
            return $this->jsonList('errors', $user->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        $url = $this->view->url('/users/' . $user->id);
        $this->response->setCode(Response::HTTP_CREATED);
        $this->response->setHeader('Location', $url);
    }
    
    /**
     * Action for PUT /users/
     * 
     * @param Request $request
     */
    public function listPutAction(Request $request)
    {
        return $this->jsonMethodNotAllowd();
    }
    
    /**
     * Action for DELETE /users/
     * 
     * @param Request $request
     */
    public function listDeleteAction(Request $request)
    {
        return $this->jsonMethodNotAllowd();
    }
    
    /**
     * Action for GET /users/{id}
     * 
     * @param Request $request
     * @param string $id User id
     */
    public function userGetAction(Request $request, $id)
    {
        $id = intval($id);
        $user = $this->usersProvider->findByPk($id);
        if (!$user) {
            throw new NotFoundException();
        }
        return $this->json($user);
    }
    
    /**
     * Action for DELETE /users/{id}
     * 
     * @param Request $request
     * @param string $id User id
     */
    public function userDeleteAction(Request $request, $id)
    {
        $id = intval($id);
        $user = $this->usersProvider->findByPk($id);
        if (!$user) {
            throw new NotFoundException();
        }
        $user->delete();
        $this->response->setCode(Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Action for PUT /users/{id}
     * 
     * @param Request $request
     * @param string $id User id
     */
    public function userPutAction(Request $request, $id)
    {
        $id = intval($id);
        $user = $this->usersProvider->findByPk($id);
        if (!$user) {
            $user = $this->usersProvider->create();
            $this->response->setCode(Response::HTTP_CREATED);
        }
        $user->populate((array)$request->getInput());
        if (!$user->save(true, ['email', 'password'])) {
           return $this->jsonList('errors', $user->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        $url = $this->view->url('/users/' . $user->id);
        $this->response->setHeader('Location', $url);
    }
    
    /**
     * Action for POST /users/{id}
     * 
     * @param Request $request
     * @param string $id User id
     */
    public function userPostAction(Request $request, $id)
    {
        return $this->jsonMethodNotAllowd();
    }

}