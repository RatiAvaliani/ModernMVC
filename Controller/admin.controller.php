<?php
/**
 * Created by PhpStorm.
 * User: r.avaliani
 * Date: 7/8/2020
 * Time: 2:02 PM
 */

namespace Controller;
use Controller\controller;
use Traits\render;
use Traits\session;

class admin extends controller {
    use render;
    use session;

    private $errorStatus = [
        "empty" => [
            "status"  => 0,
            "message" => "Passed elements are empty."
        ],
        "wrong" => [
            "status"  => 0,
            "message" => "Username or Password is incorrect."
        ],
        "success" => [
            "status"  => 1
        ]
    ];

    public function __construct () {
        parent::__construct();
    }

    public function index () {}

    public function login () {}

    public function loginPost ($content=[]) {
        if (
                empty($content) ||
                !isset($content['username']) || trim($content['username']) === "" ||
                !isset($content['password']) || trim($content['password']) === ""
            ) self::renderJson($this->errorStatus['empty']);

        $status = $this->modal->login(trim($content['username']), trim($content['password']));

        if ($status === 'success') {
            self::setSession(true, 'login');
            self::setSession(1, 'privilege');
        }

        self::renderJson($this->errorStatus[$status]);
    }
}