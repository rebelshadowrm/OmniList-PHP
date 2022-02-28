<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json');

// get db
$database = new Database();
$db = $database->getConnection();

/// get game
$game = new GameList($db);

$stmt = $game->readAll();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    // set response code - 200 OK
    http_response_code(200);
    // make it json format
    echo json_encode($result);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    // tell the user game does not exist
    echo json_encode(
        ["message" => "Game does not exist."]
    );
}