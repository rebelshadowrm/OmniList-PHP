<?php 
class ProfileModel extends Model {
    private $json;

    function __construct() {

    }

    public function getUserById($id) {
        $database = new database();
        $db = $database->getConnection();
        $handler = new UserHandler($db);

        return $handler->getUserById($id);
    }


    public function outputJson() {
        echo $this->json;
    }

}