<?php
// require __DIR__.'/../Utils/Environment.php';
// require __DIR__.'/../Utils/Environment.php';
namespace Includes;

require __DIR__ . '/../vendor/autoload.php';

use \Utils\Environment;

use \DB\DataBase;

use \Http\Middleware\Queue as MiddlewareQueue;

// carrega variaveis de ambiente
Environment::load(__DIR__ . '/../');

DataBase::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

// define a constante de url
define('URL', getenv('URL'));

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
    'maintenance' => \Http\Middleware\Maintenance::class,
    'user-basic-auth' => \Http\Middleware\UserBasicAuth::class,
    'jwt-auth' => \Http\Middleware\JWTAuth::class,
    'cache' => \Http\Middleware\Cache::class,
    'permission' => \Http\Middleware\Permission::class
]);


MiddlewareQueue::setDefault([
    'maintenance'
]);