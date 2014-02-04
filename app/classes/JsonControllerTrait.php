<?php

trait JsonControllerTrait
{
    /**
     * Return data in JSON format
     * 
     * @param mixed $data
     * @param int $responseCode
     * @return string
     */
    public function json($data = null, $responseCode = 200)
    {
        /**
         * @var Response
         */
        $response = $this->app['response'];
        $response->setHeader('Content-Type', 'application/json');
        $response->setCode($responseCode);
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * Return list in JSON format
     * 
     * @param string $name list name
     * @param mixed $list list data
     * @param int $responseCode
     * @return string
     */
    public function jsonList($name, $list, $responseCode = 200)
    {
        $data = (object)[
            $name => $list,
            'total' => count($list),
        ];
        return $this->json($data, $responseCode);
    }
    
    /**
     * @return string Returns error message
     */
    public function jsonMethodNotAllowd()
    {
        return $this->jsonList('errors', ['This method is now allowed here'], 405);
    }
}