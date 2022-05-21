<?php
defined('BASEPATH') or exit('No direct script access allowed');

// TODO: Home Router
$Router->addRoute('GET', '/', function(Request $Request) {
    include_controller('public/home');
    new Home($Request);
});

// TODO: About Router
$Router->addRoute('GET', '/about/{name}', function(Request $Request, String $name) {
    include_controller('public/about');
    new About($Request, $name);
});