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
  $movie = new MovieList($db);
  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if (
    !empty($data->title) &&
    !empty($data->length)
  ) {

  $movie->title = $data->title;
  $movie->length = $data->length;

  // Create 
  if ($movie->create()) {
    // set response code - 201 created
    http_response_code(201);
    // tell the user
    echo json_encode(array("message" => "Movie was created."));
  } else {
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(
      ["message" => "Unable to create movie."]
    );
  }
} else {
  // set response code - 400 bad request
  http_response_code(400);   
  // tell the user
  echo json_encode(
    ["message" => "Unable to create movie. Data is incomplete."]
  );
}