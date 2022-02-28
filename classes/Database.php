<?php
class Database {
  
    // specify your own database credentials
    private $host = 'capitulate.net:3306';
    private $db_name = 'OmniList';
    private $username = 'capitulate';
    //A friend set this db up for me a long time ago, I don't how to change this
    private $password = 'pocketpussy';
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
