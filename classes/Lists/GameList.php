<?php 
class GameList extends ListClass {


    //db
    private $conn;
    private $table = 'game';

    //properties
    public $id;
    public $title;
    public $type;

    protected $game_type;
    protected $platforms;


    function __construct($db) {
        $this->conn = $db;
    }

    public function create() {

        // query to insert game
        $query = <<<SQL
                  INSERT INTO 
                  $this->table 
                  (title, type)
                  VALUES 
                  ( :title, :type )
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->type=htmlspecialchars(strip_tags($this->type));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":type", $this->type);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {

        // query to read game
        $query = <<<SQL
        SELECT title, type
        FROM $this->table 
        WHERE manga_id = :id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind id of game
        $stmt->bindParam(":id", $this->id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function readAll() {
        // query to read game
        $query = <<<SQL
        SELECT title, type
        FROM $this->table 
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        // query to update game
        $query = <<<SQL
                  UPDATE $this->table 
                  SET 
                   title = :title,
                   type = :type
                  WHERE
                   manga_id = :id 
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->type=htmlspecialchars(strip_tags($this->type));

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":type", $this->type);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // query to update game
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