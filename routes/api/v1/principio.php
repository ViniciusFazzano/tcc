<?php

namespace Routes\Api\V1;

use App\Controller\Principio;
use Http\Response;

$obRouter->post('/api/v1/principio/cadastro', [
    'rolePermissao' => [
        'C', 'A',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request) {
        return new Response(200, Principio::cadastraPrincipio($request), 'application/json');
    }
]);

$obRouter->get('/api/v1/principio/consulta', [
    'rolePermissao' => [
        'C', 'A',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request) {
        return new Response(200, Principio::consultaPrincipio($request), 'application/json');
    }
]);