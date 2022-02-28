<?php

class Model {
    public $title;
    public $head;
    public $footer;
    public $nav;
    public $main;
    public $com;
    public $data;
    public $errors = [];
    public $displayName;
    
     public function __construct() {

        $this->title = 'Index';

    }
    
    public function getOutput() {
        $routes = new Routes();
        $this->com = new Component();
        $this->com->title = $this->title;
        $this->com->main = $this->data;
        

        $this->head = $this->com->getHead();
        $this->main = $this->com->getMain();
        $this->footer = $this->com->getFooter();

        if(isset($_SESSION['user'])) {
            $navigation = new NavComponent(
                $routes->getSessionRoutes()
            );
        } else {
            $navigation = new NavComponent(
                $routes->getPublicRoutes()
            );
        }
        $this->nav = $navigation->getHeaderWithNav();
         
    }
    
    public function setTitle($value) {
        $this->title = $value;
    }
    
    function hasErrors() {
        return !empty($this->errors);
    }
    

}