<?php

class Response extends AppAware
{
    /**
     * @var string
     */
    protected $body = '';
    
    /**
     * @var int
     */
    protected $code = 200;
    
    /**
     * @var array
     */
    protected $headers = [];
    
    /**
     * 
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * 
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
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

    protected function sendHeaders()
    {
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }

    public function send()
    {
        $this->sendHeaders();
        http_response_code($this->code);
        echo $this->body;
    }
    
}