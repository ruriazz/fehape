<?php
defined('BASEPATH') or exit('No direct script access allowed');

use MatthiasMullie\Minify;

if(!function_exists('base_url')) {
    function base_url(String $path = '') : String {
        return BASEURL.$path;
    }
}

if(!function_exists('include_asset')) {
    function include_asset(String $path = '') : String {
        $file = APPPATH."/views/assets/$path";
        
        if (!file_exists($file))
            throw new Exception("No file found $file");

        return $file;
    }
}

if(!function_exists('included_asset')) {
    function included_asset(String $path = '') : String {
        return BASEPATH."/static/$path";
    }
}

if(!function_exists('load_css')) {
    function load_css(String $file, String $rel = 'stylesheet') : Css {
        if(filter_var($file, FILTER_VALIDATE_URL))
            return new Css($file, $rel);

        $file = include_asset($file);

        if (!file_exists($file))
            throw new Exception("No file found $file");

        $minifier = new Minify\CSS($file);
        $minified = $minifier->minify();

        $filename = sha1($minified);
        $filename .= '.min.css';

        if(!file_exists(included_asset("css/$filename")))
            file_put_contents(included_asset("css/$filename"), $minified);

        return new Css(base_url("static/css/$filename", $rel));
    }
}

if(!function_exists('load_js')) {
    function load_js(String $file, String $type = 'text/javascript') : Js {
        if(filter_var($file, FILTER_VALIDATE_URL))
            return new Js($file, $type);

        $file = include_asset($file);

        if (!file_exists($file))
            throw new Exception("No file found $file");

        $minifier = new Minify\JS($file);
        $minified = $minifier->minify();

        $filename = sha1($minified);
        $filename .= '.min.js';

        if(!file_exists(included_asset("js/$filename")))
            file_put_contents(included_asset("js/$filename"), $minified);

        return new Js(base_url("static/js/$filename", $type));
    }
}