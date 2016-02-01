<?php 
return [
    'db' => require __DIR__ . '/db.php',
    'site' => [
        'defaultController' => 'site',
        'runFunction' => 'action',
        'defaultFunction' => 'index'
    ],
    'assets' => [
        'css' => [
            'public/vendor/bootstrap/dist/css/bootstrap.min.css',
            'public/vendor/bootstrap-material-design/dist/css/roboto.min.css',
            'public/vendor/bootstrap-material-design/dist/css/material.min.css',
            'public/vendor/bootstrap-material-design/dist/css/ripples.min.css',
            'public/vendor/fontawesome/css/font-awesome.min.css',
            'public/assets/css/style.css'
        ],
        'js' => [
            'public/vendor/jquery/jquery-2.1.4.min.js',
            'public/vendor/bootstrap/dist/js/bootstrap.min.js',
            'public/vendor/bootstrap-material-design/dist/js/ripples.min.js',
            'public/vendor/bootstrap-material-design/dist/js/material.min.js',
            'public/assets/js/script.js',
        ],
    ],
    'user' => [
        'admins' => []
    ],
    'params' => require __DIR__ . '/params.php'
];