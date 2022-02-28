<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->getConnection();
  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));
  

  $manga_id = $data->manga_id;
  $title = $data->title;
  $chapters = $data->chapters;
  $status = $data->status;
  $genres = $data->genres;
try {
  $query = <<<SQL
  insert into manga
  (
      manga_id,
      title,
      chapters,
      status
  )
  values
  (
      :manga_id,
      :title,
      :chapters,
      :status
  )
SQL;
// prepare query
$stmt = $db->prepare($query);

$stmt->bindParam(":manga_id", $manga_id);
$stmt->bindParam(":title", $title);
$stmt->bindParam(":chapters", $chapters);
$stmt->bindParam(":status", $status);


if($stmt->execute()) {


foreach($genres as $genre) {

    $query2 = <<<SQL
        select genre
        from genre
        where genre = :genre
    SQL;
    $stmt = $db->prepare($query2);
    $stmt->bindParam(":genre", $genre);
    $stmt->execute();
    $res = $stmt->fetch();

    if(!$res) {
        $query3 = <<<SQL
            insert into genre
            (
                genre
            )
            values
            (
                :genre
            )
        SQL;
        $stmt = $db->prepare($query3);
        $stmt->bindParam(":genre", $genre);
        $stmt->execute();
    }

    $query4 = <<<SQL
        insert into rlt_manga_genre
        (
            manga_id,
            genre_id
        )
        select
            :manga_id,
            genre_id
        from genre
        where genre = :genre
    SQL;

    $stmt = $db->prepare($query4);
    $stmt->bindParam(":manga_id", $manga_id);
    $stmt->bindParam(":genre", $genre);
    $stmt->execute();        
}

echo json_encode(
    ['message' => $title.' added']
);

} else {
    echo json_encode(
        ['message' => 'insert does not work']
    );
}
} catch (PDOException $e) {
    echo json_encode( [
        'message' => $e
    ]);
}