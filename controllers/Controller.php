<?php
class Controller {
    protected $model;

    public function __construct(Model $model) 
    {
        $this->model = $model; 

        //check for logout route, then log the user out
        if(isset($_GET['route'])) {
            if($_GET['route'] === 'logout') {
                session_destroy();
                header('Location: index.php');
            }
        }


    }    
}
