<?php
class Database {
  
    // specify your own database credentials
    private $host = '';
    private $db_name = '';
    private $username = '';
    private $password = '';
    public $conn;
  
    function __construct() {
        
    }

    // get the database connection
    public function getConnection(){
        $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
        $this->conn = null;
        try{
            $this->conn = new PDO($dsn,
                                  $this->username,
                                  $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                      
            $this->conn->exec("set names utf8");
        }catch(PDOException $e){
            throw new PDOException($e);
        }
  
        return $this->conn;
    }
}
?>
