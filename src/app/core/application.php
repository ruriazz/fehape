<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Application {
    protected $loader;

    function __construct(String $router_file) {
        self::__load_dependencies();
        self::__init_development();

        self::__load_router($router_file);
    }

    static protected function get_loader() : Loader {
        require_once APPPATH.'/helpers/default_helper.php';
        require_once APPPATH.'/core/view_model.php';

        return new Loader();
    }

    private function __init_development() : void {
        $debug = $_ENV['DEBUG'] ?? '1';
        $debug = $debug === '1';

        if($debug) {
            $static_dir = BASEPATH.'/static';

            if ($handle = opendir("$static_dir/css/")) {
                if ($handle = opendir("$static_dir/css/")) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && $entry != '.gitkeep') {
                            unlink("$static_dir/css/$entry");
                        }
                    }
                    closedir($handle);
                }
            }

            if ($handle = opendir("$static_dir/js/")) {
                if ($handle = opendir("$static_dir/js/")) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && $entry != '.gitkeep') {
                            unlink("$static_dir/js/$entry");
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }

    private function __load_dependencies() : void {
        $autoload_file = BASEPATH . '/vendor/autoload.php';
        require_once $autoload_file;

        $debug = $_ENV['DEBUG'] ?? '1';

        if($debug === '1') {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }

        require_once APPPATH . '/core/loader.php';
        require_once APPPATH . '/core/base_controller.php';
    }

    private function __load_router(String $router_file) : void {

        function include_controller($name) : void {
            $result = APPPATH.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR."$name.php";
            if(!file_exists($result))
                throw new Exception("No file found $result.", 1);
                
            include($result);
        }

        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $Router) use($router_file) {
            require_once $router_file;
        });
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
        $route_info = $dispatcher->dispatch($httpMethod, $uri);
        switch ($route_info[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $sapi_type = php_sapi_name();
                if (substr($sapi_type, 0, 3) == 'cgi')
                    header("Status: 404 Not Found");
                else
                    header("HTTP/1.1 404 Not Found");

                $view = file_get_contents(APPPATH.'/views/errors/404.html');
                exit($view);
                break;
            
            case FastRoute\Dispatcher::FOUND:
                $request_uri = $_SERVER['REQUEST_URI'];
                $request_uri = explode('?', $request_uri);
                $query_params = new QueryParams();

                if(count($request_uri) > 1) {
                    $request_uri = explode('&', $request_uri[1]);
                    foreach ($request_uri as $value) {
                        $query = explode('=', $value);
                        $key = $query[0];
                        $query_params->$key = $query[1];
                    }
                }

                $request = new Request();
                $request->query_params = $query_params;
                foreach($_SERVER as $key => $value) {
                    $key = strtolower($key);
                    $request->$key = $value;
                }

                $handler = $route_info[1];
                $args['request'] = $request;
                $args = array_merge($args, $route_info[2]);
                call_user_func_array($handler, $args);
                break;
        }
    }
}

class QueryParams {}
class Request {}