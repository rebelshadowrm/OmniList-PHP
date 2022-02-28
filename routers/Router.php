<?php

class Router {
    private $routes = [];
    
    public function __construct() {

        $this->routes['index'] =
                new Route('Model',
                          'HomeView',
                          'Controller');
        $this->routes['manga'] =
                new Route('MangaModel',
                          'MangaView',
                          'MangaController'); 
        $this->routes['manga_list'] =
                new Route('Model',
                          'MangaListView',
                          'Controller'); 
        $this->routes['api'] = 
                new Route('MangaModel',
                          'ApiView',
                          'MangaController');
        $this->routes['login'] = 
                new Route('FormModel',
                          'FormView',
                          'FormController');
        $this->routes['register'] = 
                new Route('FormModel',
                         'FormView',
                         'FormController');
        $this->routes['profile'] = 
                new Route('ProfileModel',
                         'ProfileView',
                         'ProfileController');
        $this->routes['logout'] = 
                new Route('Model',
                         'View',
                         'Controller');

        $this->routes['anime'] = 
                new Route('Model',
                         'UnderConstruction',
                         'Controller');
        $this->routes['games'] = 
                new Route('Model',
                         'UnderConstruction',
                         'Controller');            
        $this->routes['tv'] = 
                new Route('Model',
                         'UnderConstruction',
                         'Controller');            
        $this->routes['movies'] = 
                new Route('Model',
                         'UnderConstruction',
                         'Controller');                    
        
                          
        //need to create default view
        $this->routes['Oops'] =
                new Route('Model',
                          'Oops',
                          'Controller');
                          
    }
    
    public function getRoute($route) {
        $route = strtolower($route);

        if (empty($route) || $route === null) {
            return $this->routes['index'];
        }
        
        //Return a default route if no route is found
        if (!isset($this->routes[$route])) {
            return $this->routes['Oops'];    
        }
        
        return $this->routes[$route];        
    } 
	
}