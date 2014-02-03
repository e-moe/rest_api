<?php
include_once dirname(__FILE__) . '/configuration.php';
include_once dirname(__FILE__) . '/defines.php';
include_once dirname(__FILE__) . '/autoloader.php';

class App extends DIContainer
{

    /**
     * @var string Base url for application
     */
    protected $publicBasePath = '';



    /**
     * Run application
     */
    public function run()
    {
        $this->initPublicBaseUrl();
        $this['router'] = function($app) { return new Router($app); };
        $this['controllerFactory'] = function($app) { return new ControllerFactory($app); };
        $this['jsonParser'] = function($app) { return new JsonParser($app);};
        $this['request'] = function($app) { return new Request($app); };
        $this['response'] = function($app) { return new Response($app); };
        $this['view'] = function($app) { return new View($app); };
        $this['router']->route();
    }

    

    /**
     * Determine public base url for application
     */
    protected function initPublicBaseUrl()
    {
        $this['publicBaseUrl'] = str_replace(
            str_replace(
                INDEX_PATH,
                "",
                $_SERVER['SCRIPT_FILENAME']
            ),
            "",
            $_SERVER['SCRIPT_NAME']
        );
    }
    
}