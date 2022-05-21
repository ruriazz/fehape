<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends \BaseController {
    function __construct(Request $Request) {
        parent::__construct($Request);

        $model = new ViewModel(
            'pages/public/home.html',
            'Home Page',
            [],
            [
                load_css('css/bootstrap.css'),
                load_css('css/fontawesome.css')
            ],
            [
                load_js(base_url('static/dist/cryptojs/crypto-js.js')),
                load_js('js/jquery.js'),
                load_js('js/bootstrap.js'),
            ],
            [new MetaTags('Test', 'description')]
        );

        $this->load->view($model);
    }
}