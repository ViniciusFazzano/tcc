<?php
namespace Routes\api\v1;

use Http\Response;
use \App\Controller\Usuario;
// print_r('batata');exit;
$obRouter->post('/api/v1/users', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::cadastroUsuario($request), 'application/json');
    }
]);

$obRouter->get('/api/v1/users', [
    'rolePermissao' => [
        'A',
    ],
    'middlewares' => [
        'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::consultaUsuario($request), 'application/json');
    }
]);

$obRouter->patch('/api/v1/users/update/{idUsuario}', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request, $idUsuario)
    {
        return new Response(200, Usuario::updateUsuario($request, $idUsuario), 'application/json');
    }
]);


/**
 * Profissao
 */
$obRouter->get('/api/v1/users/profissao', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::consultaProfissao($request), 'application/json');
    }
]);

$obRouter->post('/api/v1/users/profissao', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::cadastroProfissao($request), 'application/json');
    }
]);

$obRouter->patch('/api/v1/users/profissao/update/{idProfissao}', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request, $idProfissao)
    {
        return new Response(200, Usuario::updateProfissao($request, $idProfissao), 'application/json');
    }
]);

$obRouter->delete('/api/v1/users/profissao/delete/{idProfissao}', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request, $idProfissao)
    {
        return new Response(200, Usuario::deleteProfissao($request, $idProfissao), 'application/json');
    }
]);

/**
 * Escolaridade
 */
$obRouter->get('/api/v1/users/escolaridade', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::consultaEscolaridade($request), 'application/json');
    }
]);
 
$obRouter->post('/api/v1/users/escolaridade', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::cadastroEscolaridade($request), 'application/json');
    }
]);

$obRouter->patch('/api/v1/users/escolaridade/update/{idEscolaridade}', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request, $idEscolaridade)
    {
        return new Response(200, Usuario::updateEscolaridade($request, $idEscolaridade), 'application/json');
    }
]);

$obRouter->post('/api/v1/users/auth', [
    'rolePermissao' => [
        '',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::authUsuario($request), 'application/json');
    }
]);

//adim
$obRouter->get('/api/v1/users/admin/daPermitido', [
    'rolePermissao' => [
        'A',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::consultaDeUsuarioQueFizeramOproprioCadastro($request), 'application/json');
    }
]);

$obRouter->patch('/api/v1/users/admin/daPermitido/{idUsuario}', [
    'rolePermissao' => [
        'A',
    ],
    'middlewares' => [
        'jwt-auth'
        // 'cache'  
    ],
    function ($request, $idUsuario)
    {
        return new Response(200, Usuario::daPermitidoParaUsuarioAutoCadastro($request, $idUsuario), 'application/json');
    }
]);

$obRouter->post('/api/v1/users/admin/cadastraUser', [
    'rolePermissao' => [
        'A',
    ],
    'middlewares' => [
        // 'jwt-auth'
        // 'cache'  
    ],
    function ($request)
    {
        return new Response(200, Usuario::administradorCadastraUser($request), 'application/json');
    }
]);
