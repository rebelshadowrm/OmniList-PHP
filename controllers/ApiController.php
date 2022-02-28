<?php
class ApiController extends Controller {

    public function __construct($apiType, $requestType) {

        if($apiType === 'dbFill') {

            include 'api/dbFill.php';

        } else {

        $service = new Service($requestType);
        $path = $service->getPath();
        if($service->error) {
            // set response code - 404 Not found
            http_response_code(404);
                echo json_encode(
                    ['message' => "{$service->error}"]
                );
        } else {

            try {
                if($path) {
                    switch ($apiType) {
                        case 'manga':
                            include "api/{$apiType}/{$path}";
                            break;
                        case 'anime':
                            include "api/{$apiType}/{$path}";
                            break;
                        case 'tv':
                            include "api/{$apiType}/{$path}";
                            break;
                        case 'movie':
                            include "api/{$apiType}/{$path}";
                            break;
                        case 'game':
                            include "api/{$apiType}/{$path}";
                            break;
                        default:
                        // set response code - 404 Not found
                        http_response_code(404);
                            echo json_encode(
                                ['message' => "{$apiType} does not exist"]
                            );
                            break;
                    }
                } else {
                    // set response code - 404 Not found
                    http_response_code(404);
                    echo json_encode(
                        ['message' => "{$path} not found"]
                    );
                }
            } catch(Exception $e) {
                throw new Exception($e);
            }

      
        }


        }

    }
    
}