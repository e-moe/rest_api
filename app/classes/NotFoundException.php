<?php

class NotFoundException extends Exception
{
    public function __construct($message = '404 Not Found')
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}