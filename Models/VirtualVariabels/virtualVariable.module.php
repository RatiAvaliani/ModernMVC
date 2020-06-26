<?php
/**
 * Created by PhpStorm.
 * User: r.avaliani
 * Date: 6/22/2020
 * Time: 5:11 AM
 */

namespace virtualVariables;
use Libs\database;

class virtualVariableModule {

    public static $sqlFile = 'sqlInstall.sql';
    public static $getParameter = 'type';
    public static $tableName = 'virtualvariables';

    /**
     * lets you install sql for this module
     */
    public static function install () {
        $GLOBALS['db']->query(file_get_contents(self::$sqlFile));
    }

    public function __construct() {}

    /**
     * @param null $name
     * @param null $content
     * @param null $type
     * @param null $lang
     * @return $this
     * sets virtual variable
     */
    public function set ($name=null, $content=null, $type=null, $lang=null) {
        if (is_null($name) || is_null($content)) self::error('name or content is empty');
        $insert = ['name' => $name, ' content' => $content];

        if (is_null($type)) $insert['type'] = $type;
        if (is_null($lang)) $insert['lang'] = $lang;

        $GLOBALS['db']->create(self::$tableName, $insert);

        return $this;
    }

    /**
     * @param null $Id
     * @return $this
     * using virtual variable id you can get contents
     */
    public function get ($Id=null) {
        if (is_null($Id)) self::error('passed type is null');

        $GLOBALS['db']->reed(self::$tableName, 'id', $Id);

        return $this;
    }

    /**
     * @param null $typeId
     * @return mixed
     *  returns full list of virtual variables or filters using type id
     */
    public function getAll ($typeId=null) {
        if (is_null($typeId)) return $GLOBALS['db']->reed(self::$tableName);

        return $GLOBALS['db']->reed(self::$tableName, self::$getParameter, $typeId);
    }

    /**
     * @param null $typeId
     * @return string
     * returns getAll function return just converted to json
     */
    public function getAllJson ($typeId=null) {
        return json_encode($this->getAll($typeId));
    }
}