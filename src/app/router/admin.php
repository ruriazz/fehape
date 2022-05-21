<?php
defined('BASEPATH') or exit('No direct script access allowed');

// TODO: Dashboard Router
$Router->addRoute('GET', '/', function(Request $Request) {
    include_controller('admin/dashboard');
    new Dashboard($Request);
});