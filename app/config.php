<?php
return [
    'app_path' => '/app',
    'www_path' => '/www',
    'db' => [
        'host'  => 'localhost',
        'user'  => 'api_levi9_user',
        'pass'  => 'password',
        'base'  => 'api_levi9',
    ],
    'routs' => [
        '/'             => ['IndexController' => 'indexAction'],
        '/users/'       => ['UsersController' => 'list{method}Action'],
        '/users/{id}/'  => ['UsersController' => 'user{method}Action'],
    ]
];