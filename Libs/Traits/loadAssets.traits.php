<?php
namespace Traits;

trait loadAssets {
    public static function loadJsFile ($fileName=null) {
        $fileName = $fileName . ".js";
        if (is_null($fileName) || !file_exists(ASSETS_PATH . JS_PATH . DS . $fileName)) return;

        $webPath = ASSETS_WEB_PATH . JS_PATH . DS . $fileName;
        $webPath = str_replace('\\', '/', $webPath);

        return "<script src=\"$webPath\" crossorigin=\"anonymous\"></script>";
    }

    public static function loadCssFile ($fileName=null) {
        $fileName = $fileName . ".css";
        if (is_null($fileName) || !file_exists(ASSETS_PATH . CSS_PATH . DS . $fileName)) return;

        $webPath = ASSETS_WEB_PATH . CSS_PATH . DS . $fileName;
        $webPath = str_replace('\\', '/', $webPath);

        return "<link rel=\"stylesheet\" href=\"$fileName\" crossorigin=\"anonymous\">";
    }

    public static function loadDefaultAssets ($controllerName=null, $methodName=null) {
        if (is_null($controllerName) || is_null($methodName)) return;

        if (ASSETS_AUTOLOAD === true) {
            return self::loadCssFile(ucfirst($controllerName) . DS . $methodName) . self::loadJsFile (ucfirst($controllerName) . DS . $methodName);
        }
    }
}