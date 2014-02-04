<?php
include_once dirname(__FILE__) . '/defines.php';
include_once dirname(__FILE__) . '/autoloader.php';

class App extends DIContainer
{
    /**
     * Run application
     */
    public function run()
    {
        $this['config'] = include 'config.php';
        $this->initPublicBaseUrl();
        
        $this['db'] = function($app) { return new DB($app, $app['config']['db']); };
        $this['jsonParser'] = function($app) { return new JsonParser($app); };
        $this['request'] = function($app) { return new Request($app); };
        $this['response'] = function($app) { return new Response($app); };
        $this['controllerFactory'] = function($app) { return new ControllerFactory($app); };
        $this['router'] = function($app) {
            return new Router(
                    $app,
                    $app['request'],
                    $app['response'],
                    $app['controllerFactory'],
                    $app['config']['routs']
            );
        };
        $this['view'] = function($app) { return new View($app); };
        $this['validator'] = function($app) { return new Validator($app); };
        $this['usersProvider'] = function($app) { return new ModelsProvider($app, 'UserModel', $app['db']); };
        
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