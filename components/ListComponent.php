<?php
class ListComponent extends component {




    function generateList($array, $className = '') {
        $generatedList = '';
        foreach ($array as $List) {
            $generatedList .=
            "{$List['title']}
            <ul class = \"{$className}\">
            {$List['li']}
            </ul>";
        }
        return $generatedList;
    }

    function createTitle($title, $h = "h2") {
        return "<{$h}>{$title}</{$h}>";
    }

    function createLi($array, $className) {
        $generatedList = '';
        foreach ($array as $ListItem) {
            $generatedList .=
            "<li class = \"{$className}\">
            {$ListItem}
            </li>";
        }
        
    return  $generatedList;
    }



}