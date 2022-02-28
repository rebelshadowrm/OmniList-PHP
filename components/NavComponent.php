<?php
class NavComponent extends Component {

    public $nav;


    function __construct($array) {
        try {
        $this->nav = '<ul class="nav">';
        $this->nav .= "<li class=\"nav__item\">
                        <a class=\"nav__link\"
                        href=\"index.php\">Home</a>
                        </li>"; 
        foreach ($array as $navItem) {
        $this->nav .= 
                "<li class=\"nav__item\">
                 <a class=\"nav__link\"
                href=\"?route={$navItem['href']}\">
                              {$navItem['display']}</a>
                </li>";
        }
        $this->nav .= '</ul>';
    } catch (Exception $e) {
        throw $e;
    }
    }

    function getNav() {
        return $this->nav;
    }

    function getHeaderWithNav() {
        return parent::getHeader($this->nav);
    }




}