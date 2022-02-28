<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
  if ($manga->create()) {
    // set response code - 201 created
    http_response_code(201);
    // tell the user
    echo json_encode( 
      ["message" => "Manga was created."]
    );
  } else {
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(
      ["message" => "Unable to create manga."]
    );
  }
} else {
  // set response code - 400 bad request
  http_response_code(400);   
  // tell the user
  echo json_encode(
    ["message" => "Unable to create manga. Data is incomplete."]
  );
}