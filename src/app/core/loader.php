<?php
defined('BASEPATH') or exit('No direct script access allowed');

use voku\helper\HtmlMin;
use voku\twig\MinifyHtmlExtension;

class Loader {
    public function config(String $config_file) : Object {
        $config_file = str_replace('.php', '', $config_file);
        $config_file = APPPATH."/config/$config_file.php";

        if(!file_exists($config_file))
            throw new Exception("No file found $config_file.");

        include_once($config_file);
        $results = new stdClass();
        foreach($config as $key => $value) {
            $results->$key = $value;
        }

        return $results;
    }

    public function helper(String $helper_file) : void {
        $helper_file = str_replace('.php', '', $helper_file);
        $helper_file = APPPATH."/helpers/$helper_file.php";

        if(!file_exists($helper_file))
            throw new Exception("No file found $helper_file.");

        require_once $helper_file;
    }

    public function library(String $library_file) : void {
        $library_file = str_replace('.php', '', $library_file);
        $library_file = APPPATH."/librarys/$library_file.php";

        if(!file_exists($library_file))
            throw new Exception("No file found $library_file.");

        require_once $library_file;
    }

    public function view(ViewModel $model) {
        $minifier = new HtmlMin();
        $loader = new \Twig\Loader\FilesystemLoader(APPPATH.'/views/');
        switch ($_ENV['DEBUG']) {
            case '1':
                $twig = new \Twig\Environment($loader, []);
                break;
            
            default:
                $twig = new \Twig\Environment($loader, [
                    'cache' => BASEPATH.'/.cache/',
                ]);
                break;
        }

        $twig->addExtension(new MinifyHtmlExtension($minifier));
        exit($twig->render('base_view.html', ['context' => $model]));
    }
}