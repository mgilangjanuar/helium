<?php 
session_start();
date_default_timezone_set('Asia/Jakarta');
require(__DIR__ . '/../../vendor/autoload.php');

foreach (glob('system/*') as $system) {
    require $system;
}

foreach (glob('app/models/*') as $model) {
    require $model;
}

foreach (glob('app/controllers/*') as $controller) {
    require $controller;
}

use \system\App;
App::run(require 'app/config/config.php');