<?php

namespace Http\Middleware;

use Http\Middleware\JWTAuth;

use \App\User\Model\User as EntityUser;

class Permission extends JWTAuth
{
    public function handle($request, $next)
    {
        $user = $this->auth($request);
       
        $route = $request->getRoute();

        // Verifica se o usuário tem permissão de acessar a rota
        if (! $this->hasPermission($user, $route)) {
            throw new \Exception('Você não tem permissão para acessar esta rota', 403);
        }

        // Passa a requisição para o próximo middleware
        return $next($request);
    }

    private function hasPermission($user, $route)
    {
        // Lógica para verificar se o usuário tem permissão de acessar a rota
        // Retorna true se o usuário tiver permissão e false caso contrário
    }
}