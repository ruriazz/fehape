<?php
defined('BASEPATH') or exit('No direct script access allowed');

class About extends \BaseController {
    function __construct(Request $Request, String $name) {
        parent::__construct($Request);

        $model = new ViewModel(
            'pages/public/about.html',
            'Dashboard Page',
            ['name' => $name],
            [load_css('css/bootstrap.css')]
        );

        $this->load->view($model);
    }
}