<?php 
class Component {

    public $nav;
    public $footer;
    public $main;
    public $title = 'index';


    function __construct() {
        
    }


    public function getHead() {
        return <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta content="width=device-width,initial-scale=1.0" name="viewport" >
            <title>$this->title</title>
            <link rel="stylesheet" href="styles/main.css" />
            <link href="https://unpkg.com/tabulator-tables@4.9.3/dist/css/tabulator_midnight.css" rel="stylesheet">
            <!-- <script defer type="text/javascript" src="https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js"></script> -->
            <script type="text/javascript" src="script/script.js"></script>
            </head>
        HTML;
    }

    public function getHeader() {
        return <<<HTML
        <body>
        <header class="header">
            <h1 class="logo"><a href="index.php">OmniList</a></h1>
            <input id="toggleNav" type="checkbox" />
            <nav class="header__nav">        
            $this->nav
            </nav>
            <label class="toggle" for="toggleNav">
                <span></span>
            </label>
        </header>
        HTML;
    }

    public function getFooter() {
        $date = date("Y");
        return <<<HTML
        <footer>
        $this->footer
        <p class="copyright">
            &copy; $date OmniList, inc.
        </p>
        </footer>
        </body>
        </html>
        HTML;
    }

    public function getMain() {
        return <<<HTML
        <main>
        $this->main
        </main>
        HTML;
    }
    
}