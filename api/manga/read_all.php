<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json');

// get db
$database = new Database();
$db = $database->getConnection();

/// get manga
$manga = new MangaList($db);

$manga->set_user_id(isset($_GET['user_id']) ? filter_input(INPUT_GET, 'user_id') : NULL);

$stmt = $manga->readAll();
if($stmt) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = false;
}
if ($result) {
    // set response code - 200 OK
    http_response_code(200);
    // make it json format
    echo json_encode($result);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    // tell the user manga does not exist
    echo json_encode(
        ["message" => "User does not have a list or User does not exist."]
    );
}
