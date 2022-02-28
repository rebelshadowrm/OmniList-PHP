<?php
class MangaListView extends View {
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);
    


    $session = isset($_SESSION['user']) ? $_SESSION['user']->getId() : NULL;
    $get = isset($_GET['id']) ? filter_input(INPUT_GET, 'id') : NULL;
    $user_id = $get !== null ? $get : $session;
        

    
    $this->model->data = <<<HTML
        <div class="manga__list_container">
        <div data-user_id="{$user_id}" id="manga-table"></div>
        </div>
        <script type="module" src="script/mangaList.js"></script>
    HTML;

    // tells the model to give data
    $this->model->getOutput();

    // sets the output from model data
    $this->setOutput();

    // main render call
    echo $this->output;
    }
}