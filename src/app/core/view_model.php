<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ViewModel {
    public String $page_body;
    public String $page_title;
    public Array $data;
    public Array $css;
    public Array $js;
    public Array $meta_tags;

    function __construct(
        String $page_body,
        String $page_title = 'document',
        Array $data = [],
        Array $css = [],
        Array $js = [],
        Array $meta_tags = []
    ) {
        if(!file_exists(APPPATH."/views/$page_body"))
            throw new Exception("No file found $page_body.", 1);

        $this->page_body = $page_body;
        $this->page_title = $page_title;
        $this->data = $data;

        $css_solved = array();
        $js_solved = array();
        $meta_solved = array();

        $css_solving = function($value, $key) use (&$css_solved) {
            array_push($css_solved, $value);
        };

        $js_solving = function($value, $key) use (&$js_solved) {
            array_push($js_solved, $value);
        };

        $meta_solving = function($value, $key) use (&$meta_solved) {
            array_push($meta_solved, $value);
        };

        array_walk_recursive($css, $css_solving);
        array_walk_recursive($js, $js_solving);
        array_walk_recursive($meta_tags, $meta_solving);

        $this->css = $css_solved;
        $this->js = $js_solved;
        $this->meta_tags = $meta_solved;
    }
}

class Css {
    public String $rel;
    public String $href;

    function __construct(String $href, String $rel = 'stylesheet') {
        $this->rel = $rel;
        $this->href = $href;
    }
}

class Js {
    public String $src;
    public String $type;

    function __construct(String $src, String $type = 'text/javascript') {
        $this->src = $src;
        $this->type = $type;
    }
}

class MetaTags {
    public String $property;
    public String $name;
    public String $content;

    function __construct(String $content, ?String $name = null, ?String $property = null) {
        $this->content = $content;
        if($name) {
            $this->name = $name;
        } else if($property) {
            $this->property = $property;
        }
    }
}