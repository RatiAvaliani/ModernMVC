<?php
namespace Traits;

trait render {
    /**
     * @param $file
     * getting file name and returning that file.
     * this needs to be used if you need to get a view file.
     */
    public static function render ($file) {
        if (file_exists($file)) require_once $file;
    }

    /**
     * @param null $url
     * @return null
     * redirecting the be url.
     */
    public static function redirect ($url=null) {
        if (is_null($url)) return null;

        header("Location: ${url}");
    }
}