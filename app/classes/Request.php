<?php

class Request extends DIAble
{
    protected $input = null;
    protected $isValid = true;
    protected $errors = [];

    public function __construct(\App $app)
    {
        parent::__construct($app);
        if (in_array($this->getHttpMethod(), ['POST', 'PUT'])) {
            $requestBody = file_get_contents('php://input');
            try {
                $data = $app['jsonParser']->parse($requestBody);
                $this->setInput($data);
            } catch (Exception $e) {
                $this->addError($e->getMessage());
            }
        }
    }
    
    public function getIsValid()
    {
        return $this->isValid;
    }
    
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors = [])
    {
        $this->errors = $errors;
        $this->setIsValid(count($errors) > 0);
        return $this;
    }

    public function addError($message)
    {
        $this->errors[] = $message;
        $this->setIsValid(false);
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setInput($data)
    {
        $this->input = $data;
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
        return str_replace($this->app['publicBaseUrl'], '', $_SERVER['REQUEST_URI']);
    }
}