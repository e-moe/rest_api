<?php

class NotFoundException extends Exception
{
    public function __construct($message = '404 Not Found')
    {
        parent::__construct($message, 404);
    }
}