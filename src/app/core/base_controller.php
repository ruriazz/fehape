<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseController extends \Application {
    protected Request $Request;
    protected Loader $load;

    function __construct(Request $Request) {
        $this->load = Application::get_loader();

        $this->Request = $Request;
    }
}