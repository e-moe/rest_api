<?php

class DIAble
{
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
}