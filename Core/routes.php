<?php
use Core\router;

router::get('/');
router::get('/settings/adminController', function () {
    var_dump('welcome to settings.');
});
router::get('/admin/langs')->jump()->sessionFilter(['login' => true, 'privilege' => 1], 'admin/login');
router::get('/admin/langs/add')->jump()->sessionFilter(['login' => true, 'privilege' => 1], 'admin/login');

router::get('/admin')->sessionFilter(['login' => true, 'privilege' => 1], 'admin/login');

router::get('/admin/login')->sessionFilter(['login' => false], 'admin');
router::post('/admin/login');