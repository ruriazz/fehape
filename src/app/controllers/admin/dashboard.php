<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends \BaseController {
    function __construct($Request) {
        parent::__construct($Request);

        $model = new ViewModel(
            'pages/admin/dashboard.html',
            'Dashboard Page',
            [],
            [load_css('css/bootstrap.css')],
        );

        $this->load->view($model);
    }
}