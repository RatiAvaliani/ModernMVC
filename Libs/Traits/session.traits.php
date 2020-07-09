<?php
namespace Traits;

trait session {

    /**
     * @param null $id
     * @return array
     * getting by id if there is no id it will return all of the sessions.
     */
    public static function getSession ($id=null) {
        if (is_null($id)) return $_SESSION;

        return array_key_exists($id, $_SESSION) ? $_SESSION[$id] : false;
    }

    /**
     * @param null $content
     * @param null $id
     * @return array
     * set session by id if there is no id it will add the content normally.
     */
    public static function setSession ($content=null, $id=null) {
        if (is_null($content)) return self::error('content empty');
        if (is_null($id)) {
            $_SESSION[] = $content;
            return $_SESSION;
        }

        $_SESSION[$id] = $content;
        return $_SESSION;
    }
}