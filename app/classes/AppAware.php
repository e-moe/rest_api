<?php

class AppAware
{
    /**
     * @var App
     */
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
}