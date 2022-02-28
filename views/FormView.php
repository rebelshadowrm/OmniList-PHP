<?php
class FormView extends View {
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);
    




    $this->model->setTitle($this->model->formType);
    $this->model->data = <<<HTML
        <div class="forms">
            {$this->model->HTMLform}
        </div>
    HTML;

    // tells the model to give data
    $this->model->getOutput();

    // sets the output from model data
    $this->setOutput();

    // main render call
    echo $this->output;
    }
}