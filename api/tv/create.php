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
  $tv = new TvList($db);
  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if (
    !empty($data->title) &&
    !empty($data->episodes)
  ) {

  $tv->title = $data->title;
  $tv->episodes = $data->episodes;

  // Create 
  if ($tv->create()) {
    // set response code - 201 created
    http_response_code(201);
    // tell the user
    echo json_encode(array("message" => "tv was created."));
  } else {
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(
      ["message" => "Unable to create tv."]
    );
  }
} else {
  // set response code - 400 bad request
  http_response_code(400);   
  // tell the user
  echo json_encode(
    ["message" => "Unable to create tv. Data is incomplete."]
  );
}