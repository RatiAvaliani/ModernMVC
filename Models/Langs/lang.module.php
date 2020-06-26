<?php

namespace Lang;

use virtualVariables;

class langModule {

    public $virtualVariables;

    public static $langFileNames = [
            'php' => '.lang.php',
            'js'  => '.lang.json'
    ];

    public static function install () {
        $virtual = (bool) $GLOBALS['db']->query('SELECT IF (NOT EXISTS (SELECT * FROM virtualvariables), "true", "false")');

        if (!$virtual) self::error('this module needs virtual variables module to work');
    }

    public function __construct () {
        $this->virtualVariables =  new \virtualVariables ();
    }

    public function get () {

    }

    public function autoUpdate () {

    }

    public function load () {

    }

    public function set ($content=array()) {
        if (empty($content)) self::error('contents array empty');
        //$this->virtualVariables->set();
    }

    public function loadView () {

    }
}