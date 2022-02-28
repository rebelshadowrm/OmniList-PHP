<?php


class FrontController extends Controller {
    private $controller;
    private $view;
    
    public function __construct(Router $router,
                                       $routeName,
                                       $action = null,
                                       $param = [],
                                       $requestType = null) {
        
        $route = $router->getRoute($routeName);
        $modelName = $route->model;
        $controllerName = $route->controller;
        $viewName = $route->view;

        $param === [] ? array_push($param, $requestType) : $param = [$param, $requestType];
        
        $model = new $modelName;
        $this->controller = new $controllerName($model);
        $this->view = new $viewName($routeName, $model);
        
	if (!empty($action)) {
            if (!empty($param)) {
                call_user_func_array(array($this->controller, $action),$param);
            } else {
                $this->controller->{$action}($requestType);
            }
        }
    }
    
    // public function output() {
    //     return $this->view->output();
    // }
    
    // public function outputTitle() {
    //     return $this->view->outputTitle();
    // }

    // public function outputNav() {
    //     return $this->view->outputNav();
    // }
      
} 