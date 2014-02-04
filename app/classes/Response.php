<?php

class Response extends AppAware
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * @var string Response body
     */
    protected $body = '';
    
    /**
     * @var int Respose code
     */
    protected $code = self::HTTP_OK;
    
    /**
     * @var array Respose headers
     */
    protected $headers = [];
    
    /**
     * Set response code
     * 
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get response code
     * 
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get all response headers
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Get specific response header
     * 
     * @param string $name
     * @return string|null
     */
    public function getHeader($name)
    {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }
        return null;
    }

    /**
     * Set response body
     * 
     * @param string $body
     * @return Response
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set reqponse code
     * 
     * @param int $code
     * @return Response
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set header values
     * 
     * @param array $headers
     * @return Response
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }
    
    /**
     * Set specific header value
     * 
     * @param string $name
     * @param string $value
     * @return Response
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Send response headers
     */
    protected function sendHeaders()
    {
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }

    /**
     * Send response data to user
     */
    public function send()
    {
        $this->sendHeaders();
        http_response_code($this->code);
        echo $this->body;
    }
    
}