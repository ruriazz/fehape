<?php

define('ASSETS', PUBLICDIR . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR);
define('ASSETS_CORE', ASSETS . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);
define('ASSETS_DIST', BASEPATH . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR);

use MatthiasMullie\Minify;

class Assets
{

    public static function core(String $path = "") {
        if (!file_exists(ASSETS_CORE)) {
            mkdir(ASSETS_CORE, 0777);
        }

        return ASSETS_CORE . $path;
    }

    public static function dist(String $path = "") {
        return ASSETS_DIST . $path;
    }


    public static function create(Array $assets)
    {
        $css = array();
        $js = array();

        $callback = function ($value, $key) use (&$css, &$js) {
            $type = pathinfo($value, PATHINFO_EXTENSION);
            if (is_bool(strpos($value, self::dist())))
                $value = self::dist("$value");

            if (!file_exists($value))
                throw new Exception("No file found $value");

            if ($type !== 'css' && $type !== 'js')
                throw new Exception("the create assets() function only accepts css and js files, not including .$type files");

            array_push($$type, $value);
            $$type = array_unique($$type);
        };
        array_walk_recursive($assets, $callback);

        $miniCSS = "";
        $miniJS = "";
        foreach ($css as $mainCSS) {
            $file = file_get_contents($mainCSS);
            $file = (new Minify\CSS($file))->minify();
            $miniCSS .= $file;
        }

        foreach ($js as $mainJS) {
            $file = file_get_contents($mainJS);
            $file = (new Minify\JS($file))->minify();
            $miniJS .= $file;
            $rearJs = substr($miniJS, -1);
            if ($rearJs !== ';')
                $miniJS .= ';';
        }

        $miniJS = trim($miniJS);
        if (strlen($miniJS) > 0) {
            $jsFileName = md5($miniJS) . ".min.js";
            $jsFile = self::core($jsFileName);
            if (!file_exists($jsFile))
                file_put_contents($jsFile, $miniJS);
        }

        $miniCSS = trim($miniCSS);
        if (strlen($miniCSS) > 0) {
            $cssFileName = md5($miniCSS) . ".min.css";
            $cssFile = self::core($cssFileName);
            if (!file_exists($cssFile))
                file_put_contents($cssFile, $miniCSS);
        }

        return (object) [
            'css' => isset($cssFileName) ? base_url("assets/core/$cssFileName") : null,
            'js' => isset($jsFileName) ? base_url("assets/core/$jsFileName") : null
        ];
    }
}
