<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->getConnection();
    // Instantiate new object
    $manga = new MangaList($db);
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
  
    if (
      !empty($data->user_id) &&
      !empty($data->manga_id)
    ) {
      $manga->set_user_id($data->user_id);
      $manga->set_manga_id($data->manga_id);
  
    // Create 
    if ($manga->delete()) {
      // set response code - 200 ok
      http_response_code(200);
      // tell the user
      echo json_encode(array("message" => "Manga was deleted."));
    } else {
      // set response code - 503 service unavailable
      http_response_code(503);
      // tell the user
      echo json_encode(
        ["message" => "Unable to delete manga."]
      );
    }
  } else {
    // set response code - 400 bad request
    http_response_code(400);   
    // tell the user
    echo json_encode(
      ["message" => "Unable to delete manga. Data is incomplete."]
    );
  }