<?php
class View {
    protected $model;
    protected $route;

    public $output;
    
    
    public function __construct($route, Model $model) {
        $this->route = $route;
        $this->model = $model;
    }

    public function setOutput() {
        $this->output =
        "{$this->model->head}
         {$this->model->nav}
         {$this->model->main}
         {$this->model->footer}";
    }

}
