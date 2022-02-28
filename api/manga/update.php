<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');

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



      if(isset($data->chapters_read)) {
        $manga->set_chapters_read($data->chapters_read);
      }
      if(isset($data->progress)) {
        $manga->set_chapters_read($data->progress);
      }
      
      if(isset($data->rating)) {
        $manga->set_rating($data->rating);
      }

      if(isset($data->manga_rating)) {
        $manga->set_rating($data->manga_rating);
      }
      if(isset($data->status_id)) {
        $manga->set_status_id($data->status_id);
      }
  
    // Update 
    if ($manga->update()) {
      // set response code - 200 ok
       http_response_code(200);
      // tell the user
      echo json_encode(array("message" => "Manga was updated."));
    } else {
      // set response code - 503 service unavailable
      http_response_code(503);
      // tell the user
      echo json_encode(
        ["message" => "Unable to update manga."]
      );
    }
  } else {
    // set response code - 400 bad request
    http_response_code(400);   
    // tell the user
    echo json_encode(
      ["message" => "Unable to update manga. Data is incomplete."]
    );
  }