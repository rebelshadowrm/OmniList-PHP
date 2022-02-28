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
    $anime = new AnimeList($db);
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
  
    if (
      !empty($data->id) &&
      !empty($data->title) &&
      !empty($data->episodes)
    ) {
  
    $anime->id = $data->id;
    $anime->title = $data->title;
    $anime->episodes = $data->episodes;
  
    // Create 
    if ($anime->update()) {
      // set response code - 200 ok
       http_response_code(200);
      // tell the user
      echo json_encode(array("message" => "Anime was updated."));
    } else {
      // set response code - 503 service unavailable
      http_response_code(503);
      // tell the user
      echo json_encode(
        ["message" => "Unable to update anime."]
      );
    }
  } else {
    // set response code - 400 bad request
    http_response_code(400);   
    // tell the user
    echo json_encode(
      ["message" => "Unable to update anime. Data is incomplete."]
    );
  }