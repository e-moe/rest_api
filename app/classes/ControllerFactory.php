<?php
class ControllerFactory extends AppAware
{
    protected $controllers = [];
    
    /**
     * Controller Factory
     * 
     * @param string $controllerClass
     * @return Controller
     * @throws InvalidArgumentException
     */
    public function getController($controllerClass)
    {
        if (!class_exists($controllerClass)) {
            throw new InvalidArgumentException(sprintf('Class "%s" is not knows.', $controllerClass));
        }
        if (!isset($this->controllers[$controllerClass])) {
            $parents = class_parents($controllerClass);
            if (!in_array('Controller', $parents)) {
                throw new InvalidArgumentException(sprintf('Class "%s" is not Controller.', $controllerClass));
            }
            $this->controllers[$controllerClass] = new $controllerClass($this->app);
        }
        return $this->controllers[$controllerClass];
    }
}