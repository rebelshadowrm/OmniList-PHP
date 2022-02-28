<?php
class MangaView extends View {
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);

    
        $template = $this->model->getHTML();



    $this->model->data = <<<HTML
    $template
    <script defer type="text/javascript" src="script/mangaMedia.js"></script>
    HTML;
    
    // tells the model to give data
    $this->model->getOutput();

    // sets the output from model data
    $this->setOutput();

    // main render call
    echo $this->output;
    }


    
}