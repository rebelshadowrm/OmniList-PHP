<?php
class UnderConstruction extends View {
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);





        //updates model object 'data'
        //data = data in the main component
        $this->model->data = <<<HTML
        <div class ="container">
        <section class="construction">
        <header>
        <h1 class="construction__title">Under Construction</h1>
        </header>
        <p class="construction__body">This feature is not yet implemented.</p>
        <p class="construction__body">Sorry for the inconvience.</p>
        <a class="btn" href="index.php">Take me back home</a>
        </div>
        </section>
        HTML;


        // tells the model to give data
        $this->model->getOutput();

        // sets the output from model data
        $this->setOutput();

        // main render call
        echo $this->output;
    }


   

}