<?php
class Service {
    private $requestType;
    private $path;
    public $error;


    public function __construct($requestType) {
        $this->requestType = $requestType;
    }


    public function getPath() {
        //get extension I.E read / create / delete
        $ext = $this->evaluateMethod();

        if ($ext) {
        $this->path = "{$ext}.php";
        //return to api controller
        return $this->path;
        }
    }
    

    private function evaluateMethod() {
        switch ($this->requestType) {
            case 'GET':
                if(isset($_GET['manga_id'])) {
                if($_GET['manga_id'] === '') return 'read_all';
                if($_GET['manga_id'] !== '') return 'read_single';
                }
                //default read_all without specifying id 
                return 'read_all';
                break;
            case 'POST':
                return 'create';
                break;
            case 'PUT':
                return 'update';
                break;
            case 'DELETE':
                return 'delete';
                break;
            default:
                $this->error = "{$this->requestType} isn't a supported type";
                break;
        }
    }

}