<?php

require_once SYSPATH . '/vendor/autoload.php';

$router = new \Bramus\Router\Router();

require_once SYSPATH . '/core/helpers.php';
require_once SYSPATH . '/core/loaders.php';

require_once APPPATH . '/config/app_routes.php';
$router->run();