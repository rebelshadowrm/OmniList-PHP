<?php
class mangaMediaComponent extends Component {

    function __construct() {

    }

    public function outputTemplate($id=null) {
        return $this->mediaContainer($id);
    }

    function mediaContainer($id) {
        $addItemContainer = $this->addItemContainer($id);
        $synapsis = $this->synapsis();
        $infoStrip = $this->infoStrip();
        $characterStrip = $this->characterStrip();

        return <<<HTML
        <div class="mediaContainer">
        <template id="mediaTemplate">
            <img src="" alt="" class="bannerImg">
            <div class="mediaContent">
                $addItemContainer
                $synapsis
                $infoStrip
                $characterStrip
            </div>
        </template>
        </div>
        HTML;
    }


    function addItemContainer($id) {
        $data = '';
        $data .= <<<HTML
        <div class="addMedia">
            <img class="addMedia__Img">
        HTML;
        if($id !== null) {
            $data .= <<<HTML
            <button data-id="{$id}" class="addMedia__Button">Add to List</button>
        HTML;
        }
        $data .= '</div>';

        return $data;
        
    }

    function synapsis() {
        return <<<HTML
        <section class="synapsis">
            <header class="synapsis__header">
                <h1 class="synapsis__title"></h1>
            </header>
            <div class="synapsis__body">
                <p class="synapsis__text"></p>
            </div>
        </section> 
        HTML;
    }

    function characterStrip() {
        return <<<HTML
        <div class="characters">
        <template id="characterTemplate">
            <div class="character">
                <img src="" alt="" class="character__Img">
                <h4 class="character__Name"></h4>
            <div>
        </template>
        </div>
        HTML;
    }

    function infoStrip() {
        return <<<HTML
        <aside class="informationAside">
        <template id="informationTemplate">
            <div class="information">
                <h4 class="information__title"></h4>
                <p class="information__details"></p>
            </div>
        </template>
        </aside>
        HTML;
    }

    function rating() {
        return <<<HTML
        <div class="rating">
            <h2 class="rating__number"></h2>
        </div>
        HTML;
    }







}