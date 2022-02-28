<?php
class HomeView extends View {
    
    public function __construct($route, \Model $model) {
        parent::__construct($route, $model);



      
        $this->model->data = <<<HTML
        <div class="container">
            
            <h1 class="page__title">Welcome to OmniList</h1>
            <h2 class="page__sub">your place to store all types of lists</h2>
        HTML;
        if (!isset($_SESSION['user'])) {


        $this->model->data .= <<<HTML
            <div class="call">
            <div class="signup">
                <h3 class="signup__title">New to our platform?</h3>
                <h4 class="signup__sub">Get started tracking today</h4>
                <a class='signup__btn' href="index.php?route=register">sign up</a>
            </div>
            <div class="returning">
                <h4 class="returning__title">Already have an account?</h4>
                <a class="returning__btn" href="index.php?route=login">login</a>
            <div> 
        HTML;
        }



        // //add news
        // //future work: maybe create a carousel

        // $this->model->data .= <<<HTML
        // <!--containers-->
        // <h2>Currently Releasing</h2>
        // <div class="current">
        // </div>
        // <h2>Most Popular</h2>
        // <div class="popular">
        // <div>

        // <!--Template for all news cards-->
        // <template id="homeCardTemplate">
        //     <div class="homeCard">
        //         <img class="homeCard__img" src="" alt="" src="">
        //         <h4 class="homeCard__title"></h4>
        //     </div>
        // </template>
        // HTML;

        
        // $this->model->data .= <<<HTML
        // </div>
        // </div>
        // <!--code to generate news cards from api-->
        // <script type="text/javascript" src="script/home.js"></script>
        // HTML;


        // tells the model to give data
        $this->model->getOutput();

        // sets the output from model data
        $this->setOutput();

        // main render call
        echo $this->output;
    }


   

}
