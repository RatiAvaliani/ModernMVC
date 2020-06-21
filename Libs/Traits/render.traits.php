<?php
namespace Traits;

trait render {
    /*
     * this will save passed parameters (array) and provide it in view.
     */
    public static $parameters = array();

    /**
     * @param $file
     * getting file name and returning that file.
     * this needs to be used if you need to get a view file.
     */
    public static function render ($file, $parameters=null) {
        self::$parameters = $parameters;
        if (file_exists($file)) require_once $file;
        if ($file === ERROR404) self::error('page was not found');
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