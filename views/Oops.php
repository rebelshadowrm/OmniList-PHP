<?php
class Oops extends View {
    
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);



        //updates model object 'data'
        //data = data in the main component
        $this->model->data = <<<HTML
        <div class="error-page">
        <section>
        <header>
        <h1>Oops! Something went wrong.</h1>
        </header>
        <p>Sorry for the inconvenience. Please try again later.</p>
        <a class="btn" href="index.php">Take me back home</a>
        </section>
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
