<?php 
class ListClass {
    
    protected $user_id;
    protected $rating;
    protected $title;
    protected $genres = [];


    function __construct() {
        
    }

    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }

    public function set_title($title) {
        $this->title = $title;
    }

    public function set_rating($rating) {
        $this->rating = $rating;
    }

    public function set_genres($genres) {
        if(is_array($genres)) {
            foreach ($genres as $genre) {
                array_push($this->genres, $genre);
            }
        } 
        if(!is_array($genres)) {
            array_push($this->genre, $genres);
        }
    }



}