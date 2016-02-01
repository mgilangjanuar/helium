<?php 
return [
    'db' => require __DIR__ . '/db.php',
    'site' => [
        'defaultController' => 'Site',
        'runFunction' => 'action',
        'defaultFunction' => 'index'
    ],
    'assets' => [
        'css' => [
            'public/vendor/bootstrap/dist/css/bootstrap.min.css',
            'public/assets/css/style.css'
        ],
        'js' => [
            'public/vendor/jquery/jquery-2.1.4.min.js',
            'public/vendor/bootstrap/dist/js/bootstrap.min.js',
            'public/assets/js/script.js',
        ],
    ],
    'user' => [
        'admins' => []
    ],
    'params' => require __DIR__ . '/params.php'
];