<?php
class MangaController extends Controller {
    function __construct(\Model $model) {
        parent::__construct($model);


        if(!isset($_GET['id'])) {
        
            $page = isset($_POST['page']) ? filter_input(INPUT_POST, 'page') : 1;
            $this->generateMangaListing($page);
            
        }

    }

    private function generateMangaListing($page) {
        return $this->model->createMangaListing($page);
    }

}
