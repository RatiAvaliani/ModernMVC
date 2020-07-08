<?php
use Core\router;

router::get('/');
router::get('/settings/adminController', function () {
    var_dump('welcome to settings.');
});
router::get('/admin/langs')->jump();
router::get('/admin/langs/add')->jump();

router::get('/admin')->sessionFilter(['login' => true, 'privilege' => 1], 'admin/login');

router::get('/admin/login');
router::post('/admin/login');