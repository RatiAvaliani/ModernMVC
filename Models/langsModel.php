<?php

namespace Model;
use Model\model;
use Libs\virtualVariables;
use Traits\log;

class langsModel extends Model {

    use log;

    public $virtualVariables;
    public $langs;
    private static $db;
    private $langsList;
    private static $status = [
        'active'     => 1,
        'deactivate' => 0
    ];

    private static $langFileName =  'lang.js';

    public function __construct () {
        self::$db = $GLOBALS['db'];
        $this->virtualVariables =  new \Libs\virtualVariables ();

        $this->setInFile();
    }

    protected static function install () {
        $virtual = (bool) self::$db->query('SELECT IF (NOT EXISTS (SELECT * FROM virtualvariables), "true", "false")');

        if (!$virtual) self::error('this module needs virtual variables module to work');
    }

    public function getLang () {
        $cont = $this->virtualVariables->getAll(VIRTUAL_VARIABLES_TYPE['lang'])->all(false);

        if (empty($cont)) self::error('fetched content is empty');
        return $cont;
    }

    public function getLangList () {
        $this->langsList = self::$db->read('langs', self::$status['active'], 'status')->column('id');;

        if (empty($this->langsList)) self::error('fetched data is empty return -> false <-');
        return $this;
    }

    private function setInFile () {
        $this->getLangList();
        $retCont = [];
        foreach ($this->getLang() as $key => $cont) {
            $retCont[$this->langsList[$cont['lang']]['lang_title']][] = $cont;
        }
        file_put_contents(LANG_LOAD_FILE . self::$langFileName, "export default " . json_encode($retCont) . " ");
    }

    public function autoUpdate () {
        $this->setInFile();
    }

    public function loadLang () {
        //$this->langs = file_get_contents(LANG_LOAD_FILE . self::$langFileName);
    }

    public function set ($content=array()) {
        if (empty($content)) self::error('contents array empty');
        //$this->virtualVariables->set();
    }

    public function loadView () {

    }
}