<?php
namespace Traits;

trait log {
    public static $logFile;
    public static $logMethod;
    public static $logLine;

    /**
     * @param string $message
     * @return null
     * this method needs to be used if code will or may brake, by calling this method you will get an error message in the
     * browser (if the errors is set in the config) and in any way in the logs.
     */
    public static function error ($message="") {
        self::fileInfo ();
        self::write(date("Y-m-d h:i:sa", time()) .'. LINE : ' . self::$logLine . ' ' . self::$logFile . ". METHOD:" . self::$logMethod . " --> ${message} \r\n");

        return null;
    }

    /**
     * @param string $message
     * @return null
     * the same idea as errors but if it needs to be used if need only log in logs.txt
     */
    public static function log ($message="") {
        self::fileInfo ();
        self::write(date("Y-m-d h:i:sa", time()) . '. LINE : ' . self::$logLine . ' ' . self::$logFile . ". METHOD:" . self::$logMethod . "  --> ${message} \r\n");

        return null;
    }

    /**
     * @param string $message
     * write adds content to log.txt
     */
    protected static function write ($message="") {
        file_put_contents(LOGS, file_get_contents(LOGS) . $message);
        if (ERRORS_PRINT) {
            require_once (ART);
            die($message);
        }
    }

    /**
     * returns method info like -> line/function name/file name
     */
    public static function fileInfo () {
        $fileInfo = debug_backtrace();
        if (array_key_exists(1, $fileInfo)) {
            self::$logMethod = $fileInfo[1]['function'];
        } else {
            self::$logMethod = $fileInfo[0]['function'];
        }

        self::$logLine = $fileInfo[1]['line'];
        self::$logFile = $fileInfo[1]['file'];
    }
}