<?php

//2525 Program 3

//The best way to get a project done faster is to start sooner. -Jim Highsmith

//The first 90 percent of the code accounts for the first 10 percent of the development time.
//The remaining 10 percent of the code accounts for the other 90 percent of the development time. -Tom Cargill

include 'declares.php';
session_start();



function exception_handler($exception) {
    $e = $exception;
    include 'templates/error.php';
    die();
}

set_exception_handler('exception_handler');



$requestType = $_SERVER['REQUEST_METHOD'];
    
$destination = isset($_GET['route']) ? filter_input(INPUT_GET, 'route') : NULL;
$destination = strtolower($destination);

// api  
if($destination === 'api') {
    
    $var = array_keys($_GET);
    $apiType = isset($_GET['type']) ? filter_input(INPUT_GET, 'type') : NULL;
   
    $apiController = new ApiController($apiType, $requestType);
} 

// normal routing
if ($destination !== 'api') {

    if($destination === NULL) {
        $destination = filter_input(INPUT_POST, 'route');
    }

    // calls action of controller
$action = filter_input(INPUT_GET, 'action');
    if($action === NULL) {
        $action = filter_input(INPUT_POST, 'action');
    }

    // parameters for the controller
$param = filter_input(INPUT_GET, 'param',
         FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if($param === False) {
        $param = filter_input(INPUT_GET, 'param',FILTER_DEFAULT);
    }
    if($param === NULL) {
        $param = filter_input(INPUT_POST, 'param',
                 FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            if($param === False) {
                $param = filter_input(INPUT_POST, 'param',FILTER_DEFAULT);
            }
    }

// create front controller
$frontController =
        new FrontController(new Router,
                            $destination,
                            $action,
                            $param,
                            $requestType);

}
?>
