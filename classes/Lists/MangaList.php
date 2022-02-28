<?php 
class MangaList extends ListClass {

    //db
    private $conn;
    private $table = 'manga_list';

    protected $manga_id;
    protected $completed_at;
    protected $chapters_read;
    protected $status_id;
    
    
    function __construct($db) {
        $this->conn = $db;
    }

    public function set_completed_at($date) {
        $this->completed_at = $date;
    }
    public function set_chapters_read($chapters) {
        $this->chapters_read = $chapters;
    }
    public function set_status_id($status_id) {
        $this->status_id = $status_id;
    }
    public function set_manga_id($manga_id) {
        $this->manga_id = $manga_id;
    }


    public function create() {

        // query to insert manga
        $query = <<<SQL
                  REPLACE INTO 
                  $this->table 
                  ( 
                    manga_id, user_id
                  )
                  values 
                  ( 
                    :manga_id, :user_id
                  )
                SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->user_id));
        $this->id=htmlspecialchars(strip_tags($this->manga_id));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":manga_id", $this->manga_id);
        
        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {

        if($this->user_id === null || $this->manga_id === null) {
            return false;
        }

        // query to read manga
        $query = <<<SQL
        SELECT ml.manga_id, ml.status_id as user_status, ml.progress,
         ml.manga_rating, m.title, m.chapters, m.status as manga_status
        FROM $this->table ml
        inner join manga m
        on ml.manga_id = m.manga_id
        WHERE ml.manga_id = :manga_id
        AND ml.user_id = :user_id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind user_id and manga_id
        $stmt->bindParam(":manga_id", $this->manga_id);
        $stmt->bindParam(":user_id", $this->user_id);
        
        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function readAll() {

        if($this->user_id === null) {
            return false;
        }

        // query to read manga
        $query = <<<SQL
        SELECT ml.manga_id, us.user_alias, s.status as user_status, ml.progress,
         ml.manga_rating, m.title, m.chapters, m.status as manga_status
        FROM $this->table ml
        inner join manga m
        on ml.manga_id = m.manga_id
        inner join status s
        on ml.status_id = s.status_id
        inner join user_settings us 
        on us.user_id = ml.user_id
        where ml.user_id = :user_id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind user_id
        $stmt->bindParam(":user_id", $this->user_id);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        //This function will check 'which' things need to be updated.

        if(!empty($this->rating) && $this->rating !== NULL) {
            return $this->update_rating();
        }
        else if(!empty($this->status_id) && $this->status_id !== NULL) {
            return $this->update_status();
        }
        else if(!empty($this->chapters_read) && $this->chapters_read !== NULL) {
            return $this->update_chapters();
        }

    }


    private function update_rating() {
                // query to update manga
                $query = <<<SQL
                UPDATE $this->table as ml,
                (
                    select manga_list_id 
                    from manga_list 
                    where user_id = :user_id AND manga_id = :manga_id
                ) as ml2
                SET ml.manga_rating = :manga_rating
                where ml.manga_list_id = ml2.manga_list_id
                SQL;

      // prepare query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->user_id=htmlspecialchars(strip_tags($this->user_id));
      $this->manga_id=htmlspecialchars(strip_tags($this->manga_id));
      $this->rating=htmlspecialchars(strip_tags($this->rating));

      // bind values
      $stmt->bindParam(":user_id", $this->user_id);
      $stmt->bindParam(":manga_id", $this->manga_id);
      $stmt->bindParam(":manga_rating", $this->rating);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function update_status() {
                // query to update manga
                $query = <<<SQL
                UPDATE $this->table ml,
                (
                    select manga_list_id 
                    from manga_list 
                    where user_id = :user_id AND manga_id = :manga_id
                ) as ml2
                SET ml.status_id = :status_id
                where ml.manga_list_id = ml2.manga_list_id
                SQL;

      // prepare query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->user_id=htmlspecialchars(strip_tags($this->user_id));
      $this->manga_id=htmlspecialchars(strip_tags($this->manga_id));
      $this->status_id=htmlspecialchars(strip_tags($this->status_id));

      // bind values
      $stmt->bindParam(":user_id", $this->user_id);
      $stmt->bindParam(":manga_id", $this->manga_id);
      $stmt->bindParam(":status_id", $this->status_id);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function update_chapters() {
                // query to update manga
                $query = <<<SQL
                UPDATE $this->table ml,
                (
                    select manga_list_id 
                    from manga_list 
                    where user_id = :user_id AND manga_id = :manga_id
                ) as ml2
                SET ml.progress = :chapters_read
                where ml.manga_list_id = ml2.manga_list_id
                SQL;

      // prepare query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->user_id=htmlspecialchars(strip_tags($this->user_id));
      $this->manga_id=htmlspecialchars(strip_tags($this->manga_id));
      $this->chapters=htmlspecialchars(strip_tags($this->chapters_read));

      // bind values
      $stmt->bindParam(":user_id", $this->user_id);
      $stmt->bindParam(":manga_id", $this->manga_id);
      $stmt->bindParam(":chapters_read", $this->chapters_read);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        
        $query = <<<SQL
        DELETE FROM
        $this->table 
        WHERE 
            manga_id = :manga_id AND
            user_id = :user_id
        SQL;

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->user_id));
        $this->id=htmlspecialchars(strip_tags($this->manga_id));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":manga_id", $this->manga_id);

        // execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
        
    }

}