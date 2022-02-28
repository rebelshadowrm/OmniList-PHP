<?php

class Routes {

    //Need to think of potentially a better way to do this.

    private $routes = [];

    private $publicRoutes = [
        ['display' => 'Manga',      'href' => 'manga'],
        ['display' => 'Anime',      'href' => 'anime'],
        ['display' => 'TV',         'href' => 'tv'],
        ['display' => 'Movies',     'href' => 'movies'],
        ['display' => 'Games',      'href' => 'games'],
        ['display' => 'Login',      'href' => 'login'],
        ['display' => 'Register',   'href' => 'register']
    ];
    private $sessionRoutes = [
        ['display' => 'Manga',      'href' => 'manga'],
        ['display' => 'Anime',      'href' => 'anime'],
        ['display' => 'TV',         'href' => 'tv'],
        ['display' => 'Movies',     'href' => 'movies'],
        ['display' => 'Games',      'href' => 'games'],
        ['display' => 'Profile',    'href' => 'profile'],
        ['display' => 'Logout',     'href' => 'logout']
    ];

    //maybe allow each model to generate it's own route?
    public function __construct() {
        
    }

    public function getPublicRoutes() {
        return $this->publicRoutes;
    }
    public function getSessionRoutes() {
        return $this->sessionRoutes;
    }
    public function getAllRoutes() {
        $this->routes .= $this->publicRoutes . $this->sessionRoutes;
        return $this->routes;
    }








}