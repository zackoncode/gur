<?php
$routes = [
    '/' => "App\Controller\HomeController@index",
    '/dashboard' => "App\Controller\UserController@isAdmin",
    '/login' => "App\Controller\LoginController@viewLogin",
    '/login/auth'=> "App\Controller\LoginController@login"
];

