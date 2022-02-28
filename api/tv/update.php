<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->getConnection();
    // Instantiate new object
    $tv = new TvList($db);
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
  
    if (
      !empty($data->id) &&
      !empty($data->title) &&
      !empty($data->episodes)
    ) {
  
    $tv->id = $data->id;
    $tv->title = $data->title;
    $tv->episodes = $data->episodes;
  
    // Create 
    if ($tv->update()) {
      // set response code - 200 ok
       http_response_code(200);
      // tell the user
      echo json_encode(array("message" => "tv was updated."));
    } else {
      // set response code - 503 service unavailable
      http_response_code(503);
      // tell the user
      echo json_encode(
        ["message" => "Unable to update tv."]
      );
    }
  } else {
    // set response code - 400 bad request
    http_response_code(400);   
    // tell the user
    echo json_encode(
      ["message" => "Unable to update tv. Data is incomplete."]
    );
  }