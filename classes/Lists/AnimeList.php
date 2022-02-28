<?php 
class AnimeList extends ListClass {

    //db
    private $conn;
    private $table = 'anime';

    //properties
    public $id;
    public $title;
    public $episodes;
    
    protected $end_date;
    protected $watched_episodes;
    protected $total_episodes;
    protected $studios;
    protected $producer;
    protected $source;
    
    
    function __construct($db) {
        $this->conn = $db;
    }

    public function create() {

        // query to insert anime
        $query = <<<SQL
                  INSERT INTO 
                  $this->table 
                  (title, episodes)
                  VALUES 
                  ( :title, :episodes )
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->episodes=htmlspecialchars(strip_tags($this->episodes));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":episodes", $this->episodes);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {

        // query to read anime
        $query = <<<SQL
        SELECT title, episodes
        FROM $this->table 
        WHERE manga_id = :id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind id of manga
        $stmt->bindParam(":id", $this->id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function readAll() {
        // query to read anime
        $query = <<<SQL
        SELECT title, episodes
        FROM $this->table 
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        // query to update anime
        $query = <<<SQL
                  UPDATE $this->table 
                  SET 
                   title = :title,
                   episodes = :episodes
                  WHERE
                   manga_id = :id 
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->episodes=htmlspecialchars(strip_tags($this->episodes));

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":episodes", $this->episodes);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // query to update anime
        $query = <<<SQL
        DELETE FROM
        $this->table 
        WHERE 
            manga_id = :id 
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(":id", $this->id);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
        
    }


}