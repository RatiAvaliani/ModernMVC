<?php
use Core\router;

router::get('/')->view('index');
router::get('/settings/adminController', function () {
    var_dump('welcome to settings.');
});
router::get('/admin/langs')->jump();
router::get('/admin/langs/add')->jump();

router::get('/admin');

router::get('/admin/login');
router::post('/admin/login');