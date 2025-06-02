<?php
use Http\Router;

// require_once __DIR__.'/vendor/autoload.php';
require __DIR__.'/Includes/Includes.php';

// $includes = new \Includes\Includes();
$obRouter = new Router(URL);

include __DIR__.'/routes/api.php';

$obRouter->run()->sendResponse();