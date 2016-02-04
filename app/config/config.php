<?php 
return [
    'db' => require __DIR__ . '/db.php',
    'assets' => [
        'css' => [
            'public/vendor/bootstrap/dist/css/bootstrap.min.css',
            'public/vendor/bootstrap-material-design/dist/css/bootstrap-material-design.css',
            'public/vendor/bootstrap-material-design/dist/css/ripples.min.css',
            'public/vendor/components-font-awesome/css/font-awesome.min.css',
            'public/assets/css/style.css'
        ],
        'js' => [
            'public/vendor/jquery/dist/jquery.min.js',
            'public/vendor/bootstrap/dist/js/bootstrap.min.js',
            'public/vendor/bootstrap-material-design/dist/js/ripples.min.js',
            'public/vendor/bootstrap-material-design/dist/js/material.min.js',
            'public/assets/js/script.js',
        ],
    ],
    'user' => [
        'admins' => []
    ],
    'params' => [
        'formValidation' => '/site/validate-form'
    ],
    'route' => [
        'defaultController' => 'site',
        'runFunction' => 'action',
        'defaultFunction' => 'index',
        'routes' => [
            '/home' => '\app\controllers\SiteController:actionIndex'
        ]
    ],
];