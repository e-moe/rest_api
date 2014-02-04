<?php

class Request extends AppAware
{
    /**
     * @var mixed Input data of POST/PUT requests
     */
    protected $input = null;
    
    /**
     * @var array Request params
     */
    protected $params = [];
    
    /**
     * @var bool Is input valid data
     */
    protected $isValid = true;
    
    /**
     * @var array Of error messages
     */
    protected $errors = [];

    public function __construct(App $app)
    {
        parent::__construct($app);
        if (in_array($this->getHttpMethod(), ['POST', 'PUT'])) {
            $requestBody = file_get_contents('php://input');
            try {
                $data = $app['inputParser']->parse($requestBody);
                $this->setInput($data);
            } catch (Exception $e) {
                $this->addError($e->getMessage());
            }
        }
    }
    
    /**
     * @return bool Is request valid
     */
    public function getIsValid()
    {
        return $this->isValid;
    }
    
    /**
     * Set request valid state
     * 
     * @param bool $isValid
     * @return \Request
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }

    /**
     * @return array All error messges
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set errors messages
     * 
     * @param array $errors
     * @return \Request
     */
    public function setErrors(array $errors = [])
    {
        $this->errors = $errors;
        $this->setIsValid(count($errors) > 0);
        return $this;
    }

    /**
     * Add error message
     * 
     * @param string $message
     * @return \Request
     */
    public function addError($message)
    {
        $this->errors[] = $message;
        $this->setIsValid(false);
        return $this;
    }

    /**
     * @return mixed Input data
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set input data
     * 
     * @param mixed $data
     * @return \Request
     */
    public function setInput($data)
    {
        $this->input = $data;
        return $this;
    }

    /**
     * @return array Request params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set request params
     * 
     * @param array $params
     * @return \Request
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array Associative array of all the HTTP headers for the current request
     */
    public function getHeaders()
    {
        return getallheaders();
    }
    
    /**
     * @return string Client IP addr
     */
    public function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Get HTTP request method (e.g. GET, POST, ... )
     * 
     * @return string
     */
    public static function getHttpMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * @return string Request uri (e.g. /users/123)
     */
    public function getUri()
    {
        $uri = str_replace($this->app['publicBaseUrl'], '', $_SERVER['REQUEST_URI']);
        // add trailing slash
        return $uri . (substr($uri, -1) !== '/' ? '/' : '');
    }
}