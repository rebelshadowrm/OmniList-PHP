<?php 
class MovieList extends ListClass {

    //db
    private $conn;
    private $table = 'movie';

    //properties
    public $id;
    public $title;
    public $length;


    protected $runtime;
    protected $director;
    protected $sequal_number;

    
    function __construct($db) {
        $this->conn = $db;
    }

    public function create() {

        // query to insert movie
        $query = <<<SQL
                  INSERT INTO 
                  $this->table 
                  (title, length)
                  VALUES 
                  ( :title, :length )
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->length=htmlspecialchars(strip_tags($this->length));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":length", $this->length);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {

        // query to read movie
        $query = <<<SQL
        SELECT title, length
        FROM $this->table 
        WHERE manga_id = :id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind id of movie
        $stmt->bindParam(":id", $this->id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function readAll() {
        // query to read movie
        $query = <<<SQL
        SELECT title, length
        FROM $this->table 
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        // query to update movie
        $query = <<<SQL
                  UPDATE $this->table 
                  SET 
                   title = :title,
                   length = :length
                  WHERE
                   manga_id = :id 
                  SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->length=htmlspecialchars(strip_tags($this->length));

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":length", $this->length);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // query to update movie
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