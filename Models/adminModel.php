<?php

namespace Model;
use Model\model;

class adminModel extends Model {

    public function login ($username, $password) {
        $userPassword = $GLOBALS['db']->query('SELECT password FROM users WHERE username = ? AND `privilege` = ?', [$username, 1])->fetch(false);

        return password_verify($password, $userPassword['password']) ? 'success' : 'wrong';
    }

}