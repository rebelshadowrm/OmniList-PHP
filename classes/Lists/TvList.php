<?php 
class TvList extends ListClass {


    //db
    private $conn;
    private $table = 'tv';

    //properties
    public $id;
    public $title;
    public $episodes;

    protected $episodes_total;
    protected $episodes_watched;
    protected $tv_station;
    protected $seasons;
    protected $source;
    protected $adaptations;



    function __construct($db) {
        $this->conn = $db;
    }

    public function create() {

        // query to insert tv
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

        // query to read tv
        $query = <<<SQL
        SELECT title, episodes
        FROM $this->table 
        WHERE manga_id = :id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind id of tv
        $stmt->bindParam(":id", $this->id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function readAll() {
        // query to read tv
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
        // query to update tv
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
        // query to update tv
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